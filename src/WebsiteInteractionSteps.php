<?php

namespace Exozet\Behat\Utils\Base;

use \Behat\Gherkin\Node\PyStringNode;

trait WebsiteInteractionSteps {

    /**
     * Scrolls the element matching the given selector into view
     * Example: Given I scroll to ".content"
     *
     * @Given /^ich scrolle zu "(?P<selector>[^"]+)"$/
     * @Given /^I scroll to "(?P<selector>[^"]+)"$/
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
     * @When /^ich (?P<seconds>\d+) Sekunden? warte$/
     * @When /^I wait (?P<seconds>\d+) seconds?$/
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
     * @Then /^sehe ich auf "(?P<selector>[^"]+)" passende Elemente innerhalb von (?P<seconds>\d+) Sekunden?$/
     * @Then /^I see elements matching "(?P<selector>[^"]+)" within (?P<seconds>\d+) seconds?$/
     */
    public function waitForMatchingElementsWithinSpecifiedTime($selector, $seconds)
    {
        $this->getSession()->wait(
            $seconds * 1000,
            'document.querySelectorAll(' . json_encode($selector) . ').length'
        );
    }

    /**
     * Clicks the element matching the given selector
     * Example: Then I click on "button.reset"
     *
     * @Then /^klicke ich auf "(?P<selector>[^"]+)"$/
     * @Then /^I click on "(?P<selector>[^"]+)"$/
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
     * @Then /^existieren unter dem Pfad "(?P<selector>[^"]+)" (?P<count>\d+) Elemente mit den folgenden Werten:$/
     * @Then /^at path "(?P<selector>[^"]+)" there exist (?P<count>\d+) elements with the following values:$/
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

        $messages = [];
        foreach ($domElements as $wrappingDomElementKey => $domElement) {
            $wrappingDomElementKey++; #die qa zählt von 1 nicht von 0
            #var_dump('---- dom number '.$wrappingDomElementKey.' element ----');
            $matchedElements = 0;
            foreach ($elementsToFind as $elementToFind => $expectedRegex) {

                #var_dump($elementToFind);
                #var_dump($expectedRegex);
                {
                    #var_dump('find element: ' . $elementToFind);
                    $subDomElement = $domElement->find('css', $elementToFind);
                    if ($subDomElement) {
                        #var_dump('found sub element: ' . $elementToFind);
                        $text = $subDomElement->getText();
                        $matches = [];
                        preg_match($expectedRegex, $text, $matches);
                        #var_dump('search regex ' . $expectedRegex . ' in ' . $text);
                        if ($matches) {
                            #var_dump('found: ' . $expectedRegex);
                            $matchedElements++;
                            #var_dump($matches);
                        } else {
                            $messages[] = 'expected ' . $expectedRegex . ' not found in ' . $elementToFind;
                        }
                    } else {
                        #var_dump($elementToFind. ' not found');
                    }
                }

                if ($matchedElements == count($elementsToFind)) {
                    #var_dump('set validCombinations true');
                    $validCombinations++;
                    break;
                } else {
                    #throw new Exception("unable to find elements: ".implode("\n",$messages).' in wrapping element number '.$wrappingDomElementKey);
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
     * @Given /^ich starte die Sitzung neu$/
     * @Given /^I restart the session$/
     */
    public function restartSession()
    {
        $this->getSession()->restart();
    }

    /**
     * Resets the Mink session
     * Example: Given I reset the session
     *
     * @Given /^ich setze die Sitzung zurück$/
     * @Given /^I reset the session$/
     */
    public function resetSession()
    {
        $this->getSession()->reset();
    }

    #TODO Diese Methode muss noch zerteilt werden. Den Oparator las Variable raus und direkt in den Aufruf dann gießen
    /**
     * Checks, that at the given DOM path there exist the given count of elements, optionally compared using a given
     * operator ("min" or "max)
     * Example: Then there exist max 3 elements at path ".unread-messages"
     *
     * @Then /^existieren unter dem Pfad "(?P<selector>[^"]+)" (?P<operator>("min" |"max" |))(?P<count>\d+) Elemente$/
     * @Then /^there exist (?P<operator>(min |max |))(?P<count>\d+) elements at path "(?P<selector>[^"]+)"$/
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
     * @Then /^klicke ich auf "(?P<clickSelector>[^"]+)", falls das Element "(?P<selector>[^"]+)" existiert$/
     * @Then /^I click on "(?P<clickSelector>[^"]+)" if element "(?P<selector>[^"]+)" exists$/
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
     * @When /^ich den Fokus vom Element "(?P<selector>[^"]+)" entferne$/
     * @When /^I remove focus from element "(?P<selector>[^"]+)"$/
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
