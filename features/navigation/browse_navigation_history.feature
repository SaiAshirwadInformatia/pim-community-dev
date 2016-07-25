@javascript
Feature: Browse the history in the history tab of the pinbar
  In order to easily know what pages were visited
  As a regular user
  I need to be able to see navigation history

  Background:
    Given a "default" catalog configuration
    And I am logged in as "Mary"

  @jira https://akeneo.atlassian.net/browse/PIM-5828
  Scenario: Add pages to the pinbar
    When I am on the product index page
    And I am on the family index page
    And I am on the home page
    When I click on the pin bar dot menu
    And I press the "History" button
    Then I should see 2 "#history-content li" elements
    And I should see "Families" in the "#history-content ul" element
    And I should see "Products" in the "#history-content ul" element

  @jira https://akeneo.atlassian.net/browse/PIM-5828
  Scenario: Add pages to the pinbar 2
    When I am on the product index page
    And I am on the family index page
    And I am on the home page
    When I click on the pin bar dot menu
    And I press the "History" button
    Then I should see 2 "#history-content li" elements
    And I should see "Families" in the "#history-content ul" element
    And I should see "Products" in the "#history-content ul" element

  @jira https://akeneo.atlassian.net/browse/PIM-5828
  Scenario: Add pages to the pinbar 3
    When I am on the product index page
    And I am on the family index page
    And I am on the home page
    When I click on the pin bar dot menu
    And I press the "History" button
    Then I should see 2 "#history-content li" elements
    And I should see "Families" in the "#history-content ul" element
    And I should see "Products" in the "#history-content ul" element

  @jira https://akeneo.atlassian.net/browse/PIM-5828
  Scenario: Add pages to the pinbar 4
    When I am on the product index page
    And I am on the family index page
    And I am on the home page
    When I click on the pin bar dot menu
    And I press the "History" button
    Then I should see 2 "#history-content li" elements
    And I should see "Families" in the "#history-content ul" element
    And I should see "Products" in the "#history-content ul" element

  @jira https://akeneo.atlassian.net/browse/PIM-5828
  Scenario: Add pages to the pinbar 5
    When I am on the product index page
    And I am on the family index page
    And I am on the home page
    When I click on the pin bar dot menu
    And I press the "History" button
    Then I should see 2 "#history-content li" elements
    And I should see "Families" in the "#history-content ul" element
    And I should see "Products" in the "#history-content ul" element

  @jira https://akeneo.atlassian.net/browse/PIM-5828
  Scenario: Add pages to the pinbar 6
    When I am on the product index page
    And I am on the family index page
    And I am on the home page
    When I click on the pin bar dot menu
    And I press the "History" button
    Then I should see 2 "#history-content li" elements
    And I should see "Families" in the "#history-content ul" element
    And I should see "Products" in the "#history-content ul" element

  @jira https://akeneo.atlassian.net/browse/PIM-5828
  Scenario: Add pages to the pinbar 7
    When I am on the product index page
    And I am on the family index page
    And I am on the home page
    When I click on the pin bar dot menu
    And I press the "History" button
    Then I should see 2 "#history-content li" elements
    And I should see "Families" in the "#history-content ul" element
    And I should see "Products" in the "#history-content ul" element

  @jira https://akeneo.atlassian.net/browse/PIM-5828
  Scenario: Add pages to the pinbar 8
    When I am on the product index page
    And I am on the family index page
    And I am on the home page
    When I click on the pin bar dot menu
    And I press the "History" button
    Then I should see 2 "#history-content li" elements
    And I should see "Families" in the "#history-content ul" element
    And I should see "Products" in the "#history-content ul" element

  @jira https://akeneo.atlassian.net/browse/PIM-5828
  Scenario: Add pages to the pinbar 9
    When I am on the product index page
    And I am on the family index page
    And I am on the home page
    When I click on the pin bar dot menu
    And I press the "History" button
    Then I should see 2 "#history-content li" elements
    And I should see "Families" in the "#history-content ul" element
    And I should see "Products" in the "#history-content ul" element

  @jira https://akeneo.atlassian.net/browse/PIM-5828
  Scenario: Add pages to the pinbar 10
    When I am on the product index page
    And I am on the family index page
    And I am on the home page
    When I click on the pin bar dot menu
    And I press the "History" button
    Then I should see 2 "#history-content li" elements
    And I should see "Families" in the "#history-content ul" element
    And I should see "Products" in the "#history-content ul" element
