<?php

namespace Exozet\Behat\Utils\Base;

use \Behat\Gherkin\Node\PyStringNode;
use Behat\Mink\Exception\ExpectationException;

trait WebsiteInteractionSteps {

    /**
     * Scrolls the element matching the given selector into view
     * Example: Given I scroll to ".content"
     *
     * @Given /^I scroll to "(?P<selector>[^"]+)"$/
     * @Given /^ich scrolle zu "(?P<selector>[^"]+)"$/
     */
    public function scrollIntoView($selector)
    {
        $this->getSession()->executeScript(
            'document.querySelectorAll(' . json_encode($selector) . ')[0].scrollIntoView()'
        );
    }

    /**
     * Waits synchronously for the given amount of seconds
     * Example: When I wait 3 seconds
     *
     * @When /^I wait (?P<seconds>\d+) seconds?$/
     * @When /^ich (?P<seconds>\d+) Sekunden? warte$/
     */
    public function wait($seconds)
    {
        $this->getSession()->wait(
            $seconds * 1000
        );
    }

    /**
     * Waits asynchronously until either elements matching the given selector are existing
     * or a given amount of seconds has passed
     * Example: Then I see elements matching ".content" within 3 seconds
     *
     * @Then /^I see elements matching "(?P<selector>[^"]+)" within (?P<seconds>\d+) seconds?$/
     * @Then /^sehe ich auf "(?P<selector>[^"]+)" passende Elemente innerhalb von (?P<seconds>\d+) Sekunden?$/
     */
    public function waitForMatchingElementsWithinSpecifiedTime($selector, $seconds)
    {
        $this->getSession()->wait(
            $seconds * 1000,
            'document.querySelectorAll(' . json_encode($selector) . ').length'
        );
    }

    /**
     * Waits asynchronously until either some elements matching the given selector are visible and inside the viewport
     * or a given amount of seconds has passed
     * Example: Then I see visible elements matching ".content" within 3 seconds
     *
     * @Then /^I see visible elements matching "(?P<selector>[^"]+)" within (?P<seconds>\d+) seconds?$/
     * @Then /^sehe ich auf "(?P<selector>[^"]+)" passende sichtbare Elemente innerhalb von (?P<seconds>\d+) Sekunden?$/
     *
     * TODO Could be optimized by using Intersection Observer API:
     * TODO https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API
     */
    public function waitForVisibleMatchingElementsWithinSpecifiedTime($selector, $seconds)
    {
        $selector = json_encode($selector);
        $this->getSession()->wait(
            $seconds * 1000,
            <<<JS
Array.from(document.querySelectorAll( {$selector} ))
    .some(function(element) {
        
        // See https://stackoverflow.com/a/7557433
        function isElementInViewport (el) {
            //special bonus for those using jQuery
            if (typeof jQuery !== 'undefined' && el instanceof jQuery) el = el[0];
        
            const rect = el.getBoundingClientRect();
            const windowHeight = (window.innerHeight || document.documentElement.clientHeight);
            const windowWidth = (window.innerWidth || document.documentElement.clientWidth);
        
            return (
                   (rect.left >= 0)
                && (rect.top >= 0)
                && ((rect.left + rect.width) <= windowWidth)
                && ((rect.top + rect.height) <= windowHeight)
            );
        }
        
        // See https://stackoverflow.com/a/21696585
        function isElementVisible (el) {
            return (el.offsetParent !== null);
        }
        
        return isElementInViewport(element) && isElementVisible(element);
    });
JS
        );
    }

    /**
     * Clicks the element matching the given selector
     * Example: Then I click on "button.reset"
     *
     * @Then /^I click on "(?P<selector>[^"]+)"$/
     * @Then /^klicke ich auf "(?P<selector>[^"]+)"$/
     * @throws ExpectationException
     */
    public function clickOn($selector)
    {
        $findName = $this->getSession()->getPage()->find("css", $selector);
        if (!$findName) {
            throw new ExpectationException($selector . " could not be found", $this->getSession()->getDriver());
        } else {
            $findName->press();
        }
    }

    /**
     * Checks, that at the given DOM path there exists exactly the given count of elements matching the given JSON data
     * (the JSON is expected to be of the format "<CSS element selector>": "<RegEx matching the element's content>")
     * Example: Then at path ".content" there exist 3 elements with the following values:
     * """
     * {
     *   "h3": "Section \d"
     * }
     * """
     *
     * @Then /^at path "(?P<selector>[^"]+)" there exist (?P<count>\d+) elements with the following values:$/
     * @Then /^existieren unter dem Pfad "(?P<selector>[^"]+)" (?P<count>\d+) Elemente mit den folgenden Werten:$/
     * @throws ExpectationException
     */
    public function findMultipleTextInDomElements($selector, $count, PyStringNode $elementsToFind)
    {
        $elementsToFind = json_decode($elementsToFind->getRaw(),true);
        $validCombinations = 0;

        /** @var \Behat\Mink\Element\NodeElement $domElements */
        $domElements = $this->getSession()->getPage()->findAll("css", $selector);
        if (!$domElements) {
            throw new ExpectationException($selector . " could not be found", $this->getSession()->getDriver());
        }

        foreach ($domElements as $wrappingDomElementKey => $domElement) {
            $wrappingDomElementKey++; # Start counting with 1 instead of 0 for better readability towards non-techies
            $matchedElements = 0;
            foreach ($elementsToFind as $elementToFind => $expectedRegex) {
                {
                    $subDomElement = $domElement->find('css', $elementToFind);
                    if ($subDomElement) {
                        $text = $subDomElement->getText();
                        $matches = [];
                        preg_match($expectedRegex, $text, $matches);
                        if ($matches) {
                            $matchedElements++;
                        }
                    }
                }

                if ($matchedElements == count($elementsToFind)) {
                    $validCombinations++;
                    break;
                }
            }
        }

        if ($count != $validCombinations) {
            throw new ExpectationException(
                'expected count ' . $count . ' combination not found: ' . print_r($elementsToFind, true) .
                'The actual count is: ' . $validCombinations,
                $this->getSession()->getDriver()
            );
        }
    }

    /**
     * Restarts the Mink session
     * Example: Given I restart the session
     *
     * @Given /^I restart the session$/
     * @Given /^ich starte die Sitzung neu$/
     */
    public function restartSession()
    {
        $this->getSession()->restart();
    }

    /**
     * Resets the Mink session
     * Example: Given I reset the session
     *
     * @Given /^I reset the session$/
     * @Given /^ich setze die Sitzung zurück$/
     */
    public function resetSession()
    {
        $this->getSession()->reset();
    }

    # TODO Find a better way of integrating the operator into the expected count, e. g. allowing "exist >= 3 elements"
    /**
     * Checks, that at the given DOM path there exist the given count of elements, optionally compared using a given
     * operator ("min" or "max)
     * Example: Then there exist max 3 elements at path ".unread-messages"
     *
     * @Then /^there exist (?P<operator>(min |max |))(?P<count>\d+) elements at path "(?P<selector>[^"]+)"$/
     * @Then /^existieren unter dem Pfad "(?P<selector>[^"]+)" (?P<operator>("min" |"max" |))(?P<count>\d+) Elemente$/
     * @throws ExpectationException
     */
    public function checkCountOfElements($selector, $operator, $count)
    {
        $domElements = $this->getSession()->getPage()->findAll("css", $selector);

        if (!$domElements) {
            throw new ExpectationException($selector . " could not be found", $this->getSession()->getDriver());
        }

        if (strcmp($operator, "min")) {
            if (count($domElements) >= $count) {

            } else {
                throw new ExpectationException(
                    'expected at least ' . $count . ' elements matching ' . $selector . '. Actual count: ' . count($domElements),
                    $this->getSession()->getDriver()
                );
            }
        } elseif (strcmp($operator, "max")) {
            if (count($domElements) <= $count) {

            } else {
                throw new ExpectationException(
                    'expected at most ' . $count . ' elements matching ' . $selector . '. Actual count: ' . count($domElements),
                    $this->getSession()->getDriver()
                );
            }
        } else {
            if (count($domElements) == $count) {

            } else {
                throw new ExpectationException(
                    'expected exactly ' . $count . ' elements matching ' . $selector . '. Actual count: ' . count($domElements),
                    $this->getSession()->getDriver()
                );
            }
        }
    }

    /**
     * If a given element exists, clicks the element matching the given selector. Otherwise, does nothing (succeeds)
     * Example: Then I click on ".modal button.close", if element ".modal" exists
     *
     * @Then /^I click on "(?P<clickSelector>[^"]+)" if element "(?P<selector>[^"]+)" exists$/
     * @Then /^klicke ich auf "(?P<clickSelector>[^"]+)", falls das Element "(?P<selector>[^"]+)" existiert$/
     * @throws ExpectationException
     */
    public function clickOnIfGivenElementExists($selector, $clickSelector)
    {
        $domElement = $this->getSession()->getPage()->find("css", $selector);

        if (!$domElement) {
            # Do nothing if the element was not found
        } else {
            $this->clickOn($clickSelector);
        }
    }

    /**
     * Removes the focus from the element matching the given DOM selector
     * Example: When I remove focus from element "input.password"
     *
     * @When /^I remove focus from element "(?P<selector>[^"]+)"$/
     * @When /^ich den Fokus vom Element "(?P<selector>[^"]+)" entferne$/
     * @throws ExpectationException
     */
    public function removeFocusFromElement($selector)
    {
        $domElement = $this->getSession()->getPage()->find("css", $selector);

        if (!$domElement) {
            throw new ExpectationException($selector . " could not be found", $this->getSession()->getDriver());
        } else {
            $domElement->blur();
        }
    }

}
