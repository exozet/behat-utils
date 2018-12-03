# language: en
Feature: Steps for checking conditions before continuing test executions

  @javascript
  Scenario: Check if current time is in range
    When the current time is between "06:00" and "20:00", otherwise skip the test case
    And I am on "/api/html" in time
    Then I should be on "/api/html"
