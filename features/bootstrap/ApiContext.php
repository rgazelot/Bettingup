<?php

namespace features\bootstrap;

use Behat\Behat\Context\BehatContext;

use \PHPUnit_Framework_Assert as Assert;

use features\bootstrap\Features\Api,
    features\bootstrap\Features\User,
    features\bootstrap\Features\Ticket;

class ApiContext extends BehatContext
{
    private $method;
    private $token;

    public function __construct()
    {
        $this->useContext('api.api', new Api);
        $this->useContext('api.user', new User);
        $this->useContext('api.ticket', new Ticket);
    }

    /**
     * @Given /^I use the token of an admin$/
     */
    public function iUseTheTokenOfAnAdmin()
    {
        $this->token = sha1('admin');
    }

    public function getUrlWithToken($url)
    {
        return $url . (false !== strrpos($url, '?') ? '&' : '?') . 'token=' . $this->token;
    }

    /**
     * Get somewhere with the token on a specific request
     *
     * @When /^(?:I )?send a "(?P<method>(?:[^"]*|\\"))" request (?:to|on) "(?P<url>(?:[^"]|\\")*)" with my token$/
     *
     * @param string $method Method to use
     * @param string $url URL to go to
     *
     * @throws \Exception No token, no access !
     */
    public function sendRequestWithToken($url, $method = 'GET')
    {
        $this->getMainContext()->sendRequest($method, $this->getUrlWithToken($url));
    }

    /**
     * Sends a new request with some values and token
     *
     * @Given /^I sent a (?P<method>[a-zA-Z]+) request on "(?P<url>(?:[^"]|\\")*)" with my token and these values :$/
     * @When /^I send a (?P<method>[a-zA-Z]+) request on "(?P<url>(?:[^"]|\\")*)" with my token and these values :$/
     *
     * @param string    $url        the url to send the request
     * @param mixed     $parameters parameters to send
     * @param string    $method     method used
     */
    public function sendRequestWithTokenAndValues($url, $method = 'POST', $parameters)
    {
        $this->getMainContext()->sendRequestWithValues(strtoupper($method), $this->getUrlWithToken($url), $parameters);
    }

    /**
     * Sends a request with a body and token
     *
     * This function is used to pass an array in the JSON. If your data is only
     * simple values (non multidimensional array), please use
     * self::sendRequestWithTokenAndValues
     *
     * @Given /^I sent a (?P<method>[a-zA-Z]+) request (?:on|to) "(?P<url>(?:[^"]|\\")*)" with my token and this body :$/
     * @When /^I send a (?P<method>[a-zA-Z]+) request (?:on|to) "(?P<url>(?:[^"]|\\")*)" with my token and this body :$/
     *
     * @param string              $url    URL to send the request
     * @param string|PyStringNode $body   Content to send
     * @param string              $method method used
     */
    public function sendRequestWithTokenAndBody($url, $method = 'POST', $body)
    {
        $this->getMainContext()->sendRequestWithValues(strtoupper($method), $this->getUrlWithToken($url), [], [], [], $body);
    }
}
