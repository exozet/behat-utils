# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Add "waitForVisibleMatchingElementsWithinSpecifiedTime" step that allows checking elements' visibility in viewport

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

[Unreleased]: https://github.com/exozet/behat-utils/compare/0.4.0...HEAD
[0.4.0]: https://github.com/exozet/behat-utils/compare/0.3.0...0.4.0
[0.3.0]: https://github.com/exozet/behat-utils/compare/0.2.0...0.3.0
[0.2.0]: https://github.com/exozet/behat-utils/compare/0.1.0...0.2.0
