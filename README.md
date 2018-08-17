# exozet/behat-utils

The `exozet/behat-utils` provides some traits for easier api testing with behat and mink.

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

The `JsonApiSteps` are helpful for testing json apis.

### WebsiteInteractionSteps

The `WebsiteInteractionSteps` simplify DOM-based interactions with websites.

### SpinnedMinkSteps

The `SpinnedMinkSteps` allow calling default MinkContext steps while allowing a specified timeout.

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

```

## LICENSE

The behat-utils is copyright by Exozet (http://exozet.com) and licensed under the terms of MIT License.

