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
  Scenario: Scroll to something
    When I am on "/api/html"
    Then I should see "hateoas-notes in HTML"
    Then I scroll to "button"

  @javascript
  Scenario: Wait for matching elements within a specified time interval
    When I am on "/api/html/notes"
    Then I see elements matching "h1" within 4 seconds
