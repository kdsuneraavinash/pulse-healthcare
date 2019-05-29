import monolithic
import time
import unittest
from selenium.common.exceptions import NoSuchElementException


class TestMedicalCenterRegistrationUnverified(monolithic.MonolithicTest):
    def _check_if_in_page_even_after_submit(self):
        self.signInButton.click()
        time.sleep(1)
        self.assertCurrentUrl('http://localhost:8000/login')

    def step_01_go_to_login(self):
        self.browser.get('http://localhost:8000/login')
        self.assertBrowserTitle('medikit')

    def step_02_verify_signup_page(self):
        self.assertIn('sign in',
                      self.browser.page_source.lower())

    def step_03_find_all_fields(self):
        self.account = self.browser.find_element_by_id('account')
        self.password = self.browser.find_element_by_id('password')
        self.signInButton = self.browser.find_element_by_id(
            "login_page_sign_in")

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
        self.assertCurrentUrl('http://localhost:8000/control/med_center')

    def step_09_register_patient_locked(self):
        self.assertPanelLocked("register/doctor", True)

    def step_10_register_doctors_locked(self):
        self.assertPanelLocked("register/patient", True)

    def step_11_search_doctors_unlocked(self):
        self.assertPanelLocked("search/doctor", False)

    def step_12_search_patients_unlocked(self):
        self.assertPanelLocked("search/patient", False)

    def step_13_logout(self):
        self.browser.find_element_by_id('profileNav').click()
        logout_button = self.browser.find_element_by_id("profile_logout")
        logout_button.click()
        time.sleep(2)
        self.assertCurrentUrl('http://localhost:8000')


if __name__ == '__main__':
    unittest.main(verbosity=2)
