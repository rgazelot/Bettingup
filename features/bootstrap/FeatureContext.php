<?php

namespace features\bootstrap;

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException,
    Behat\MinkExtension\Context\MinkContext,
    Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use \PHPUnit_Framework_Assert as Assert;

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    public function __construct(array $parameters)
    {
        $this->useContext('api', new ApiContext);
        $this->useContext('json', new JSONContext);

        if (isset($parameters['debug']) && true === $parameters['debug']) {
            $this->useContext('debug', new DebugContext);
        }
    }

    /**
     * Sends a new request with some values
     *
     * @Given /^I sent a "(?P<type>(?:[^"]*|\\"))" request on "(?P<url>(?:[^"]|\\")*)" with values :$/
     * @When /^I send a "(?P<type>(?:[^"]*|\\"))" request on "(?P<url>(?:[^"]|\\")*)" with values :$/
     *
     * @param string                    $method     a valid HTTP method
     * @param string                    $url        the url to send the request
     * @param array|TableNode           $parameters parameters to send
     * @param string|PyStringNode|null  $body       body to send in the request
     *
     * @throws \InvalidArgumentException $parameters is not an array.
     */
    public function sendRequestWithValues($method, $url, $parameters = [], $files = [], $server = [], $body = null)
    {
        if ($parameters instanceof TableNode) {
            $parameters = $parameters->getHash();
            $parameters = array_shift($parameters);
        } elseif (!is_array($parameters)) {
            throw new \InvalidArgumentException('Array or TableNode expected in sendRequestWithValues');
        }

        if ($body instanceof PyStringNode) {
            $body = $body->getRaw();
        }

        $url        = parent::locatePath($url);
        $parameters = array_filter($parameters, function ($parameter) {
            return '' !== $parameter && null !== $parameter;
        });

        if ('GET' === $method) {
            $url       .= (false === strpos($url, '?') ? '?' : '&') . http_build_query($parameters);
            $parameters = [];
        }

        $this->getSession()->getDriver()->getClient()->request($method, parent::locatePath($url), $parameters, $files ?: [], $server ?: [], $body ?: null);
    }

    /**
     * Sends a new request
     *
     * @Given /^I sent a "(?P<type>(?:[^"]*|\\"))" request (?:on|to) "(?P<url>(?:[^"]|\\")*)"$/
     * @When /^I send a "(?P<type>(?:[^"]*|\\"))" request (?:on|to) "(?P<url>(?:[^"]|\\")*)"$/
     *
     * @param string $method a valid HTTP method
     * @param string $url
     */
    public function sendRequest($method, $url)
    {
        $this->sendRequestWithValues($method, $url, []);
    }

    /**
     * Sends a request with a body
     *
     * @Given /^I sent a "(?P<type>(?:[^"]*|\\"))" request (?:on|to) "(?P<url>(?:[^"]|\\")*)" with body :$/
     * @When /^I send a "(?P<type>(?:[^"]*|\\"))" request (?:on|to) "(?P<url>(?:[^"]|\\")*)" with body :$/
     *
     * @param string $method      a valid HTTP method
     * @param string $url         URL to send the request
     * @param string|PyStringNode Content to send
     */
    public function sendRequestWithBody($method, $url, $body) {
        $this->sendRequestWithValues($method, $url, [], [], [], $body);
    }
}
