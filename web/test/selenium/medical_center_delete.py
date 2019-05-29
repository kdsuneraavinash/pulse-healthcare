import monolithic
import time
import unittest


class TestAdminLoginAndMedCenterDelete(monolithic.MonolithicTest):
    def _check_if_in_page_even_after_submit(self):
        self.signInButton.click()
        time.sleep(0.5)
        self.assertCurrentUrl('http://localhost:8000/login')

    def _select_nth_element(self, n):
        try:
            return self.browser.find_element_by_id(
                "admin_card_header_{}".format(n))
        except:
            pass
        return None

    def _click_on_admin_button(self, button, modal):
        element = object()
        i = 0
        while element != None:
            try:
                element = self.browser.find_element_by_id(
                    "admin_card_header_{}".format(i))
            except:
                break

            if 'Selenium Tester' in element.text:
                self.browser.find_element_by_id(button).click()
                time.sleep(1)
                self.browser.find_element_by_id(
                    "modal_verification_button_{}".format(modal)).click()
                return
            try:
                self.browser.find_element_by_class_name(
                    'fa-arrow-circle-right').click()
            except:
                pass
            i += 1
        self.assertTrue(False)

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

    def step_04_fill_data(self):
        self.account.send_keys('administrator')
        self.password.send_keys('password')

    def step_05_submit_and_check_if_redirected(self):
        self.signInButton.click()
        time.sleep(2)
        self.assertCurrentUrl('http://localhost:8000/control/admin')

    def step_06_goto_verify_accounts(self):
        button = self.browser.find_element_by_id('verify')
        button.click()

        time.sleep(2)
        self.browser.switch_to.frame(
            self.browser.find_element_by_id('content-iframe'))

    def step_07_reject_test_account(self):
        self._click_on_admin_button(
            "admin_reject_button_selenium_medcenter_tester", "modelRejectConfirm")

    def step_08_delete_test_account(self):
        self._click_on_admin_button(
            "admin_delete_button_selenium_medcenter_tester", "modelDeleteConfirm")

    def step_09_logout(self):
        self.browser.switch_to.default_content()
        self.browser.find_element_by_id('profileNav').click()
        logout_button = self.browser.find_element_by_id("profile_logout")
        logout_button.click()
        time.sleep(2)
        self.assertCurrentUrl('http://localhost:8000')


if __name__ == '__main__':
    unittest.main(verbosity=2)
