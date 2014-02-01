<?php
namespace features\bootstrap;

use Behat\Behat\Context\BehatContext;

use Symfony\Component\EventDispatcher\Event;

use Behat\Mink\Exception\UnsupportedDriverActionException;

/**
 * Debug context.
 */
class DebugContext extends BehatContext
{
    /**
     * In case of something is wrong...
     *
     * @AfterScenario
     *
     */
    public static function debugAfter(Event $e)
    {
        if (4 === $e->getResult()) {
            $e->getContext()->getSubcontext('debug')->debug();
        }
    }

    /**
     * Prints status code from last response and last response
     *
     * @Then /^I want to debug$/
     */
    public function debug()
    {
        try {
            $headers = $this->getMainContext()->getSession()->getResponseHeaders();

            $this->printDebug($this->getMainContext()->getSession()->getStatusCode());
            $this->printDebug($headers['content-type'][0]);
        } catch (UnsupportedDriverActionException $e) { /* can't debug ? do nothing */ }

        $this->getMainContext()->printLastResponse();
    }
}
