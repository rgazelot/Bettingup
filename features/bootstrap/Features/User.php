<?php

namespace features\bootstrap\Features;

use Behat\Behat\Context\BehatContext,
    Behat\Gherkin\Node\PyStringNode;

use \PHPUnit_Framework_Assert as Assert;

class User extends BehatContext
{
    /**
     * @When /^I retrieve the use "([^"]*)"$/
     */
    public function iRetrieveTheUse($slug)
    {
        $this->getMainContext()->getSubContext('api')->sendRequestWithToken('api/1/users/' . $slug, 'GET');
    }

    /**
     * @When /^I create an user with these data :$/
     */
    public function iCreateAnUserWithTheseData(PyStringNode $data)
    {
        $this->getMainContext()->getSubContext('api')->sendRequestWithTokenAndBody('/api/1/users', 'POST', $data);
    }
}
