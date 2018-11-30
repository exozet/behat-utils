<?php

namespace Exozet\Behat\Utils\Base;

use Behat\Behat\Tester\Exception\PendingException;
use PHPUnit\Framework\TestCase;
use Exozet\Behat\Utils\Test\FeatureContext;

/**
 * Override time() in current namespace for testing
 *
 * @return int
 */
function time()
{
    return ConditionStepsTest::$now ?: \time();
}

class ConditionStepsTest extends TestCase
{
    /**
     * @var int $now Timestamp that will be returned by time()
     */
    public static $now;

    /**
     * @var FeatureContext $featureContext Test subject
     */
    private $featureContext;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        parent::setUp();
        $this->featureContext = new FeatureContext();
    }

    /**
     * Reset custom time after test
     */
    protected function tearDown()
    {
        self::$now = null;
    }

    public function testDoesSuccessfullyCheckWhetherCurrentTimeIsWithinGivenTimeInterval()
    {
        self::$now = strtotime('14:00');
        $this->assertNull($this->featureContext->actualTimeIsInSpecifiedTime('13:00', '15:00'));
    }

    public function testDoesSuccessfullyCheckWhetherCurrentTimeIsWithinGivenTimeIntervalSpanningOverMidnight()
    {
        self::$now = strtotime('4:00');
        $this->assertNull($this->featureContext->actualTimeIsInSpecifiedTime('22:00', '6:00'));
    }

    public function testDoesThrowPendingExceptionWhenCurrentTimeIsNotWithinGivenTimeInterval()
    {
        self::$now = strtotime('15:00');
        $this->expectException(PendingException::class);
        $this->featureContext->actualTimeIsInSpecifiedTime('9:00', '12:00');
    }

    public function testDoesThrowPendingExceptionWhenCurrentTimeIsNotWithinGivenTimeIntervalSpanningOverMidnight()
    {
        self::$now = strtotime('8:00');
        $this->expectException(PendingException::class);
        $this->featureContext->actualTimeIsInSpecifiedTime('22:00', '6:00');
    }

    public function testDoesSuccessfullyCheckWhetherCurrentTimeIsWithingATimeIntervalSpanningExactlyForTheCurrentTime()
    {
        self::$now = strtotime('6:00');
        $this->assertNull($this->featureContext->actualTimeIsInSpecifiedTime('6:00', '6:00'));
    }
}
