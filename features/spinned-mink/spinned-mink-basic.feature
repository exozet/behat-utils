# language: en
Feature: Spinned MinkContext step variants

@javascript
  Scenario: assetPageAddress within the default time interval
    Given I am on "/api/html"
    Then I should be on "/api/html" in time

  @javascript
  Scenario: assetPageAddress within a specified time interval
    Given I am on "/api/html"
    Then I should be on "/api/html" within 3 seconds

  @javascript
  Scenario: assetNotPageAddress within the default time interval
    Given I am on "/api/html"
    Then I should not be on "/api/another-uri" in time

  @javascript
  Scenario: assetNotPageAddress within a specified time interval
    Given I am on "/api/html"
    Then I should not be on "/api/another-uri" within 3 seconds

  @javascript
  Scenario: assertPageContainsText within default time interval
    Given I am on "/api/html"
    Then I should see "Home" in time

  @javascript
  Scenario: assertPageContainsText within a specified time interval
    Given I am on "/api/html"
    Then I should see "Home" within 3 seconds

  @javascript
  Scenario: assertPageNotContainsText within default time interval
    Given I am on "/api/html"
    Then I should not see "Non-existent text" in time

  @javascript
  Scenario: assertPageNotContainsText within a specified time interval
    Given I am on "/api/html"
    Then I should not see "Non-existent text" within 3 seconds

  @javascript
  Scenario: assertElementContainsText within default time interval
    Given I am on "/api/html"
    Then I should see "Home" in the "li.active" element in time

  @javascript
  Scenario: assertElementContainsText within a specified time interval
    Given I am on "/api/html"
    Then I should see "Home" in the "li.active" element within 3 seconds

  @javascript
  Scenario: assertElementOnPage within default time interval
    Given I am on "/api/html"
    Then I should see an "h2" element in time

  @javascript
  Scenario: assertElementOnPage within a specified time interval
    Given I am on "/api/html"
    Then I should see an "h2" element within 3 seconds

  @javascript
  Scenario: assertElementNotOnPage within default time interval
    Given I am on "/api/html"
    Then I should not see an ".not-existent" element in time

  @javascript
  Scenario: assertElementNotOnPage within a specified time interval
    Given I am on "/api/html"
    Then I should not see an ".not-existent" element within 3 seconds

  @javascript
  Scenario: fillField within default time interval
    Given I am on "/api/html/register-user"
    When I fill in "username" with "sample username" in time
    Then the "username" field should contain "sample username"

  @javascript
  Scenario: fillField within a specified time interval
    Given I am on "/api/html/register-user"
    When I fill in "username" with "sample username" within 3 seconds
    Then the "username" field should contain "sample username"
