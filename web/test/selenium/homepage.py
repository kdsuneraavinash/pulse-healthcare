import monolithic
import time
import unittest


class TestAllPublicPages(monolithic.MonolithicTest):
    def step_1(self):
        self.browser.get('http://localhost:8000')
        self.assertIn('medikit', self.browser.title.lower())

    def step_2(self):
        signInButton = self.browser.find_element_by_css_selector(
            "#home > div > div.col-md-5.my-auto > div > div > form > button")
        self.assertIn('sign in', signInButton.text.lower())
        signInButton.click()

    def step_3(self):
        self.browser.find_element_by_id('account')
        self.browser.find_element_by_id('password')
        loginButton = self.browser.find_element_by_css_selector(
            "body > main > div > section > div > div.border.col-lg-4.d-flex.justify-content-center.bg-white > form > button")
        self.assertIn('sign in', loginButton.text.lower())

    def step_4(self):
        homePageButton = self.browser.find_element_by_css_selector(
            "body > header > nav > div > a")
        homePageButton.click()

    def step_5(self):
        signUpButton = self.browser.find_element_by_css_selector(
            "body > main > section.bg-white.py-5.my-5.text-dark.home-cta-register > div > div > div > p > a")
        self.assertIn('register now', signUpButton.text.lower())
        signUpButton.click()

    def step_6(self):
        self.assertIn('medical center registration',
                      self.browser.page_source.lower())

if __name__ == '__main__':
    unittest.main(verbosity=2)