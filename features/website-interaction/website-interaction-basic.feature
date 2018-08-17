# language: en
Feature: HTML Test

  Scenario: Fetch status code 404
    When I am on "/inexistent-page"
    Then the response status code should be 404

  @javascript
  Scenario: Press Users-Button
    When I am on "/api/html"
    When I press "Users"
    Then I should be on "/api/html/users"
    Then I should see "Users"
