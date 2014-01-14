<?php

namespace features\bootstrap\Features;

use Behat\Behat\Context\BehatContext;

use \PHPUnit_Framework_Assert as Assert;

class Api extends BehatContext
{
    /**
     * @When /^I fetch any API, like users, wihtout token$/
     */
    public function iFetchAnyApiLikeUsersWihtoutToken()
    {
        $this->getMainContext()->sendRequest('GET', '/api/1/users/1');
    }

    /**
     * @When /^I send a post request with invalid JSON data$/
     */
    public function iSendAPostRequestWithInvalidJsonData()
    {
        $this->getMainContext()->getSubContext('api')->sendRequestWithTokenAndBody('POST', '/api/1/users', "{foo:{}");
    }
}
