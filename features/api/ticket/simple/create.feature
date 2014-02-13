Feature: Create Simple Ticket

Scenario: Create ticket without type key
  Given I use the token of an admin
  When I create a ticket with these data:
  """
  {"foo":"bar"}
  """
  Then the response status code should be 400
  And the JSON key "code" should be equal to 400
  And the JSON key "message" should be equal to "type key is missing"

Scenario: Create simple ticket without amount key
  Given I use the token of an admin
  When I create a ticket with these data:
  """
  {
    "type":"simple",
    "bets":[
      {
        "odds":20
      }
    ]
  }
  """
  Then the response status code should be 400
  And the JSON key "code" should be equal to 400
  And the JSON key "message" should be equal to "amount key is missing"

Scenario: Create simple ticket with two bets
  Given I use the token of an admin
  When I create a ticket with these data:
  """
  {
    "type":"simple",
    "amount":50,
    "bets":[
      {
        "odds":20,
        "home":1,
        "visitor":1,
        "competition":0,
        "bet_type":0,
        "pronostic":1,
        "status":false
      },
      {
        "odds":20,
        "home":1,
        "visitor":1,
        "competition":0,
        "bet_type":0,
        "pronostic":1,
        "status":false
      }
    ]
  }
  """
  Then the response status code should be 400
  And the JSON key "code" should be equal to 400
  And the JSON key "message" should be equal to "A simple ticket must have only one bet."

Scenario: Create Simple Ticket
  Given I use the token of an admin
  When I create a ticket with these data:
  """
  {
    "type":"simple",
    "amount":50,
    "bets":[
      {
        "odds":20,
        "home":1,
        "visitor":1,
        "competition":0,
        "bet_type":0,
        "pronostic":1,
        "status":false
      }
    ]
  }
  """
  Then the response status code should be 201
  And the JSON key "options[amount]" should be equal to 50
  And the JSON key "bets[0][odds]" should be equal to 20
  And the JSON key "bets[0][home]" should be equal to 1
