Feature: Facade classes call methods of instances returned by a service locator

Scenario: I can call an instance method from a facade
  Given I set a service locator with an instance of "ArrayObject" registered as "array_object"
  And I have a facade to a service named "array_object"
  When I call the method "count" on that facade
  Then I get 0 as the facade return value
  
Scenario: I have an unimplemented facade that throws an exception upon usage
  Given I set a service locator with an instance of "ArrayObject" registered as "array_object"
  And I have an unimplemented facade
  When I call the method "count" on that facade
  Then A "LogicException" exception should have been thrown 

Scenario: I have a good facade but a service locator has not been set
  Given I have a facade to a service named "array_object"
  When I call the method "count" on that facade
  Then A "LogicException" exception should have been thrown with message "Service locator has not been set yet"