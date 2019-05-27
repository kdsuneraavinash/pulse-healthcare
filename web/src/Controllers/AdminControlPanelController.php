<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\AccountFactory;
use Pulse\Models\Admin\Admin;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\Exceptions;

class AdminControlPanelController extends BaseController
{
    /**
     * @param Account $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get(?Account $currentAccount)
    {
        $this->renderWithNoContext('ControlPanelAdminPage.twig', $currentAccount);
    }

    /**
     * @param Admin $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAdminDashboardIframe(Admin $currentAccount)
    {
        $result = $currentAccount->generateUserTypeData();
        $this->render('iframe/AdminDashboardIFrame.twig', $result, $currentAccount);

    }

    /**
     * @param Admin $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAdminVerifyMedicalCentersIframe(Admin $currentAccount)
    {
        $this->render('iframe/AdminVerifyMedicalCentersIFrame.twig', array(
            'medical_centers' => $currentAccount->retrieveMedicalCentersList()
        ), $currentAccount);
    }

    /**
     * @param Admin $currentAccount
     * @throws Exceptions\AccountRejectedException
     */
    public function postAdminVerifyMedicalCentersIframe(Admin $currentAccount)
    {
        $targetAccountId = $this->httpHandler()->postParameter('account');
        $action = $this->httpHandler()->postParameter('action');

        try {

            /// Get target user object
            $accountFactory = new AccountFactory();
            $targetAccount = $accountFactory->getAccount($targetAccountId, true);

            if ($targetAccount instanceof MedicalCenter) {
                $currentAccount->changeMedicalCenterVerificationState($targetAccount, $action);
                /// Exit in normal way
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/admin/verify#$targetAccountId");
            }

        } catch (Exceptions\AccountNotExistException|Exceptions\InvalidDataException $e) {
            // Ignore error (to catch later)
        }
        /// Account is not a MedicalCenter or error thrown
        $this->redirectToErrorPage(405);
    }
}