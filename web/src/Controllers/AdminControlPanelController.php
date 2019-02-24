<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\AccountRejectedException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\Admin\Admin;
use Pulse\Models\MedicalCenter\MedicalCenter;

class AdminControlPanelController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        parent::loadOnlyIfUserIsOfType(Admin::class,
            'ControlPanelAdminPage.html.twig', "http://$_SERVER[HTTP_HOST]/405");
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAdminDashboardIframe()
    {
        parent::loadOnlyIfUserIsOfType(Admin::class,
            'iframe/AdminDashboardIFrame.htm.twig', "http://$_SERVER[HTTP_HOST]/404");
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAdminVerifyMedicalCentersIframe()
    {
        $currentAccount = $this->getCurrentAccount();
        if ($currentAccount instanceof Admin) {
            $this->render('iframe/AdminVerifyMedicalCentersIFrame.htm.twig', array(
                'medical_centers' => $currentAccount->retrieveMedicalCentersList()
            ), $currentAccount);
        } else {
            header("http://$_SERVER[HTTP_HOST]/404");
            exit;
        }
    }

    /**
     * @throws AccountRejectedException
     */
    public function postAdminVerifyMedicalCentersIframe()
    {
        $targetAccountId = $this->getRequest()->getBodyParameter('account');
        $action = $this->getRequest()->getBodyParameter('action');

        $currentAccount = $this->getCurrentAccount();
        if ($currentAccount instanceof Admin) {
            /// Current user must be ADMIN
            try {
                /// Get target user object
                $targetAccount = Account::retrieveAccount($targetAccountId, true);

            } catch (AccountNotExistException $e) {
                header("Location: http://$_SERVER[HTTP_HOST]/405");
                exit;
            } catch (InvalidDataException $e) {
                header("Location: http://$_SERVER[HTTP_HOST]/405");
                exit;
            }

            if ($targetAccount instanceof MedicalCenter) {
                /// Target account should be a MedicalCenter
                if ($action === 'verify') {
                    $currentAccount->verifyMedicalCenter($targetAccount);
                } else if ($action === 'retract') {
                    $currentAccount->retractMedicalCenter($targetAccount);
                } else if ($action === 'delete') {
                    $currentAccount->deleteMedicalCenter($targetAccount);
                } else if ($action === 'reject') {
                    $currentAccount->rejectMedicalCenter($targetAccount);
                } else {
                    /// Unknown method
                    header("Location: http://$_SERVER[HTTP_HOST]/405");
                    exit;
                }
            } else {
                /// Account is not a MedicalCenter
                header("Location: http://$_SERVER[HTTP_HOST]/405");
                exit;
            }

            /// Exit in normal way
            header("Location: http://$_SERVER[HTTP_HOST]/control/admin/verify#$targetAccountId");
            exit;
        } else {
            /// Current use is not ADMIN
            header("Location: http://$_SERVER[HTTP_HOST]/404");
            exit;
        }
    }
}