<?php

namespace Exozet\Behat\Utils\Base;

use \Behat\Gherkin\Node\PyStringNode;

trait WebsiteInteractionSteps {

    /**
     * @Given /^Ich scrolle zu "(?P<page>[^"]+)"$/
     */
    public function ifIScrollToSelector($selector)
    {
        $this->getSession()->executeScript('document.querySelectorAll(' . json_encode($selector) . ')[0].scrollIntoView()');
    }

    /**
     * @Then I wait :seconds Seconds
     */
    public function iWait($seconds)
    {
        $this->getSession()->wait(
            $seconds * 1000
        );
    }

    /**
     * @Then Ich sehe matchende Elemente auf :selector innerhalb von :seconds Sekunden
     */
    public function ichSeheMatchendeElementeAufInnerhalbVonSekunden($selector, $seconds)
    {
        $this->getSession()->wait(
            $seconds * 1000,
            'document.querySelectorAll(' . json_encode($selector) . ').length'
        );
    }

    /**
     * @Then /^(?:|I )click (?:on |)(?:|the )"([^"]*)"(?:|.*)$/
     */
    public function iClickOn($arg1)
    {
        $findName = $this->getSession()->getPage()->find("css", $arg1);
        if (!$findName) {
            throw new Exception($arg1 . " could not be found");
        } else {
            $findName->press();
        }
    }


    /**
     * @Then /^Unter dem Pfad "([^"]*)" existieren Anzahl "([^"]*)" Objekte mit den folgenden Werten:$/
     */
    public function findMultipleTextInDomElements($domSelector, $expectedValidCombination, PyStringNode $elementsToFind)
    {
        $elementsToFind = json_decode($elementsToFind->getRaw(),true);
        $validCombinations = 0;

        /** @var \Behat\Mink\Element\NodeElement $domElements */
        $domElements = $this->getSession()->getPage()->findAll("css", $domSelector);
        if (!$domElements) {
            throw new Exception($domSelector . " could not be found");
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

        if ($expectedValidCombination != $validCombinations) {
            throw new Exception('expected count ' . $expectedValidCombination . ' combination not found: ' . print_r($elementsToFind, true) . 'The actual count is: ' . $validCombinations);
        }
    }

    /**
     * @Then /^Ich starte die Session neu$/
     */
    public function restartSession()
    {
        $this->getSession()->restart();
    }

    /**
     * @Then /^Ich resete die Session/
     */
    public function restetSession()
    {
        $this->getSession()->reset();
    }

    #TODO Diese Methode muss noch zerteilt werden. Den Oparator las Variable raus und direkt in den Aufruf dann gießen
    /**
     * Als Operator kann min (kleiner gleich), gleich oder max (größer gleich) gewählt werden.
     * @Then /^Unter dem Pfad "([^"]*)" existieren "(?P<operator>(?:[^"]|\\")*)" "([^"]*)" Elemente$/
     */
    public function checkCountOfElements($domSelector, $expectetedOperator, $expectedCountCombination)
    {
        $domElements = $this->getSession()->getPage()->findAll("css", $domSelector);

        if (!$domElements) {
            throw new Exception($domSelector . " could not be found");
        }

        if($expectetedOperator == "min"){
            if(count($domElements) >= $expectedCountCombination){

            } else{
                throw new Exception('expected count ' . $expectedCountCombination . ' combination not found: ' . 'The actual count is: ' . count($domElements));
            }
        } elseif ($expectetedOperator == "gleich"){
            if($expectedCountCombination == count($domElements)){
            }

            else{
                throw new Exception('expected count ' . $expectedCountCombination . ' combination not found: ' . 'The actual count is: ' . count($domElements));
            }
        } elseif ($expectetedOperator == "max") {
            if(count($domElements) <= $expectedCountCombination){

            }
            else{
                throw new Exception('expected count ' . $expectedCountCombination . ' combination not found: ' . 'The actual count is: ' . count($domElements));
            }
        }

    }



    /**
     * @Then /^Wenn Element "([^"]*)" vorhanden ist, dann klicke den "([^"]*)" Button, sonst übergehe diesen Schritt$/
     */
    public function ifElementFoundThenClickButton($domSelector, $button)
    {
        $domElement = $this->getSession()->getPage()->find("css", $domSelector);

        if (!$domElement) {
            var_dump("kein Element gefunden");
            #TODO Eine Meldung ausgeben das, dass gesuchte Element nicht vorhanden ist.
        }else{
            var_dump("Element gefunden");
            $this->iClickOn($button);
            #$domElement->press();
        }
    }

    /**
     * @Then /^Remove Focus from "([^"]*)" Element$/
     */
    public function removeFocusFromELement($domSelectorElement)
    {
        $domElement = $this->getSession()->getPage()->find("css", $domSelectorElement);

        if (!$domElement) {
            throw new Exception($domSelectorElement . " could not be found");
        } else {
            $domElement->blur();
        }
    }

}
