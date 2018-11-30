<?php

namespace Exozet\Behat\Utils\Base;

use Behat\Behat\Tester\Exception\PendingException;

trait ConditionSteps
{

    /**
     * Check if the current time is within the specified time. Otherwise, throw a PendingException and thus skip the test case.
     * Example: When the current time is between "06:00" and "20:00", otherwise skip the test case
     *
     * @When /^the current time is between "(?P<fromTime>[^"]+)" and "(?P<toTime>[^"]+)", otherwise skip the test case$/
     * @When /^die aktuelle Uhrzeit liegt zwischen "(?P<fromTime>[^"]+)" und "(?P<toTime>[^"]+)", sonst breche das Testszenario ab$/
     * @throws PendingException
     */
    public function actualTimeIsInSpecifiedTime($fromTime, $toTime)
    {
        date_default_timezone_set('Europe/Berlin');
        $timestamp = time();
        $currentTime = date('H:i', $timestamp);
        $fromDate = date('H:i', strtotime($fromTime));
        $toDate = date('H:i', strtotime($toTime));

        if ($this->checkTime($currentTime, $fromDate, $toDate) == false) {
            throw new PendingException('The current time (' . $currentTime . ') is outside of the specified range. Specified range: from ' . $fromDate . ' to ' . $toDate);
        }
    }

    /**
     * Check if the current time is within the specified time and return true or false
     * Example: currentTime: 22:34 - fromeDate: 20:00 - toDate: 06:00 -> Result: true
     */
    private function checkTime($currentTime, $fromDate, $toDate)
    {
        if ($fromDate < $toDate) {
            if (($currentTime >= $fromDate) && ($currentTime <= $toDate)) {
                return true;
            } else {
                return false;
            }
        } elseif ($fromDate > $toDate) {
            if (($currentTime >= $fromDate) || ($currentTime <= $toDate)) {
                return true;
            } else {
                return false;
            }
        }
    }
}
