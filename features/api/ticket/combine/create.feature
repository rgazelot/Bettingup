Feature: Create Combine Ticket

Scenario: Create ticket without type key
  Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
  When I create a ticket with these data:
  """
  {"foo":"bar"}
  """
  Then the response status code should be 400
  And the JSON key "code" should be equal to 400
  And the JSON key "message" should be equal to "type key is missing"

Scenario: Create combine ticket without amount key
  Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
  When I create a ticket with these data:
  """
  {
    "type":"combine",
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

Scenario: Create combine ticket with one bet
  Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
  When I create a ticket with these data:
  """
  {
    "type":"combine",
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
  Then the response status code should be 400
  And the JSON key "code" should be equal to 400
  And the JSON key "message" should be equal to "A combine ticket must have at least two bets."

Scenario: Create combine Ticket
  Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
  When I create a ticket with these data:
  """
  {
    "type":"combine",
    "amount":50,
    "bets":[
      {
        "odds":1.4,
        "home":4,
        "visitor":5,
        "competition":0,
        "bet_type":0,
        "pronostic":1,
        "status":false
      },
      {
        "odds":1.4,
        "home":2,
        "visitor":3,
        "competition":0,
        "bet_type":0,
        "pronostic":1,
        "status":true
      }
    ]
  }
  """
  Then the response status code should be 201
  And the JSON key "options[amount]" should be equal to 50
  And the JSON key "options[totalOdds]" should be equal to 1.96
  And the JSON key "bets[0][odds]" should be equal to 1.4
  And the JSON key "bets[0][home]" should be equal to 4
  And the JSON key "bets[1][odds]" should be equal to 1.4
  And the JSON key "bets[1][home]" should be equal to 2
  And the JSON key "bets[1][visitor]" should be equal to 3
