Feature: Class aliases are created on demand

Scenario: Registered classes are autoloaded upon usage
  Given I have registered an alias named "DummyAlias" and mapped to "DummyClassRegistered"
  When I try to use that class
  Then I should have that class available

@common
Scenario: Unregister makes aliases not autoload anymore
  Given I have registered an alias named "DummyAliasUnregistered" and mapped to "DummyClassUnregistered"
  When I unregister the class aliases
  And I try to use that class
  Then I should not have that class available
    
Scenario: Self referencing alias is registered and used
  Given I have registered an alias named "DummyAliasSelfReference" and mapped to non existing "DummyAliasSelfReference"
  When I try to use that class
  Then A "LogicException" exception should have been thrown with message "Class alias is referencing the alias itself"