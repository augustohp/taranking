Feature: Allow new user registrations.
    In order to record my games and scores
    As an visitor
    I need to be able to register to identify myself in games

Background:
    Given I am the first visitor

Scenario: New user registration
    Given I visit "/"
    When I click on "#register"
    Then I input "tbon3" on "#name"
    And I input "123456" on "#passwd"
    And I input "123456" on "#passwd2"
    When I click on ".submit"
    Then I should see "User successfully registered!" on ".notice"

