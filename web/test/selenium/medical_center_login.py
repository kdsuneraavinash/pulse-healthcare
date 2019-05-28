import monolithic
import time
import unittest
from selenium.common.exceptions import NoSuchElementException


class TestMedicalCenterRegistrationUnverified(monolithic.MonolithicTest):
    def _check_if_in_page_even_after_submit(self):
        self.signInButton.click()
        time.sleep(1)
        self.assertIn('http://localhost:8000/login',
                      self.browser.current_url.lower())

    def _is_panel_locked(self, idx):
        button = self.browser.find_element_by_id(idx)
        button.click()

        time.sleep(2)
        self.browser.switch_to.frame(
            self.browser.find_element_by_id('content-iframe'))

        try:
            # If error then Error element does not exist -> Unlocked
            self.browser.find_element_by_xpath(
                "/html/body/div/table/tbody/tr/td/div/div[2]/h1")
        except NoSuchElementException:
            self.browser.switch_to.default_content()
            return True
        self.browser.switch_to.default_content()
        return False

    def step_01_go_to_login(self):
        self.browser.get('http://localhost:8000/login')
        self.assertIn('medikit', self.browser.title.lower())

    def step_02_verify_signup_page(self):
        self.assertIn('sign in',
                      self.browser.page_source.lower())

    def step_03_find_all_fields(self):
        self.account = self.browser.find_element_by_id('account')
        self.password = self.browser.find_element_by_id('password')

        self.signInButton = self.browser.find_element_by_css_selector(
            "body > main > div > section > div > div.border.col-lg-4.d-flex.justify-content-center.bg-white > form > button")

    def step_04_submit_without_data(self):
        self._check_if_in_page_even_after_submit()

    def step_05_fill_data(self):
        self.account.send_keys('selenium_medcenter_tester')
        self.password.send_keys('selenium_password')

    def step_06_clear_account_and_submit(self):
        self.account.clear()
        self._check_if_in_page_even_after_submit()
        self.account.send_keys('selenium_medcenter_tester')

    def step_07_change_password_retype_and_submit(self):
        self.password.clear()
        self.password.send_keys('fake_password')
        self._check_if_in_page_even_after_submit()

        self.step_03_find_all_fields()
        self.step_05_fill_data()

    def step_08_submit_and_check_if_redirected(self):
        self.signInButton.click()
        time.sleep(2)
        self.assertNotIn('http://localhost:8000/login',
                         self.browser.current_url.lower())
        self.assertIn('http://localhost:8000/control/med_center',
                      self.browser.current_url.lower())

    def step_09_register_patient_locked(self):
        unlocked = self._is_panel_locked("register/doctor")
        self.assertFalse(unlocked)

    def step_10_register_doctors_locked(self):
        unlocked = self._is_panel_locked("register/patient")
        self.assertFalse(unlocked)

    def step_11_search_doctors_unlocked(self):
        unlocked = self._is_panel_locked("search/doctor")
        self.assertTrue(unlocked)

    def step_12_search_patients_unlocked(self):
        unlocked = self._is_panel_locked("search/patient")
        self.assertTrue(unlocked)

    def step_13_logout(self):
        self.browser.find_element_by_id('profileNav').click()

        logout_button = self.browser.find_element_by_css_selector(
            "body > header > div > div.text-center.pb-4.text-white > ul > li > div > a:nth-child(3)")
        logout_button.click()

        time.sleep(2)
        self.assertNotIn('http://localhost:8000/control/med_center',
                         self.browser.current_url.lower())
        self.assertIn('http://localhost:8000',
                      self.browser.current_url.lower())


if __name__ == '__main__':
    unittest.main(verbosity=2)
