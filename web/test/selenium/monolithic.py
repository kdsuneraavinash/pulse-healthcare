import unittest
import time
from selenium import webdriver

class MonolithicTest(unittest.TestCase):
    def _steps(self):
        for name in dir(self):
            if name.startswith("step"):
                yield name, getattr(self, name)

    def test_steps(self):
        for _, step in self._steps():
            try:
                step()
                time.sleep(1)
            except Exception as e:
                self.fail("{} failed ({}: {})".format(step, type(e), e))

    def setUp(self):
        self.browser = webdriver.Chrome()
        self.addCleanup(self.browser.quit)
