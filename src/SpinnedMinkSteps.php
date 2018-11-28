<?php

namespace Exozet\Behat\Utils\Base;

use Behat\Mink\Exception\ExpectationException;

trait SpinnedMinkSteps {

    /**
     * Returns the default timeout in seconds used by all steps accepting a timeout.
     * You may override the default timeout in classes using this trait by using:
     *   protected $defaultTimeout = 10;  // Set the default timeout to 10 seconds
     *
     * @return int the default timeout in seconds
     */
    public function getDefaultTimeoutSpinnedMink()
    {
        return isset($this->defaultTimeout) ? $this->defaultTimeout : 15;
    }

    /**
     * @see MinkContext::assertPageAddress
     *
     * @Then /^I should be on "(?P<page>[^"]+)" within (?P<seconds>(\d+)) seconds?$/
     * @Then /^bin ich auf "(?P<page>[^"]+)" innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     *
     * @throws \Exception
     */
    public function assertPageAddressWithinSpecifiedTime($page, $seconds)
    {
        $assertPageAddress = function($context) use ($page) {
            $context->assertPageAddress($page);
            return true;
        };
        $this->spin($assertPageAddress, $seconds);
    }

    /**
     * @see MinkContext::assertPageAddress
     *
     * @Then /^I should be on "(?P<page>[^"]+)" in time?$/
     * @Then /^bin ich kurz darauf auf "(?P<page>[^"]+)"$/
     *
     * @throws \Exception
     */
    public function assertPageAddressWithinDefaultTimeout($page)
    {
        $this->assertPageAddressWithinSpecifiedTime($page, $this->getDefaultTimeoutSpinnedMink());
    }

    /**
     * @see MinkContext::assertPageContainsText
     *
     * @Then /^I should see "(?P<text>(.+))" within (?P<seconds>(\d+)) seconds?$/
     * @Then /^sehe ich "(?P<text>(.+))" innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     *
     * @throws \Exception
     */
    public function assertPageContainsTextWithinSpecifiedTime($text, $seconds)
    {
        $assertPageContainsText = function($context) use ($text) {
            $context->assertPageContainsText($text);
            return true;
        };
        $this->spin($assertPageContainsText, $seconds);
    }

    /**
     * @see MinkContext::assertPageContainsText
     *
     * @Then /^I should see "(?P<text>(.+))" in time$/
     * @Then /^sehe ich kurz darauf "(?P<text>(.+))"$/
     *
     * @throws \Exception
     */
    public function assertPageContainsTextWithinDefaultTimeout($text)
    {
        $this->assertPageContainsTextWithinSpecifiedTime($text, $this->getDefaultTimeoutSpinnedMink());
    }

    /**
     * @see MinkContext::assertPageNotContainsText
     *
     * @Then /^I should not see "(?P<text>(.+))" within (?P<seconds>(\d+)) seconds?$/
     * @Then /^sehe ich "(?P<text>(.+))" nicht innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     *
     * @throws \Exception
     */
    public function assertPageNotContainsTextWithinSpecifiedTime($text, $seconds)
    {
        $assertPageNotContainsText = function($context) use ($text) {
            $context->assertPageNotContainsText($text);
            return true;
        };
        $this->spin($assertPageNotContainsText, $seconds);
    }

    /**
     * @see MinkContext::assertPageNotContainsText
     *
     * @Then /^I should not see "(?P<text>(.+))" in time$/
     * @Then /^sehe ich "(?P<text>(.+))" nicht kurz darauf$/
     *
     * @throws \Exception
     */
    public function assertPageNotContainsTextWithinDefaultTimeout($text)
    {
        $this->assertPageNotContainsTextWithinSpecifiedTime($text, $this->getDefaultTimeoutSpinnedMink());
    }

    /**
     * @see MinkContext::assertElementContainsText
     *
     * @Then /^I should see "(?P<text>(.+))" in the "(?P<element>[^"]+)" element within (?P<seconds>(\d+)) seconds?$/
     * @Then /^sehe ich "(?P<text>(.+))" im "(?P<element>[^"]+)"-Element innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     *
     * @throws \Exception
     */
    public function assertElementContainsTextWithinSpecifiedTime($element, $text, $seconds)
    {
        $assertElementContainsText = function($context) use ($element, $text) {
            $context->assertElementContainsText($element, $text);
            return true;
        };
        $this->spin($assertElementContainsText, $seconds);
    }

    /**
     * @see MinkContext::assertElementNotContainsText
     *
     * @Then /^I should not see "(?P<text>(.+))" in the "(?P<element>[^"]+)" element within (?P<seconds>(\d+)) seconds?$/
     * @Then /^sehe ich kein "(?P<text>(.+))" im "(?P<element>[^"]+)"-Element innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     *
     * @throws \Exception
     */
    public function assertElementNotContainsTextWithinSpecifiedTime($element, $text, $seconds)
    {
        $assertElementNotContainsText = function($context) use ($element, $text) {
            $context->assertElementNotContainsText($element, $text);
            return true;
        };
        $this->spin($assertElementNotContainsText, $seconds);
    }

    /**
     * @see MinkContext::assertElementContainsText
     *
     * @Then /^I should see "(?P<text>(.+))" in the "(?P<element>[^"]+)" element in time$/
     * @Then /^sehe ich kurz darauf "(?P<text>(.+))" im "(?P<element>[^"]+)"-Element$/
     *
     * @throws \Exception
     */
    public function assertElementContainsTextWithinDefaultTimeout($element, $text)
    {
        $this->assertElementContainsTextWithinSpecifiedTime($element, $text, $this->getDefaultTimeoutSpinnedMink());
    }

    /**
     * @see MinkContext::assertElementNotContainsText
     *
     * @Then /^I should not see "(?P<text>(.+))" in the "(?P<element>[^"]+)" element in time$/
     * @Then /^sehe ich kurz darauf kein "(?P<text>(.+))" im "(?P<element>[^"]+)"-Element$/
     *
     * @throws \Exception
     */
    public function assertElementNotContainsTextWithinDefaultTimeout($element, $text)
    {
        $this->assertElementNotContainsTextWithinSpecifiedTime($element, $text, $this->getDefaultTimeoutSpinnedMink());
    }

    /**
     * @see MinkContext::assertElementOnPage
     *
     * @Then /^I should see an? "(?P<element>[^"]+)" element within (?P<seconds>(\d+)) seconds?$/
     * @Then /^sehe ich ein "(?P<element>[^"]+)"-Element innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     *
     * @throws \Exception
     */
    public function assertElementOnPageWithinSpecifiedTime($element, $seconds)
    {
        $assertElementOnPage = function($context) use ($element) {
            $context->assertElementOnPage($element);
            return true;
        };
        $this->spin($assertElementOnPage, $seconds);
    }

    /**
     * @see MinkContext::assertElementOnPage
     *
     * @Then /^I should see an? "(?P<element>[^"]+)" element in time$/
     * @Then /^sehe ich kurz darauf ein "(?P<element>[^"]+)"-Element$/
     *
     * @throws \Exception
     */
    public function assertElementOnPageWithinDefaultTimeout($element)
    {
        $this->assertElementOnPageWithinSpecifiedTime($element, $this->getDefaultTimeoutSpinnedMink());
    }

    /**
     * @see MinkContext::assertElementNotOnPage
     *
     * @Then /^I should not see an? "(?P<element>[^"]+)" element within (?P<seconds>(\d+)) seconds?$/
     * @Then /^sehe ich kein "(?P<element>[^"]+)"-Element innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     *
     * @throws \Exception
     */
    public function assertElementNotOnPageWithinSpecifiedTime($element, $seconds)
    {
        $assertElementNotOnPage = function($context) use ($element) {
            $context->assertElementNotOnPage($element);
            return true;
        };
        $this->spin($assertElementNotOnPage, $seconds);
    }

    /**
     * @see MinkContext::assertElementNotOnPage
     *
     * @Then /^I should not see an? "(?P<element>[^"]+)" element in time$/
     * @Then /^sehe ich kurz darauf kein "(?P<element>[^"]+)"-Element$/
     *
     * @throws \Exception
     */
    public function assertElementNotOnPageWithinDefaultTimeout($element)
    {
        $this->assertElementNotOnPageWithinSpecifiedTime($element, $this->getDefaultTimeoutSpinnedMink());
    }

    /**
     * @see MinkContext::fillField
     *
     * @When /^I fill in "(?P<field>(.+))" with "(?P<value>(.+))" within (?P<seconds>(\d+)) seconds?$/
     * @When /^ich "(?P<field>(.+))" mit "(?P<value>(.+))" innerhalb von (?P<seconds>(\d+)) Sekunden? ausfülle$/
     *
     * @throws \Exception
     */
    public function fillFieldWithinSpecifiedTime($field, $value, $seconds)
    {
        $fillField = function($context) use ($field, $value) {
            $context->fillField($field, $value);
            return true;
        };
        $this->spin($fillField, $seconds);
    }

    /**
     * @see MinkContext::fillField
     *
     * @When /^I fill in "(?P<field>(.+))" with "(?P<value>(.+))" in time$/
     * @When /^ich kurz darauf "(?P<field>(.+))" mit "(?P<value>(.+))" ausfülle$/
     *
     * @throws \Exception
     */
    public function fillFieldWithinDefaultTimeout($field, $value)
    {
        $this->fillFieldWithinSpecifiedTime($field, $value, $this->getDefaultTimeoutSpinnedMink());
    }

    /**
     * Fills in form field with specified id|name|label|value within the default timeout, using JavaScript
     * @see MinkContext::fillField
     *
     * @When /^I fill in "(?P<field>(.+))" with "(?P<value>(.+))" in time using JavaScript$/
     * @When /^ich kurz darauf "(?P<field>(.+))" mit "(?P<value>(.+))" mittels JavaScript ausfülle$/
     * @Throws ExpectationException
     */
    public function fillFieldWithinDefaultTimeoutUsingJavaScript($field, $value)
    {
        $this->waitForMatchingElementsWithinDefaultTimeout($field);
        $this->getSession()->evaluateScript("document.querySelectorAll('" . $field . "')[0].value='" . $value . "'");
    }

    /**
     * Repeatedly executes a given lambda method again and again until it throws no exception anymore.
     * If the given timeout exceeds, the last thrown exception (or, if not existing, a default Exception) is thrown.
     *
     * @param callable $lambda The method to be executed
     * @param int $timeout The maximum amount of seconds to be waited. Defaults to 5 seconds
     * @throws \Exception
     * @see https://stackoverflow.com/a/29608256
     */
    public function spin($lambda, $timeout)
    {
        if(!isset($timeout)) {
            $timeout = $this->getDefaultTimeoutSpinnedMink();
        }
        $time = time();
        $stopTime = $time + $timeout;
        $lastException = null;
        while (time() < $stopTime)
        {
            try {
                if ($lambda($this)) {
                    return;
                }
            } catch (\Exception $e) {
                $lastException = $e;
            }

            usleep(250000);
        }

        if (isset($lastException)) {
            throw new \Exception("Spin function timed out after {$timeout} seconds with: " . $lastException);
        } else {
            throw new \Exception("Spin function timed out after {$timeout} seconds");
        }
    }
}
