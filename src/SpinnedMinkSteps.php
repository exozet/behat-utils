<?php

namespace Exozet\Behat\Utils\Base;

trait SpinnedMinkSteps {
    /**
     * @see MinkContext::assertPageAddress
     *
     * @Then /^bin ich auf "(?P<page>[^"]+)" innerhalb von (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) Sekunden?$/
     * @Then /^I should be on "(?P<page>[^"]+)" within (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) seconds?$/
     */
    public function assertPageAddressWithinSpecifiedTime($page, $seconds)
    {
        $seconds = (float) $seconds;
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
     * @Then /^sehe ich "(?P<text>[^"]+)" innerhalb von (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) Sekunden?$/
     * @Then /^I should see "(?P<text>[^"]+)" within (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) seconds?$/
     */
    public function assertPageContainsTextWithinSpecifiedTime($text, $seconds)
    {
        $seconds = (float) $seconds;

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
     * @Then /^sehe ich "(?P<text>[^"]+)" nicht innerhalb von (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) Sekunden?$/
     * @Then /^I should not see "(?P<text>[^"]+)" within (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) seconds?$/
     */
    public function assertPageNotContainsTextWithinSpecifiedTime($text, $seconds)
    {
        $seconds = (float) $seconds;

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
     * Checks, that element with specified CSS contains specified text
     * Example: Then I should see "Batman" in the "heroes_list" element
     * Example: And I should see "Batman" in the "heroes_list" element
     *
     * @Then /^sehe ich "(?P<text>[^"]+)" im Element "(?P<element>[^"]+)" innerhalb von (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) Sekunden?$/
     * @Then /^I should see "(?P<text>[^"]+)" in the "(?P<element>[^"]+)" element within (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) seconds?$/
     */
    public function assertElementContainsTextWithinSpecifiedTime($element, $text, $seconds)
    {
        $seconds = (float) $seconds;

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
     * @see MinkContext::assertElementNotOnPage
     *
     * @Then /^sehe ich kein "(?P<element>[^"]+)" Element innerhalb von (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) Sekunden?$/
     * @Then /^I should not see an? "(?P<element>[^"]+)" element within (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) seconds?$/
     */
    public function assertElementNotOnPageWithinSpecifiedTime($element, $seconds)
    {
        $seconds = (float) $seconds;

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
     * @When /^ich "(?P<field>[^"]+)" mit "(?P<value>[^"]+)" innerhalb von (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) Sekunden? ausfülle$/
     * @When /^I fill in "(?P<field>[^"]+)" with "(?P<value>[^"]+)" within (?P<seconds>[0-9]+([.][0-9]*)?|[.][0-9]+) seconds?$/
     */
    public function fillFieldWithinSpecifiedTime($field, $value, $seconds)
    {
        $seconds = (float) $seconds;

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
