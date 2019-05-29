import unittest
import time
from selenium import webdriver


class MonolithicTest(unittest.TestCase):

    def _steps(self):
        for name in dir(self):
            if name.startswith("step"):
                yield name, getattr(self, name)

    def assertBrowserTitle(self, expected):
        self.assertIn(expected, self.browser.title.lower())

    def assertCurrentUrl(self, expected):
        self.assertIn(expected, self.browser.current_url.lower())

    def assertElementText(self, expected, element):
        self.assertIn(expected, element.text.lower())

    def assertPanelLocked(self, button_id, is_locked):
        locked = True
        button = self.browser.find_element_by_id(button_id)
        button.click()
        time.sleep(1)
        self.browser.switch_to.frame(
            self.browser.find_element_by_id('content-iframe')
        )

        try:
            # If error then Error element does not exist -> Unlocked
            self.browser.find_element_by_id("unverified_text")
        except:
            self.browser.switch_to.default_content()
            locked = False

        self.browser.switch_to.default_content()
        self.assertEqual(is_locked, locked)

    def test_steps(self):
        print()
        for name, step in self._steps():
            try:
                test_name = " ".join(name.split('_')[2:])
                print("Running test: {}".format(test_name))
                step()
                time.sleep(1)
            except Exception as e:
                self.fail("{} failed ({}: {})".format(step, type(e), e))

    def setUp(self):
        self.browser = webdriver.Chrome()
        self.browser.maximize_window()
        time.sleep(1)
        self.addCleanup(self.browser.quit)
