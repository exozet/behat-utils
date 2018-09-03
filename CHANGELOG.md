# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.5.0] - 2018-09-03
### Added
- Add "waitForVisibleMatchingElementsWithinSpecifiedTime" step that allows checking elements' visibility in viewport
- Add wrapped parameter-less variants of timeout steps using a default timeout. #7
### Changed
- WebsiteInteractionSteps with a timeout now fail correctly when element(s) matching the given selector do not exist. #6
- Make the English step translations the default by moving them before the German translations. #8
- Refactor spinning used in SpinnedMinkSteps to produce better error messages on exceptions

## [0.4.0] - 2018-08-30
### Added
- Add TravisCI support for behat-utils
- Add "SpinnedMinkSteps" trait for calling default MinkContext steps with a specified timeout

## [0.3.0] - 2018-08-10
### Added
- Add "WebsiteInteractionSteps" trait for interacting with websites based on DOM elements
### Changed
- Remove unnecessary duplicate "require" from composer.json

## [0.2.0] - 2017-12-12
### Changed
- Fix typos in JsonApiSteps
- Pass HTTP headers to POST requests in JsonApiSteps

## 0.1.0 - 2017-03-27
### Added
- Add "JsonApiSteps" trait for sending, receiving and asserting JSON data

[0.5.0]: https://github.com/exozet/behat-utils/compare/0.4.0...0.5.0
[0.4.0]: https://github.com/exozet/behat-utils/compare/0.3.0...0.4.0
[0.3.0]: https://github.com/exozet/behat-utils/compare/0.2.0...0.3.0
[0.2.0]: https://github.com/exozet/behat-utils/compare/0.1.0...0.2.0
