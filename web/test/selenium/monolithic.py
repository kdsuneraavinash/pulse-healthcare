import unittest
import time
from selenium import webdriver

class MonolithicTest(unittest.TestCase):

    def _steps(self):
        for name in dir(self):
            if name.startswith("step"):
                yield name, getattr(self, name)

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

    def save_screenshot(self, name):
        # time.sleep(2)
        # file = '../../screenshots/web/{}.png'.format(name)
        # self.browser.save_screenshot(file)
        # print('Saved screenshot: {}'.format(file))
        pass
