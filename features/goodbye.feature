Feature: Redirecting all traffic to a single page

    Background:
        And there are posts:
            | post_title      | post_content              | post_status | post_author | post_type |
            | Just my article | The content of my article | publish     | admin       | post      |
            | My draft        | This is just a draft      | draft       | admin       | post      |
			| Moving day      | Go away                   | publish     | admin       | page      |

    @javascript
    Scenario: List my blog posts
		Given I am logged in as an admin
        And I am on the homepage
        Then I should see "Just my article"
        And I should not see "My draft"
		And I am on the Dashboard
		And I go to menu item "Goodbye Please"
		And I select "Moving day" from "goodbye-please-page"
		And I press "Save Changes"
		And I am on the homepage
		Then I should see "Go away"
