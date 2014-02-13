<?php

namespace features\bootstrap\Features;

use Behat\Behat\Context\BehatContext,
    Behat\Gherkin\Node\PyStringNode;

use \PHPUnit_Framework_Assert as Assert;

class Ticket extends BehatContext
{
    /**
     * @When /^I create a ticket with these data:$/
     */
    public function iCreateATicketWithTheseData(PyStringNode $data)
    {
        $this->getMainContext()->getSubContext('api')->sendRequestWithTokenAndBody('/api/1/tickets', 'POST', $data);
    }

    /**
     * @When /^I delete the ticket "([^"]*)"$/
     */
    public function iDeleteTheTicket($hash)
    {
        $this->getMainContext()->getSubContext('api')->sendRequestWithTokenAndBody('/api/1/tickets/' . $hash, 'DELETE', []);
    }

    /**
     * @When /^I fetch the ticket "([^"]*)"$/
     */
    public function iFetchTheTicket($hash)
    {
        $this->getMainContext()->getSubContext('api')->sendRequestWithToken('/api/1/tickets/' . $hash);
    }

    /**
     * @When /^I fetch the tickets of the user "([^"]*)"$/
     */
    public function iFetchTheTicketsOfTheUser($hash)
    {
        $this->getMainContext()->getSubContext('api')->sendRequestWithTokenAndValues('/api/1/tickets', 'GET', ['user' => $hash]);
    }

    /**
     * @When /^I fetch the tickets without parameters$/
     */
    public function iFetchTheTicketsWithoutParameters()
    {
        $this->getMainContext()->getSubContext('api')->sendRequestWithTokenAndValues('/api/1/tickets', 'GET', []);
    }
}
