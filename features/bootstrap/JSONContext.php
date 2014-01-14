<?php

namespace features\bootstrap;

use \Exception;

use Behat\Behat\Context\BehatContext,
    Behat\Gherkin\Node\PyStringNode;

use Symfony\Component\HttpFoundation\ParameterBag;

use \PHPUnit_Framework_Assert as Assert;

class JSONContext extends BehatContext
{
    private $lastJSON = [];

    /**
     * Verifies that the response returns a valid JSON file
     *
     * @Then /^the response should be a valid JSON response$/
     * @throws InvalidException Not a valid JSON.
     */
    public function responseIsValidJSON()
    {
        $headers = $this->getMainContext()->getSession()->getDriver()->getResponseHeaders();

        Assert::assertEquals('application/json', $headers['content-type'][0],
            new Exception('The response is not a valid JSON response. Expected "application/json", got "' . $headers['content-type'][0] . '"'));
    }

    /**
     * Extract the JSON from the last response
     *
     * @return array
     */
    public function getLastJSON()
    {
        $url = $this->getMainContext()->getSession()->getCurrentUrl();

        if (empty($this->lastJSON) || $this->lastJSON['url'] !== $url) {
            $this->responseIsValidJSON();

            $data = $this->getMainContext()->getSession()->getPage()->getContent();

            $this->lastJSON = ['url'   => $url,
                               'raw'   => $data,
                               'std'   => json_decode($data),
                               'array' => new ParameterBag(json_decode($data, true))];
        }

        return $this->lastJSON;
    }

    /**
     * Checks that this JSON contains a specific key. Also checks if it is a
     * valid JSON response. Returns the data.
     *
     * @Then /^the JSON response should contain a "(?P<key>(?:[^"]|\\")*)" key$/
     *
     * @param string $key     key to interpret and verify
     * @param mixed  $default default value if not found
     *
     * @return mixed
     */
    public function jsonShouldContainKey($key, $default = null)
    {
        /** @var ParameterBag $data  */
        $data = $this->getLastJSON()['array'];

        Assert::assertNotEquals(false, $data->has($key, true), new Exception('The JSON does not have expected key "' . $key . '"'));

        return $data->get($key, $default, true);
    }

    /**
     * Checks that the JSON key `$key` has the `$expected` value
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should be equal to "(?P<expected>(?:[^"]|\\")*)"$/
     */
    public function theJsonKeyShouldBeEqualTo($key, $expected)
    {
        Assert::assertEquals($this->jsonShouldContainKey($key), $expected);
    }

    /**
     * Checks that the JSON key `$key` has the `$expected` value (which is a number)
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should be equal to (?P<expected>[0-9]+)$/
     */
    public function theJsonKeyShouldBeEqualToNumber($key, $expected)
    {
        Assert::assertInternalType('int', $this->jsonShouldContainKey($key));
        Assert::assertEquals($this->jsonShouldContainKey($key), (int) $expected);

    }

    /**
     * Checks that the JSON key `$key` does not have the `$expected` value (which is a number)
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should not be equal to (?P<expected>[0-9]+)$/
     */
    public function theJsonKeyShouldNotBeEqualToNumber($key, $expected)
    {
        Assert::assertInternalType('int', $this->jsonShouldContainKey($key));
        Assert::assertNotEquals($this->jsonShouldContainKey($key), (int) $expected);
    }

    /**
     * Checks that the JSON key `$key` has the `$expected` value (which is a number)
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should be equal to (?P<expected>[0-9]+\.[0-9]+)$/
     */
    public function theJsonKeyShouldBeEqualToFloat($key, $expected)
    {
        Assert::assertInternalType('float', $this->jsonShouldContainKey($key));
        Assert::assertEquals($this->jsonShouldContainKey($key), (float) $expected);
    }

    /**
     * Checks that the JSON has value in array
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should contain "(?P<expected>(?:[^"]|\\")*)"$/
     */
    public function theJsonKeyShouldContainInArray($key, $expected)
    {
        Assert::assertInternalType('array', $this->jsonShouldContainKey($key));
        Assert::assertEquals(in_array($expected, $this->jsonShouldContainKey($key)), true);
    }

    /**
     * Checks that the JSON key `$key` is true or false
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should be (?P<expected>true|false)$/
     */
    public function theJsonKeyShouldBe($key, $expected)
    {
        Assert::assertEquals($this->jsonShouldContainKey($key), $expected === 'true');
    }

    /**
     * Checks that the JSON key `$key` is not true or false
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should not be (?P<expected>true|false)$/
     */
    public function theJsonKeyShouldNotBe($key, $expected)
    {
        Assert::assertNotEquals($this->jsonShouldContainKey($key), $expected === 'true');
    }

    /**
     * Checks that the JSON key `$key` is true or false
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should not be equal to "(?P<expected>(?:[^"]|\\")*)"$/
     */
    public function theJsonKeyShouldNotBeEqualTo($key, $expected)
    {
        Assert::assertNotEquals($this->jsonShouldContainKey($key), $expected);
    }

    /**
     * Checks that the JSON key `$key` is null
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should be null$/
     */
    public function theJsonKeyShouldBeNull($key)
    {
        Assert::assertNull($this->jsonShouldContainKey($key, -1));
    }

    /**
     * Checks that the JSON key `$key` is not null
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should not be null$/
     */
    public function theJsonKeyShouldNotBeNull($key)
    {
        Assert::assertNotNull($this->jsonShouldContainKey($key, -1));
    }

    /**
     * Checks that the JSON key `$key` has the `$expected` value
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should be equal to :$/
     */
    public function theJsonKeyShouldBeEqualToLong($key, PyStringNode $expected)
    {
        Assert::assertEquals($this->jsonShouldContainKey($key), $expected->getRaw());
    }

    /**
     * Checks that the JSON key `$key` is empty
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should be empty$/
     */
    public function theJsonKeyShouldBeEmpty($key)
    {
        Assert::assertEmpty($this->jsonShouldContainKey($key), new KeyNotEmptyException('The JSON key "' . $key . '" is not empty'));
    }

    /**
     * Checks that the JSON key `$key` is not empty
     *
     * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should not be empty$/
     */
    public function theJsonKeyShouldNotBeEmpty($key)
    {
        Assert::assertNotEmpty($this->jsonShouldContainKey($key), new KeyEmptyException('The JSON key "' . $key . '" is empty'));
    }

    // /**
    //  * Checks than the JSON key `$key` is greater than `$number`
    //  *
    //  * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should be greater than (?P<number>[0-9]+)$/
    //  */
    // public function theJsonKeyShouldBeGreaterThan($key, $number)
    // {
    //     Assert::assertGreaterThan($number, $this->jsonShouldContainKey($key));
    // }

    // /**
    //  * Checks than the JSON key `$key` is lower than `$number`
    //  *
    //  * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should be lower than (?P<number>[0-9]+)$/
    //  */
    // public function theJsonKeyShouldBeLowerThan($key, $number)
    // {
    //     Assert::assertLessThan($number, $this->jsonShouldContainKey($key));
    // }

    // /**
    //  * Checks than the JSON key `$key` is greater or equal than `$number`
    //  *
    //  * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should be greater or equal than (?P<number>[0-9]+)$/
    //  */
    // public function theJsonKeyShouldBeGreaterOrEqualThan($key, $number)
    // {
    //     Assert::assertGreaterThanOrEqual($number, $this->jsonShouldContainKey($key));
    // }

    // /**
    //  * Checks than the JSON key `$key` is lower or equal than `$number`
    //  *
    //  * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should be lower or equal than (?P<number>[0-9]+)$/
    //  */
    // public function theJsonKeyShouldBeLowerOrEqualThan($key, $number)
    // {
    //     Assert::assertLessThanOrEqual($number, $this->jsonShouldContainKey($key));
    // }

    // *
    //  * Checks that the JSON key `$key` have at least `$number` elements
    //  *
    //  * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should have at least ([0-9]+) elements?$/

    // public function theJsonKeyShouldHaveAtLeastElements($key, $number)
    // {
    //     Assert::assertHasAtLeastElements($this->jsonShouldContainKey($key), $number);
    // }

    // /**
    //  * Checks that the JSON key `$key` have `$number` elements
    //  *
    //  * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should have ([0-9]+) elements?$/
    //  */
    // public function theJsonKeyShouldHaveElements($key, $number)
    // {
    //     Assert::assertInRange($this->jsonShouldContainKey($key), $number);
    // }

    // /**
    //  * Checks that the JSON key `$key` have at most `$number` elements
    //  *
    //  * @Then /^the JSON key "(?P<key>(?:[^"]|\\")*)" should have at most (?P<number>[0-9]+) elements?$/
    //  */
    // public function theJsonKeyShouldHaveAtMostElements($key, $number)
    // {
    //     Assert::assertHasAtMostElements($this->jsonShouldContainKey($key), $number);
    // }

    // /**
    //  * Checks that each element in an JSON element has an integer value of at least `$number`
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should be greater or equal than (?P<number>[0-9]+)$/
    //  */
    // public function eachJsonElementInShouldBeGreaterThan($element, $key, $number)
    // {
    //     foreach ($this->getEachKeys($key, $element) as $node) {
    //         Assert::assertIsGreaterOrEqualThan($node, $number);
    //     }
    // }

    // /**
    //  * Checks that each element in a an JSON key has a key equal to `$expected`
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should be equal to "(?P<expected>[^"]*)"$/
    //  *
    //  */
    // public function eachElementInTheJsonKeyShouldBeEqualTo($element, $key, $expected)
    // {
    //    foreach ($this->getEachKeys($key, $element) as $node) {
    //         Assert::assertEqual($node, $expected);
    //     }
    // }

    // /**
    //  * Checks that each element in a an JSON key has a key not equal to `$expected`
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should not be equal to (?P<expected>[0-9]*)$/
    //  *
    //  */
    // public function eachElementInTheJsonKeyShouldNotBeEqualToNumber($element, $key, $expected)
    // {
    //    foreach ($this->getEachKeys($key, $element) as $node) {
    //         Assert::assertIsInteger($node);
    //         Assert::assertNotEquals($node, (int) $expected);
    //     }
    // }

    // /**
    //  * Checks that each element in a an JSON key has a key equal to `$expected`
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should be equal to (?P<expected>[0-9]*)$/
    //  *
    //  */
    // public function eachElementInTheJsonKeyShouldBeEqualToNumber($element, $key, $expected)
    // {
    //    foreach ($this->getEachKeys($key, $element) as $node) {
    //         Assert::assertIsInteger($node);
    //         Assert::assertEquals($node, (int) $expected);
    //     }
    // }

    // /**
    //  * Checks that each element in a an JSON key has a key not equal to `$expected`
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should not be equal to "(?P<expected>[^"]*)"$/
    //  *
    //  */
    // public function eachElementInTheJsonKeyShouldNotBeEqualTo($element, $key, $expected)
    // {
    //    foreach ($this->getEachKeys($key, $element) as $node) {
    //         Assert::assertNotEqual($node, $expected);
    //     }
    // }

    // /**
    //  * Checks that each element in a JSON key is true or false
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should be (?P<flag>true|false)$/
    //  */
    // public function eachElementInTheJsonKeyShouldBe($element, $key, $flag)
    // {
    //     $this->eachElementInTheJsonKeyShouldBeEqualTo($element, $key, $flag === 'true');
    // }

    // /**
    //  * Checks that each element in a JSON key is not true or not false
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should not be (?P<flag>true|false)$/
    //  */
    // public function eachElementInTheJsonKeyShouldNotBe($element, $key, $flag)
    // {
    //     $this->eachElementInTheJsonKeyShouldNotBeEqualTo($element, $key, $flag === 'true');
    // }

    // /**
    //  * Checks that each element in a JSON key is older than a specific date
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should not be older than "(?P<date>(?:[^"]|\\")*)"$/
    //  */
    // public function eachElementInTheJsonKeyShouldBeOlderThan($element, $key, $date)
    // {
    //     $date = new \DateTime($date);

    //     foreach ($this->getEachKeys($key, $element) as $node) {
    //         $current = new \DateTime($node);

    //         // does it worth it to make an assert for dates ? assertIsOlder($cur, $prev)
    //         Assert::assertIsLowerOrEqualThan($date->getTimestamp(), $current->getTimestamp(), new LowerThanException('This element is younger'));
    //     }
    // }

    // /**
    //  * Checks that each element in a JSON key is older than the last one
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should be older than the previous one$/
    //  */
    // public function eachElementInTheJsonKeyShouldBeOlderThanThePreviousOne($element, $key)
    // {
    //     /** @var \DateTime $previous */
    //     $previous = null;

    //     foreach ($this->getEachKeys($key, $element) as $node) {
    //         $current = new \DateTime($node);

    //         if ($previous !== null) {
    //             // does it worth it to make an assert for dates ? assertIsOlder($cur, $prev)
    //             Assert::assertIsGreaterOrEqualThan($previous->getTimestamp(), $current->getTimestamp(), new LowerThanException('This element is younger than the previous one'));
    //         }

    //         $previous = $current;
    //     }
    // }

    // /**
    //  * Checks that each element in a JSON key is greater than the previous one
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should be greater than the previous one$/
    //  */
    // public function eachElementInTheJsonKeyShouldBeGreaterThanThePreviousOne($element, $key)
    // {
    //     $previous = null;
    //     $i = 0;

    //     foreach ($this->getEachKeys($key, $element) as $node) {
    //         if ($previous !== null) {
    //             Assert::assertIsGreaterThan($node, $previous, new LowerThanException('The element "' . $key . '[' . $i . '][' . $element . ']" (' . $node . ') is lower than the previous one (' . $previous . ')'));
    //         }

    //         $i++;

    //         $previous = $node;
    //     }
    // }

    // /**
    //  * Checks that each element in a JSON key is greater than the previous one
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should be greater or equal than the previous one$/
    //  */
    // public function eachElementInTheJsonKeyShouldBeGreaterOrEqualThanThePreviousOne($element, $key)
    // {
    //     $previous = null;
    //     $i = 0;

    //     foreach ($this->getEachKeys($key, $element) as $node) {
    //         if ($previous !== null) {
    //             Assert::assertIsGreaterOrEqualThan($node, $previous, new LowerThanException('The element "' . $key . '[' . $i . '][' . $element . ']" (' . $node . ') is lower than the previous one (' . $previous . ')'));
    //         }

    //         $i++;

    //         $previous = $node;
    //     }
    // }

    // /**
    //  * Checks that each element in a JSON key is greater than the previous one
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should be lower than the previous one$/
    //  */
    // public function eachElementInTheJsonKeyShouldBeLowerThanThePreviousOne($element, $key)
    // {
    //     $previous = null;
    //     $i = 0;

    //     foreach ($this->getEachKeys($key, $element) as $node) {
    //         if ($previous !== null) {
    //             Assert::assertIsLowerThan($previous, $node, new GreaterThanException('The element "' . $key . '[' . $i . '][' . $element . ']" is lower than the previous one'));
    //         }

    //         $i++;

    //         $previous = $node;
    //     }
    // }

    // /**
    //  * Checks that each element in a JSON key is greater than the previous one
    //  *
    //  * @Then /^each "(?P<element>(?:[^"]|\\")*)" element in the JSON key "(?P<key>(?:[^"]|\\")*)" should be lower or equal than the previous one$/
    //  */
    // public function eachElementInTheJsonKeyShouldBeLowerOrEqualThanThePreviousOne($element, $key)
    // {
    //     $previous = null;
    //     $i = 0;

    //     foreach ($this->getEachKeys($key, $element) as $node) {
    //         if ($previous !== null) {
    //             Assert::assertIsLowerOrEqualThan($previous, $node, new GreaterThanException('The element "' . $key . '[' . $i . '][' . $element . ']" is lower than the previous one'));
    //         }

    //         $i++;

    //         $previous = $node;
    //     }
    // }

    // /**
    //  * Checks that the returned elements have at least one element with a specific value
    //  *
    //  * @Then /^the element "(?P<element>(?:[^"]|\\")*)" should have the value "(?P<value>(?:[^"]|\\")*)" at least once in the JSON key "(?P<key>(?:[^"]|\\")*)"$/
    //  *
    //  * @param type $key
    //  * @param type $element
    //  * @param type $value
    //  */
    // public function theElementShouldHaveTheValueAtLeastOnceInTheJsonKey($key, $element, $value)
    // {
    //     Assert::assertHasValueAtLeast($this->getEachKeys($key, $element), $value, 1);
    // }

    // /**
    //  * Stores a value for a specific key
    //  *
    //  * @Then /^(?:I )?store the JSON key "(?P<key>(?:[^"]|\\")*)"$/
    //  */
    // public function storeKeyValue($key)
    // {
    //    return FeatureContext::$storedRef = $this->jsonShouldContainKey($key);
    // }

    // /**
    //  * Stores a named value for a specific key
    //  *
    //  * @Then /^(?:I )?store the JSON key "(?P<key>(?:[^"]|\\")*)" in reference "(?P<reference>(?:[^"]|\\")+)"$/
    //  */
    // public function storeNamedKeyValue($key, $reference)
    // {
    //     return $this->getMainContext()->storeValueInRef($this->jsonShouldContainKey($key), $reference);
    // }

    // /**
    //  * Returns the keys of the array to format.
    //  *
    //  * @param string $key     first part of the array, containing the global immuable part
    //  * @param string $element part where we need to test each occurence of this key in $key
    //  *
    //  * @return array
    //  */
    // private function getEachKeys($key, $element)
    // {
    //     if (false == $pos = strpos($element, '[')) {
    //         $pos = strlen($element);
    //     }

    //     $element = $key . '[%s]' . '[' . substr($element, 0, $pos) . ']' . substr($element, $pos);
    //     $return = [];

    //     foreach(array_keys($this->jsonShouldContainKey($key)) as $node) {
    //         $return[] = $this->jsonShouldContainKey(sprintf($element, $node));
    //     }

    //     return $return;
    // }
}
