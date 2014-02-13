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
    public function iDeleteTheTicket($id)
    {
        $this->getMainContext()->getSubContext('api')->sendRequestWithTokenAndBody('/api/1/tickets/' . $id, 'DELETE', []);
    }
}
