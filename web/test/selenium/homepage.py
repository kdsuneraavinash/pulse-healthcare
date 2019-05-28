import monolithic
import time
import unittest


class TestAllPublicPages(monolithic.MonolithicTest):
    def step_1_go_to_homepage(self):
        self.browser.get('http://localhost:8000')
        self.assertIn('medikit', self.browser.title.lower())

    def step_2_click_signin_button(self):
        signInButton = self.browser.find_element_by_css_selector(
            "#home > div > div.col-md-5.my-auto > div > div > form > button")
        self.assertIn('sign in', signInButton.text.lower())
        signInButton.click()

    def step_3_verify_signin_page(self):
        self.browser.find_element_by_id('account')
        self.browser.find_element_by_id('password')
        loginButton = self.browser.find_element_by_css_selector(
            "body > main > div > section > div > div.border.col-lg-4.d-flex.justify-content-center.bg-white > form > button")
        self.assertIn('sign in', loginButton.text.lower())

    def step_4_click_homepage_button(self):
        homePageButton = self.browser.find_element_by_css_selector(
            "body > header > nav > div > a")
        homePageButton.click()

    def step_5_click_signup_button(self):
        signUpButton = self.browser.find_element_by_css_selector(
            "body > main > section.bg-white.py-5.my-5.text-dark.home-cta-register > div > div > div > p > a")
        self.assertIn('register now', signUpButton.text.lower())
        signUpButton.click()

    def step_6_verify_signup_page(self):
        self.assertIn('medical center registration',
                      self.browser.page_source.lower())

if __name__ == '__main__':
    unittest.main(verbosity=2)