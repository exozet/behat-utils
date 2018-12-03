# exozet/behat-utils

[![Build Status](https://travis-ci.org/exozet/behat-utils.svg?branch=master)](https://travis-ci.org/exozet/behat-utils)

The `exozet/behat-utils` provide some traits for easier testing of websites and web services using Behat and Mink.

## Usage

```console
$ composer require exozet/behat-utils
```

and add

```php
use \Exozet\Behat\Utils\Base\JsonApiSteps;
```

to your context, like this:

```php
class ServiceContext extends \Behat\MinkExtension\Context\MinkContext
{
    use \Exozet\Behat\Utils\Base\JsonApiSteps;
}
```

That's it!

## Traits

### JsonApiSteps

The `JsonApiSteps` are helpful for testing JSON APIs.

### WebsiteInteractionSteps

The `WebsiteInteractionSteps` simplify DOM-based interactions with websites.

### SpinnedMinkSteps

The `SpinnedMinkSteps` allow calling default MinkContext steps while allowing a specified timeout.

### ConditionSteps

The `ConditionSteps` offer steps that only continue the test execution under specific conditions, marking the tests as "Pending" otherwise.

### HelpUtils

The `HelpUtils` offer helper functions that can be useful for defining own steps.

## Development of behat-utils

If you want to develop on those utils, please use chromedriver and run it like this: 

```console
$ chromedriver --whitelisted-ips='*'
```

Then use the embedded docker-compose.yml and run a php-cli like this:
```console
$ docker-compose run --rm php-cli
www-data $ bash setup-dev.sh 
www-data $ vendor/bin/behat -p local
www-data $ ./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests
```

## LICENSE

The behat-utils is copyright by Exozet (http://exozet.com) and licensed under the terms of MIT License.

