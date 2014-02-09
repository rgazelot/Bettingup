<?php

namespace features\bootstrap;

use OutOfBoundsException;

use Symfony\Component\HttpFoundation\ParameterBag;

use features\bootstrap\Exception\MalformedPathException,
    features\bootstrap\Exception\ParameterNotFoundException;

class Bag extends ParameterBag
{
    private $cache = [];

    /**
     * Checks the existence of a key in this bag
     *
     * If the deep mode is activated, it will try to interpret the key argument
     * as a nested key. If not, the key will be searched as is.
     *
     * @param mixed   $path Key to search
     * @param boolean $deep If true, will search paths such as foo[bar]
     *
     * @return Boolean true if found, false otherwise
     * @throws MalformedPathException If the path is malformed
     */
    public function has($path, $deep = false)
    {
        if (!$deep) {
            return array_key_exists($path, $this->parameters);
        }

        try {
            $this->translatePath($path);
            return true;
        } catch (ParameterNotFoundException $e) {
            return false;
        }
    }

    /**
     * Translate a path foo[bar] into its value
     *
     * This method was adapted from Symfony's HTTP Foundation's ParameterBag's
     * get method.
     *
     * @param mixed $path Path to the value
     *
     * @return mixed Computed value
     * @throws ParameterNotFoundException
     * @throws MalformedPathException
     */
    private function translatePath($path)
    {
        if (array_key_exists($path, $this->cache)) {
            return $this->cache[$path];
        }

        if (false === $pos = strpos($path, '[')) {
            if (!$this->has($path)) {
                throw new ParameterNotFoundException($path);
            }

            return $this->cache[$path] = $this->parameters[$path];
        }

        $root = substr($path, 0, $pos);

        if (!$this->has($root)) {
            throw new ParameterNotFoundException($path);
        }

        $key   = null;
        $value = $this->parameters[$root];

        for ($i = $pos, $c = strlen($path); $i < $c; ++$i) {
            switch ($path[$i]) {
                case '[':
                    // nested keys not supported
                    if (null !== $key) {
                        throw new MalformedPathException($path[$i], $i);
                    }

                    $key = '';
                    break;

                case ']':
                    // not in a key
                    if (null === $key) {
                        throw new MalformedPathException($path[$i], $i);
                    }

                    if (!is_array($value) || !array_key_exists($key, $value)) {
                        throw new ParameterNotFoundException($path);
                    }

                    $value = $value[$key];
                    $key   = null;
                    break;

                default:
                    // not in a key
                    if (null === $key) {
                        throw new MalformedPathException($path[$i], $i);
                    }

                    $key .= $path[$i];
                    break;
            }
        }

        if (null !== $key) {
            throw new MalformedPathException(null, null, 'a path must end with a "]"');
        }

        return $this->cache[$path] = $value;
    }
}
