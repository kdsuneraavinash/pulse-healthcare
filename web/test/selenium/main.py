import public_pages
import medical_center_create
import unittest

if __name__ == "__main__":
    test_classes_to_run = [public_pages.TestAllPublicPages, medical_center_create.TestMedicalCenterRegistration]

    loader = unittest.TestLoader()

    suites_list = []
    for test_class in test_classes_to_run:
        suite = loader.loadTestsFromTestCase(test_class)
        suites_list.append(suite)

    big_suite = unittest.TestSuite(suites_list)

    runner = unittest.TextTestRunner(verbosity=2)
    results = runner.run(big_suite)