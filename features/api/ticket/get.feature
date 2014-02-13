Feature: Get a Ticket

Scenario: Fetch with wrong id
  Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
  When I fetch the ticket "foooooo"
  Then the response status code should be 404
  And the JSON key "code" should be equal to 404
  And the JSON key "message" should be equal to "Ticket not found"

Scenario: Fetch a ticket of another user
  Given I use the token "d033e22ae348aeb5660fc2140aec35850c4da997"
  When I fetch the ticket "simple1"
  Then the response status code should be 404
  And the JSON key "code" should be equal to 404
  And the JSON key "message" should be equal to "Ticket not found"

Scenario: Fetch a ticket
  Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
  When I fetch the ticket "simple1"
  Then the response status code should be 200
  And the JSON key "hash" should be equal to "simple1"
  And the JSON key "bets[0][hash]" should be equal to "bet0001"

Scenario: Fetch the tickets of an user wihtout user parameter
  Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
  When I fetch the tickets without parameters
  Then the response status code should be 400
  And the JSON key "code" should be equal to 400
  And the JSON key "message" should be equal to "user parameter missing"

Scenario: Fetch the tickets of another user
  Given I use the token "d033e22ae348aeb5660fc2140aec35850c4da997"
  When I fetch the tickets of the user "behat01"
  Then the response status code should be 403
  And the JSON key "code" should be equal to 403
  And the JSON key "message" should be equal to "Wrong rights"

# Scenario: Fetch the tickets of an user
#   Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
#   When I fetch the tickets of the user "behat01"
#   Then the response status code should be 200
#   And the JSON key "[0][hash]" should be equal to "simple1"
#   And the JSON key "[1][hash]" should be equal to "simple2"
