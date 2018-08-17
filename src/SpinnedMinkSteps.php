<?php

namespace Exozet\Behat\Utils\Base;

trait SpinnedMinkSteps {
    /**
     * @see MinkContext::assertPageAddress
     *
     * @Then /^bin ich auf "(?P<page>[^"]+)" innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     * @Then /^I should be on "(?P<page>[^"]+)" within (?P<seconds>(\d+)) seconds?$/
     *
     * @throws \Exception
     */
    public function assertPageAddressWithinSpecifiedTime($page, $seconds)
    {
        $assertPageAddress = function($context) use ($page) {
            try {
                $context->assertPageAddress($page);
                return true;
            }
            catch (\Exception $e) {
                // Do nothing, try again
            }
            return false;
        };
        $this->spin($assertPageAddress, $seconds);
    }

    /**
     * @see MinkContext::assertPageContainsText
     *
     * @Then /^sehe ich "(?P<text>(.+))" innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     * @Then /^I should see "(?P<text>(.+))" within (?P<seconds>(\d+)) seconds?$/
     *
     * @throws \Exception
     */
    public function assertPageContainsTextWithinSpecifiedTime($text, $seconds)
    {
        $assertPageContainsText = function($context) use ($text) {
            try {
                $context->assertPageContainsText($text);
                return true;
            }
            catch (\Exception $e) {
                // Do nothing, try again
            }
            return false;
        };
        $this->spin($assertPageContainsText, $seconds);
    }

    /**
     * @see MinkContext::assertPageNotContainsText
     *
     * @Then /^sehe ich "(?P<text>(.+))" nicht innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     * @Then /^I should not see "(?P<text>(.+))" within (?P<seconds>(\d+)) seconds?$/
     *
     * @throws \Exception
     */
    public function assertPageNotContainsTextWithinSpecifiedTime($text, $seconds)
    {
        $assertPageNotContainsText = function($context) use ($text) {
            try {
                $context->assertPageNotContainsText($text);
                return true;
            }
            catch (\Exception $e) {
                // Do nothing, try again
            }
            return false;
        };
        $this->spin($assertPageNotContainsText, $seconds);
    }

    /**
     * @see MinkContext::assertElementContainsText
     *
     * @Then /^sehe ich "(?P<text>(.+))" im "(?P<element>[^"]+)"-Element innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     * @Then /^I should see "(?P<text>(.+))" in the "(?P<element>[^"]+)" element within (?P<seconds>(\d+)) seconds?$/
     *
     * @throws \Exception
     */
    public function assertElementContainsTextWithinSpecifiedTime($element, $text, $seconds)
    {
        $assertElementContainsText = function($context) use ($element, $text) {
            try {
                $context->assertElementContainsText($element, $text);
                return true;
            }
            catch (\Exception $e) {
                // Do nothing, try again
            }
            return false;
        };
        $this->spin($assertElementContainsText, $seconds);
    }

    /**
     * @see MinkContext::assertElementOnPage
     *
     * @Then /^sehe ich ein "(?P<element>[^"]+)"-Element innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     * @Then /^I should see an? "(?P<element>[^"]+)" element within (?P<seconds>(\d+)) seconds?$/
     *
     * @throws \Exception
     */
    public function assertElementOnPageWithinSpecifiedTime($element, $seconds)
    {
        $assertElementOnPage = function($context) use ($element) {
            try {
                $context->assertElementOnPage($element);
                return true;
            }
            catch (\Exception $e) {
                // Do nothing, try again
            }
            return false;
        };
        $this->spin($assertElementOnPage, $seconds);
    }

    /**
     * @see MinkContext::assertElementNotOnPage
     *
     * @Then /^sehe ich kein "(?P<element>[^"]+)"-Element innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     * @Then /^I should not see an? "(?P<element>[^"]+)" element within (?P<seconds>(\d+)) seconds?$/
     *
     * @throws \Exception
     */
    public function assertElementNotOnPageWithinSpecifiedTime($element, $seconds)
    {
        $assertElementNotOnPage = function($context) use ($element) {
            try {
                $context->assertElementNotOnPage($element);
                return true;
            }
            catch (\Exception $e) {
                // Do nothing, try again
            }
            return false;
        };
        $this->spin($assertElementNotOnPage, $seconds);
    }

    /**
     * @see MinkContext::fillField
     *
     * @When /^ich "(?P<field>(.+))" mit "(?P<value>(.+))" innerhalb von (?P<seconds>(\d+)) Sekunden? ausfülle$/
     * @When /^I fill in "(?P<field>(.+))" with "(?P<value>(.+))" within (?P<seconds>(\d+)) seconds?$/
     *
     * @throws \Exception
     */
    public function fillFieldWithinSpecifiedTime($field, $value, $seconds)
    {
        $fillField = function($context) use ($field, $value) {
            try {
                $context->fillField($field, $value);
                return true;
            }
            catch (\Exception $e) {
                // Do nothing, try again
            }
            return false;
        };
        $this->spin($fillField, $seconds);
    }


    /**
     * Runs a given lambda method again and again until it returns "true".
     * If the given timeout exceeds, an Exception is thrown.
     *
     * @param callable $lambda The method to be executed
     * @param int $timeout The maximum amount of seconds to be waited. Defaults to 5 seconds
     * @throws \Exception
     * @see https://stackoverflow.com/a/29608256
     */
    public function spin($lambda, $timeout = 5)
    {
        $time = time();
        $stopTime = $time + $timeout;
        while (time() < $stopTime)
        {
            try {
                if ($lambda($this)) {
                    return;
                }
            } catch (\Exception $e) {
                // do nothing
            }

            usleep(250000);
        }

        throw new \Exception("Spin function timed out after {$timeout} seconds");
    }
}
