<?php

namespace Exozet\Behat\Utils\Base;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;

trait WebsiteInteractionSteps {

    /**
     * Returns the default timeout in seconds used by all steps accepting a timeout.
     * You may override the default timeout in classes using this trait by using:
     *   protected $defaultTimeout = 10;  // Set the default timeout to 10 seconds
     *
     * @return int the default timeout in seconds
     */
    public function getDefaultTimeoutWebsiteInteraction()
    {
        return isset($this->defaultTimeout) ? $this->defaultTimeout : 5;
    }

    /**
     * Returns the default widths in pixels used when resizing the viewport.
     * You may override the default responsive widths in classes using this trait by using:
     *   protected $defaultResponsiveViewportWidths = array(
     *        "Desktop" => 1365,
     *        "Tablet"  => 1023,
     *        "Phone"   => 640
     *   );
     *
     * @param string $deviceType one of "Desktop", "Tablet" or "Phone"
     * @return array a mapping of the viewport breakpoints "Desktop", "Tablet" and "Phone" to their corresponding widths
     */
    public function getDefaultResponsiveViewportWidths($deviceType)
    {
        $widthMapping = isset($this->defaultResponsiveViewportWidths) ? $this->defaultResponsiveViewportWidths : array(
            "Desktop" => 1823,
            "Tablet"  => 1023,
            "Phone"   => 767
        );
        return $widthMapping[$deviceType];
    }

    /**
     * Scroll the element matching the given selector into view, trying to align the element to the top of the viewport
     * If $alignToTop is set to false, the step tries to align the element to the bottom of the viewport.
     * Example: Given I scroll to ".content"
     *
     * @param bool $alignToTop align the element to the viewport's top when "true" (default). Otherwise, align to bottom
     *
     * @Given /^I scroll to "(?P<selector>[^"]+)"$/
     * @Given /^ich scrolle zu "(?P<selector>[^"]+)"$/
     */
    public function scrollIntoView($selector, $alignToTop = true)
    {
        $this->getSession()->executeScript(
            'document.querySelectorAll(' . json_encode($selector) . ')[0].scrollIntoView(' . ($alignToTop) ? "true" : "false" . ')'
        );
    }

    /**
     * @see scrollIntoView
     *
     * @Given /^I scroll to have "(?P<selector>[^"]+)" at the bottom of the viewport$/
     * @Given /^ich scrolle, um "(?P<selector>[^"]+)" am unteren Ende des Viewports zu haben$/
     */
    public function scrolIntoViewAlignToBottom($selector)
    {
        $this->scrollIntoView($selector, false);
    }

    /**
     * Waits synchronously for the given amount of seconds
     * Example: When I wait 3 seconds
     *
     * @When /^I wait (?P<seconds>\d+) seconds?$/
     * @When /^ich (?P<seconds>\d+) Sekunden? warte$/
     */
    public function waitForSpecifiedTime($seconds)
    {
        $this->getSession()->wait(
            $seconds * 1000
        );
    }

    /**
     * Waits synchronously for the default timeout
     * Example: When I wait
     *
     * @When /^I wait$/
     * @When /^ich warte$/
     */
    public function waitForDefaultTimeout()
    {
        $this->waitForSpecifiedTime($this->getDefaultTimeoutWebsiteInteraction());
    }

    /**
     * Waits asynchronously (for a given time) until elements matching the given selector are existing
     * Example: Then I see elements matching ".content" within 3 seconds
     *
     * @Then /^I see elements matching "(?P<selector>[^"]+)" within (?P<seconds>\d+) seconds?$/
     * @Then /^sehe ich auf "(?P<selector>[^"]+)" passende Elemente innerhalb von (?P<seconds>\d+) Sekunden?$/
     * @throws ExpectationException
     */
    public function waitForMatchingElementsWithinSpecifiedTime($selector, $seconds)
    {
        $didFindElements = $this->getSession()->wait(
            $seconds * 1000,
            'document.querySelectorAll(' . json_encode($selector) . ').length'
        );
        if ($didFindElements === false) {
            throw new ExpectationException(
                'Element(s) matching "' . $selector . '" could not be found within ' . $seconds . ' seconds',
                $this->getSession()->getDriver()
            );
        }
    }

    /**
     * Waits asynchronously (for the default timeout time) until elements matching the given selector are existing
     * Example: Then I see elements matching ".content" in time
     *
     * @Then /^I see elements matching "(?P<selector>[^"]+)" in time$/
     * @Then /^sehe ich kurz darauf auf "(?P<selector>[^"]+)" passende Elemente$/
     * @throws ExpectationException
     */
    public function waitForMatchingElementsWithinDefaultTimeout($selector)
    {
        $this->waitForMatchingElementsWithinSpecifiedTime($selector, $this->getDefaultTimeoutWebsiteInteraction());
    }

    /**
     * Waits asynchronously (for a given time) until some elements matching the given selector are visible and inside
     * the viewport
     * Example: Then I see visible elements matching ".content" within 3 seconds
     *
     * @Then /^I see visible elements matching "(?P<selector>[^"]+)" within (?P<seconds>\d+) seconds?$/
     * @Then /^sehe ich auf "(?P<selector>[^"]+)" passende sichtbare Elemente innerhalb von (?P<seconds>\d+) Sekunden?$/
     * @throws ExpectationException
     *
     * TODO Could be optimized by using Intersection Observer API:
     * TODO https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API
     */
    public function waitForVisibleMatchingElementsWithinSpecifiedTime($selector, $seconds)
    {
        $jsonSelector = json_encode($selector);
        $didFindElements = $this->getSession()->wait(
            $seconds * 1000,
            <<<JS
Array.from(document.querySelectorAll( {$jsonSelector} ))
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

        if ($didFindElements === false) {
            throw new ExpectationException(
                'Visisble element(s) matching "' . $selector . '" could not be found within ' . $seconds . ' seconds',
                $this->getSession()->getDriver()
            );
        }
    }

    /**
     * Waits asynchronously (for the default timeout time) until some elements matching the given selector are visible
     * and inside the viewport
     * Example: Then I see visible elements matching ".content" in time
     *
     * @Then /^I see visible elements matching "(?P<selector>[^"]+)" in time$/
     * @Then /^sehe ich kurz darauf auf "(?P<selector>[^"]+)" passende sichtbare Elemente$/
     * @throws ExpectationException
     */
    public function waitForVisibleMatchingElementsWithinDefaultTimeout($selector)
    {
        $this->waitForVisibleMatchingElementsWithinSpecifiedTime($selector, $this->getDefaultTimeoutWebsiteInteraction());
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

    /**
     * Opens specified page in default time
     * Example: Given I am on "http://batman.com" in time
     *
     * @Given /^I am on "(?P<page>[^"]+)" in time/
     * @Given /^Ich öffne die Seite "(?P<page>[^"]+)" innerhalb der Standardzeit$/
     */
    public function openUrl($page)
    {
        $this->visit($page);
        $this->assertPageAddressWithinDefaultTimeout($page);
    }

    /**
     * Opens specified page in specified time
     * Example: Given I am on "http://batman.com" within 5 seconds
     *
     * @Given /^I am on "(?P<page>[^"]+)" within (?P<seconds>(\d+)) seconds?$/
     * @Given /^Ich öffne die Seite "(?P<page>[^"]+)" innerhalb von (?P<seconds>(\d+)) Sekunden?$/
     */
    public function openUrlWithinSpecifiedTime($page, $seconds)
    {
        $this->visit($page);
        $this->assertPageAddressWithinSpecifiedTime($page, $seconds);
    }

    /**
     * Switch from the currently active iFrame to the one indicated by the given name
     * Example: When I switch to the iFrame "#checkout"
     *
     * @When /^I switch to the iFrame "(?P<name>[^"]+)"$/
     * @When /^ich zum iFrame "(?P<name>[^"]+)" wechsle$/
     */
    public function activateIFrame($name)
    {
        $this->getSession()->getDriver()->switchToIFrame($name);
    }

    /**
     * Resize the driver's window to the width of a given device type
     * Example: When I resize the window to the responsive viewport for Desktop
     *
     * @When /^I resize the window to the responsive viewport for (Desktop|Tablet|Mobile)$/
     * @When /^ich das Browser-Fenster auf die responsive Viewport-Größe (Desktop|Tablet|Mobile) skaliere$/
     */
    public function resizeWindowToResponsiveViewport($deviceType)
    {
        $this->assertContains($deviceType, ['Desktop', 'Tablet', 'Mobile'],
            'The device type has to be one of "Desktop", "Tablet" and "Mobile".');
        $width = $this->getDefaultResponsiveViewportWidths($deviceType);
        $this->getSession()->getDriver()->resizeWindow($width, 900, $deviceType);
    }

    /**
     * Hover over a given element, i. e. toggles the corresponding mouse-over event
     * Example: When I hover over the element "button.send"
     *
     * @When /^I hover over the element "(?P<selector>[^"]+)"$/
     * @When /^ich über das Element "(?P<selector>[^"]+)" hovere$/
     * @throws ElementNotFoundException
     */
    public function mouseOver($selector)
    {
        $domElement = $this->getSession()->getPage()->find("css", $selector);

        if (!$domElement) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), null, $selector);
        } else {
            $domElement->mouseOver();
        }
    }
}
