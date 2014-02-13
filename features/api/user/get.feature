Feature: Retrieve users

Scenario: I retrieve a non existing user
  Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
  When I retrieve the use "foo"
  Then the response status code should be 404
  And the JSON key "code" should be equal to "404"
  And the JSON key "message" should be equal to "User not found."

Scenario: I retrieve an user
  Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
  When I retrieve the use "behat"
  Then the response status code should be 200
  And the JSON key "username" should be equal to "behat"
