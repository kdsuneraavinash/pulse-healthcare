import monolithic
import time
import unittest


class TestAllPublicPages(monolithic.MonolithicTest):
    def step_1_go_to_homepage(self):
        self.browser.get('http://localhost:8000')
        self.assertBrowserTitle('medikit')

    def step_2_click_signin_button(self):
        signInButton = self.browser.find_element_by_id("home_page_sign_in")
        self.assertElementText('sign in', signInButton)
        signInButton.click()

    def step_3_verify_signin_page(self):
        self.browser.find_element_by_id('account')
        self.browser.find_element_by_id('password')
        loginButton = self.browser.find_element_by_id("login_page_sign_in")
        self.assertElementText('sign in', loginButton)

    def step_4_click_homepage_button(self):
        homePageButton = self.browser.find_element_by_id("site_logo")
        homePageButton.click()

    def step_5_click_signup_button(self):
        signUpButton = self.browser.find_element_by_id("home_page_register")
        self.assertElementText('register now', signUpButton)
        signUpButton.click()

    def step_6_verify_signup_page(self):
        self.assertBrowserTitle('medi center registration')
        self.browser.find_element_by_id("med_center_register_button")


if __name__ == '__main__':
    unittest.main(verbosity=2)
