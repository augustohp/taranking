Feature: Allow new user registrations.
    In order to record my games and scores
    As an visitor
    I need to be able to register to identify myself in games

Background:
    Given I am the first visitor

Scenario Outline: Successfull registration
    Given I visit "/"
    When I click on "#register"
    Then I input "<Nick>" on "#name"
    And I input "123456" on "#passwd"
    And I input "123456" on "#passwd2"
    When I click on ".submit"
    Then I should see "User successfully registered!" on ".notice"
Examples:
    | Nick          |
    | tbon3         |
    | stinger       |
    | RacX          |
    | DandS         |
    | No_Mercy      |
    | Sukkoy        |

Scenario Outline: Invalid data for registration
    Given I visit "/users/register"
    And I input "<Nick>" on "#name"
    And I input "<Password>" on "#passwd"
    And I input "<Password Check>" on "#passwd2"
    When I click on ".submit"
    Then I should see '<Message>' on ".alert"
Examples:
    | Nick          | Password | Password Check | Message                                                |
    | a             | 123456   | 123456         | must have a length between 3 and 45                    |
    | a##           | 123456   | 123456         | must contain only letters (a-z), digits (0-9) and "-_" |
    | tbon3         |          |                | Password must be only alpha-numeric and have length between 6 and 45 |
    | tbon3         | 1234     | 1234           | Password must be only alpha-numeric and have length between 6 and 45 |
    | tbon3         | 123456   |                | Passwords must be the same                                           |

