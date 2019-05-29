import monolithic
import time
import unittest
from selenium.common.exceptions import NoSuchElementException


class TestMedicalCenterRegistration(monolithic.MonolithicTest):
    def _check_if_in_page_even_after_submit(self):
        self.signUpButton.click()
        time.sleep(0.5)
        self.assertCurrentUrl('http://localhost:8000/register/medi')

    def step_01_go_to_registerpage(self):
        self.browser.get('http://localhost:8000/register/medi')
        self.assertBrowserTitle('medi center registration')

    def step_02_verify_signup_page(self):
        self.assertIn('medical center registration',
                      self.browser.page_source.lower())

    def step_03_find_all_fields(self):
        self.account = self.browser.find_element_by_id('account')
        self.password = self.browser.find_element_by_id('password')
        self.password_retype = self.browser.find_element_by_id(
            'password_retype')
        self.name = self.browser.find_element_by_id('name')
        self.phsrc = self.browser.find_element_by_id('phsrc')
        self.email = self.browser.find_element_by_id('email')
        self.fax = self.browser.find_element_by_id('fax')
        self.phone_number = self.browser.find_element_by_id('phone_number')
        self.address = self.browser.find_element_by_id('address')
        self.postal = self.browser.find_element_by_id('postal')
        self.signUpButton = self.browser.find_element_by_id("med_center_register_button")

    def step_04_submit_without_data(self):
        self._check_if_in_page_even_after_submit()

    def step_05_fill_data(self):
        self.account.send_keys('selenium_medcenter_tester')
        self.password.send_keys('selenium_password')
        self.password_retype.send_keys('selenium_password')
        self.name.send_keys('Selenium Tester')
        self.phsrc.send_keys('PHSRC/SELENIUM/001')
        self.email.send_keys('selenium_medcenter_tester@test.com')
        self.fax.send_keys('00110011000')
        self.phone_number.send_keys('0112233445')
        self.address.send_keys('Selenium, Selenium Tester, Selenium.')
        self.postal.send_keys('400')

    def step_06_clear_account_and_submit(self):
        self.account.clear()
        self._check_if_in_page_even_after_submit()
        self.account.send_keys('selenium_medcenter_tester')

    def step_07_change_password_retype_and_submit(self):
        self.password_retype.clear()
        self.password_retype.send_keys('fake_password')
        self._check_if_in_page_even_after_submit()
        self.password_retype.clear()
        self.password_retype.send_keys('selenium_password')

    def step_08_type_invalid_phsrc_and_submit(self):
        self.phsrc.clear()
        self.phsrc.send_keys('INVALID/SELENIUM/001')
        self._check_if_in_page_even_after_submit()
        self.phsrc.clear()
        self.phsrc.send_keys('PHSRC/fake/001')
        self._check_if_in_page_even_after_submit()
        self.phsrc.clear()
        self.phsrc.send_keys('INVALID/TEST/fake')
        self._check_if_in_page_even_after_submit()
        self.phsrc.clear()
        self.phsrc.send_keys('PHSRC/SELENIUM/001')

    def step_09_type_invalid_email_and_submit(self):
        self.email.clear()
        self.email.send_keys('fake')
        self._check_if_in_page_even_after_submit()
        self.email.clear()
        self.email.send_keys('fake@fake')
        self._check_if_in_page_even_after_submit()
        self.email.clear()
        self.email.send_keys('selenium_medcenter_tester@test.com')

    def step_10_type_invalid_phonenumber_and_submit(self):
        self.phone_number.clear()
        self.phone_number.send_keys('fake')
        self._check_if_in_page_even_after_submit()
        self.phone_number.clear()
        self.phone_number.send_keys('01234567')
        self._check_if_in_page_even_after_submit()
        self.phone_number.clear()
        self.phone_number.send_keys('0112233445')

    def step_11_submit_and_check_if_redirected(self):
        self.signUpButton.click()
        time.sleep(2)
        self.assertCurrentUrl('http://localhost:8000/profile')
        profile_name = self.browser.find_element_by_id("profile_name")
        self.assertIn('selenium tester', profile_name.text.lower())

    def step_12_go_to_control_panel(self):
        self.browser.find_element_by_id('profileNav').click()
        control_panel_button = self.browser.find_element_by_id("control_panel_link")
        control_panel_button.click()
        time.sleep(2)
        self.assertCurrentUrl('http://localhost:8000/control/med_center')

    def step_13_register_patient_locked(self):
        self.assertPanelLocked("register/doctor", True)

    def step_14_register_doctors_locked(self):
        self.assertPanelLocked("register/patient", True)

    def step_15_search_doctors_unlocked(self):
        self.assertPanelLocked("search/doctor", False)

    def step_16_search_patients_unlocked(self):
        self.assertPanelLocked("search/patient", False)

    def step_17_logout(self):
        self.browser.find_element_by_id('profileNav').click()
        logout_button = self.browser.find_element_by_id("profile_logout")
        logout_button.click()
        time.sleep(2)
        self.assertCurrentUrl('http://localhost:8000')


if __name__ == '__main__':
    unittest.main(verbosity=2)
