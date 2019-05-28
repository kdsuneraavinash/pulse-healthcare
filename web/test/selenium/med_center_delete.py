import monolithic
import time
import unittest
from selenium.common.exceptions import NoSuchElementException


class TestAdminLoginAndMedCenterDelete(monolithic.MonolithicTest):
    def _check_if_in_page_even_after_submit(self):
        self.signInButton.click()
        time.sleep(1)
        self.assertIn('http://localhost:8000/login',
                      self.browser.current_url.lower())

    def _select_nth_element(self, n):
        try:
            element = self.browser.find_element_by_css_selector(
                "body > main > div.row.p-4 > div > div.owl-stage-outer > div > div:nth-child({}) > div > div > div.card-header.text-center.primary-color-dark.text-white.card-header-text".format(n))
            return element
        except NoSuchElementException:
            pass
        return None

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

    def step_04_fill_data(self):
        self.account.send_keys('administrator')
        self.password.send_keys('password')

    def step_05_submit_and_check_if_redirected(self):
        self.signInButton.click()
        time.sleep(2)
        self.assertNotIn('http://localhost:8000/login',
                         self.browser.current_url.lower())
        self.assertIn('http://localhost:8000/control/admin',
                      self.browser.current_url.lower())

    def step_06_goto_verify_accounts(self):
        button = self.browser.find_element_by_id('verify')
        button.click()

        time.sleep(2)
        self.browser.switch_to.frame(
            self.browser.find_element_by_id('content-iframe'))

    def step_07_reject_test_account(self):
        element = object()
        i = 0
        while element != None:
            i += 1
            element = self._select_nth_element(i)

            if element == None:
                break
            if 'selenium_medcenter_tester' in element.text:
                reject_button = self.browser.find_element_by_css_selector(
                    "body > main > div.row.p-4 > div > div.owl-stage-outer > div > div:nth-child({}) > div > div > div.card-body > div.text-right > button.btn.btn-outline-danger.waves-effect.waves-light".format(i))
                reject_button.click()

                time.sleep(2)

                self.browser.find_element_by_css_selector(
                    '#modelRejectConfirm > div > div > div.modal-body > div > div:nth-child(4) > form > button').click()
                return
            try:
                self.browser.find_element_by_css_selector('body > main > div.row.p-4 > div > div.owl-nav > button.owl-next').click()
            except:
                pass
        self.assertTrue(False)

    def step_08_delete_test_account(self):
        element = object()
        i = 0
        while element != None:
            i += 1
            element = self._select_nth_element(i)

            if element == None:
                break
            if 'selenium_medcenter_tester' in element.text:
                delete_button = self.browser.find_element_by_css_selector(
                    "body > main > div.row.p-4 > div > div.owl-stage-outer > div > div:nth-child({}) > div > div > div.card-body > div.text-right > button.btn.btn-outline-danger.waves-effect.waves-light".format(i))
                delete_button.click()

                time.sleep(2)

                self.browser.find_element_by_css_selector(
                    '#modelDeleteConfirm > div > div > div.modal-body > div > div:nth-child(4) > form > button').click()
                return
            try:
                self.browser.find_element_by_css_selector('body > main > div.row.p-4 > div > div.owl-nav > button.owl-next').click()
            except:
                pass
        self.assertTrue(False)

    def step_09_logout(self):
        self.browser.switch_to.default_content()
        self.browser.find_element_by_id('profileNav').click()

        logout_button = self.browser.find_element_by_css_selector(
            "body > header > div > div.text-center.pb-4.text-white > ul > li > div > a:nth-child(3)")
        logout_button.click()

        time.sleep(2)
        self.assertNotIn('http://localhost:8000/control/admin',
                         self.browser.current_url.lower())
        self.assertIn('http://localhost:8000',
                      self.browser.current_url.lower())


if __name__ == '__main__':
    unittest.main(verbosity=2)
