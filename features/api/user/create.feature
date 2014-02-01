Feature: Create User

Scenario: Create an User
  Given I use the token of an admin
  When I create an user with these data :
  """
  {"username":"newBehatUser", "email":"newbehatuser@bettingup.com", "password":"pass"}
  """
  Then the response status code should be 201
