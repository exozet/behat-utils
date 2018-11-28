<?php

namespace Exozet\Behat\Utils\Base;


trait HelpUtis {


    public function getDefaultJavascriptTime() {
        return 5000;
    }

    public function assertJavascriptExpression($sourceCode, $waitTime = null) {
        if ($waitTime === null) {
            $waitTime = $this->getDefaultJavascriptTime();
        }

        $returnValue = $this->getSession()->wait(
            $waitTime,
            $sourceCode
        );

        if (!$returnValue) {
            throw new \Exception('The source code ' . $sourceCode . ' did not match within ' . $waitTime . 'ms.');
        }
        assert($returnValue);
    }
}
