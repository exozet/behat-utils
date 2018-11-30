<?php

namespace Exozet\Behat\Utils\Base;

class FeatureContext extends \Behat\MinkExtension\Context\MinkContext {
    use \Exozet\Behat\Utils\Base\JsonApiSteps;
    use \Exozet\Behat\Utils\Base\WebsiteInteractionSteps;
    use \Exozet\Behat\Utils\Base\SpinnedMinkSteps;
    use \Exozet\Behat\Utils\Base\ConditionSteps;

    protected $defaultTimeout = 3;  // Set the default timeout to 3 seconds
}
