<?php

namespace Exozet\Behat\Utils\Base;


trait ConditionSteps {


    /**
     *  @Then /^die aktuelle Uhrzeit liegt zwischen "(?P<fromTime>[^"]+)" und "(?P<toTime>[^"]+)", sonst breche das Testzsenario ab/
     */

    public function actualTimeIsInSpecifiedTime($fromTime, $toTime){
        date_default_timezone_set("Europe/Berlin");
        $timestamp = time();
        $currentTime = date("H:i",$timestamp);
        $fromDate = date("H:i", strtotime($fromTime));
        $toDate = date("H:i", strtotime($toTime));

        if($this->checkTime($currentTime,$fromDate, $toDate) == false){
            throw new \Behat\Behat\Tester\Exception\PendingException("Die momentane Zeit: " . $currentTime . " befindet sich außerhalb der Range. From: " . $fromDate . " To: " .$toDate);
        }
    }

    public function checkTime($currentTime, $fromDate, $toDate){

        if($fromDate < $toDate){
            if(($currentTime >= $fromDate) && ($currentTime <= $toDate)) {
                return true;
            }else {
                return false;
            }
        }elseif ($fromDate > $toDate){
            if(($currentTime >= $fromDate) || ($currentTime <= $toDate)) {
                return true;
            }else {
                return false;
            }
        }
    }
}
