#!/bin/bash
composer install --quiet
composer require behat/behat=^3.3  behat/mink-extension=^2.2 behat/mink-goutte-driver=^1.2 behat/mink-selenium2-driver=^1.3 --quiet
