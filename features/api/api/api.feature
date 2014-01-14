Feature: Test on the API itself

Scenario: I can't fetch any data without token in query args
  When I fetch any API, like users, wihtout token
  Then the response status code should be 400
    And the JSON key "code" should be equal to 400

Scenario: I send a post request with invalid JSON data
  When I send a post request with invalid JSON data
  Then the response status code should be 400
    And the JSON key "code" should be equal to 400
