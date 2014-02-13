Feature: Delete a Ticket

Scenario: Delete a ticket
	Given I use the token of an admin
	When I delete the ticket "1"
	Then the response status code should be 400
