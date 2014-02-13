Feature: Create User

Scenario: Create an User
  Given I use the token "1e928dda66fc4031678d0e5c5557f5e2e9061614"
  When I create an user with these data :
  """
  {"username":"newBehatUser", "email":"newbehatuser@bettingup.com", "password":"pass"}
  """
  Then the response status code should be 201
