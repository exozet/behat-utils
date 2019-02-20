<?php

namespace Exozet\Behat\Utils\Base;

use Behat\Mink\Exception\ExpectationException;

trait HelpUtils
{

    /**
     * Returns the default timeout in milliseconds used by JavaScript steps that involve a timeout.
     * You may override the default timeout in classes using this trait by using:
     *   protected $defaultJavaScriptTimeout = 10;  // Set the default timeout to 10 seconds
     *
     * @return int the default timeout in milliseconds
     * @throws ExpectationException
     */
    public function getDefaultJavascriptTimeInMilliseconds()
    {
        return $this->getDefaultJavascriptTime() * 1000;
    }

    public function getDefaultJavascriptTime()
    {
        return isset($this->defaultJavascriptTimeout) ? $this->defaultJavascriptTimeout : 5;
    }

    /**
     * Asserts that a given JavaScript expression does evaluate to "true" within an optionally given timeout.
     * If no $time is set, the default timeout returned by getDefaultJavascriptTimeInMilliseconds() is used
     * @see Session::wait
     *
     * @param string $condition the JavaScript condition that should evaluate to "true"
     * @param int    $time      optional timeout in milliseconds that is allowed for the condition to evaluate
     * @throws ExpectationException
     */
    public function assertJavascriptExpression($condition, $time = null)
    {
        if ($time === null) {
            $time = $this->getDefaultJavascriptTimeInMilliseconds();
        }

        $returnValue = $this->getSession()->wait(
            $time,
            $condition
        );

        if (!$returnValue) {
            throw new ExpectationException('The JavaScript code ' . $condition . ' did not match within ' . $time . 'ms.',
                $this->getDriver());
        }

        assert($returnValue);
    }
}
