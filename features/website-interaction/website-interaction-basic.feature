# language: en
Feature: HTML Test

  Scenario: Fetch status code 404
    When I am on "/inexistent-page"
    Then the response status code should be 404

  @javascript
  Scenario: Press Users-Button
    When I am on "/api/html"
    Then I should see "hateoas-notes in HTML"
    When I press "Users"
    Then I should be on "/api/html/users"
    Then I should see "Users"

  @javascript
  Scenario: Scroll to something (scroll it to the middle of the viewport)
    When I am on "/api/html"
    Then I should see "hateoas-notes in HTML"
    Then I scroll to "button"

  @javascript
  Scenario: Scroll something to the top of the viewport
    Given I am on "/api/html"
    And I scroll to have ".breadcrumb" at the top of the viewport
    Then I see visible elements matching ".breadcrumb" in time

  @javascript
  Scenario: Scroll something to the bottom of the viewport
    Given I am on "/api/html"
    And I scroll to have ".breadcrumb" at the bottom of the viewport
    Then I see visible elements matching ".breadcrumb" in time

  @javascript
  Scenario: Wait for matching elements within a specified time interval
    When I am on "/api/html/notes"
    Then I see elements matching "h1" within 4 seconds

  @javascript
  Scenario: Wait for matching elements within the default time interval
    When I am on "/api/html/notes"
    Then I see elements matching "h1" in time

  @javascript
  Scenario: Wait for visible matching elements within a specified time interval
    When I am on "/api/html/notes"
    Then I see visible elements matching "h1" within 4 seconds

  @javascript
  Scenario: Wait for visible matching elements within the default time interval
    When I am on "/api/html/notes"
    Then I see visible elements matching "h1" in time

  @javascript
  Scenario: Wait for the default timeout
    When I am on "/api/html"
    And I wait
    Then I should be on "/api/html"

  @javascript
  Scenario: Open Url in default time
    When I am on "/api/html" in time
    Then I should be on "/api/html"
