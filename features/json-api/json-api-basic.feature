# language: en
Feature: hateoas notes api Test

  Scenario: Check _links property in users
    When I am on "/api/hal/users"
    Then the object "_links" contains:
      """
        {
          "first": {
            "href": "/api/hal/users?offset=0&limit=20"
          }
        }
      """
