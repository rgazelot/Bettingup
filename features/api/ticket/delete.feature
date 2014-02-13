Feature: Delete a Ticket

Scenario: Try to delete the ticket from another user
  Given I use the token "d033e22ae348aeb5660fc2140aec35850c4da997"
  When I delete the ticket "delete1"
  Then the response status code should be 403
  And the JSON key "code" should be equal to 403
  And the JSON key "message" should be equal to "Wrong rights"

Scenario: Delete a ticket
  Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
  When I delete the ticket "delete1"
  Then the response status code should be 204
