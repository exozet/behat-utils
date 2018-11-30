# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased
### Added
- Add "openUrl" and "openUrlWithinSpecifiedTime" steps. Both open a url and compares, within a specified time, the current url with the expected one
- Add "ConditionSteps" trait for conditional steps that mark tests as "pending" when specific conditions are not met
- Add "actualTimeIsInSpecifiedTime" step that allows checking whether the current time is in the expected range
- Add "resizeWindowToResponsiveViewport" step that allows resizing the driver's browser windows for a given responsive device type
- Add "mouseOver" step that allows hovering elements 
- Add "fillFieldWithinDefaultTimeoutUsingJavaScript" step that allows filling input fields within a default timeout using JavaScript
- Add "activateIFrame" that allows switching the currently active frame to a given iFrame
- Add spinned version of MinkContext's "assertElementNotContainsText" method
- Add "HelpUtils" trait for helper functions that can be useful for defining own steps
- Add "assertJavascriptExpression" method to the "HelpUtils" trait wrapping the wait method of Mink's Session
- Add "scrolIntoViewAlignToBottom" step that allows scrolling such that a given element is at the bottom of the viewport
- Add "clickWithinDefaultTimeoutUsingJavaScript" step that allows clicking an element via JavaScript within the default timeout configured
 
### Changed
- Increase the default waiting interval for the SpinnedMinkSteps from 5 seconds to 15 seconds
- Change error message in the SpinnedMinkSteps to a more descriptive one

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
