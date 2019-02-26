<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\Exceptions;
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
        parent::loadOnlyIfUserIsOfType(Admin::class, 'ControlPanelAdminPage.html.twig');
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAdminDashboardIframe()
    {
        parent::loadOnlyIfUserIsOfType(Admin::class, 'iframe/AdminDashboardIFrame.htm.twig');
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
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }

    /**
     * @throws AccountRejectedException
     */
    public function postAdminVerifyMedicalCentersIframe()
    {
        $targetAccountId = $this->httpHandler()->postParameter('account');
        $action = $this->httpHandler()->postParameter('action');

        $currentAccount = $this->getCurrentAccount();
        if ($currentAccount instanceof Admin) {
            /// Current user must be ADMIN
            try {
                /// Get target user object
                $targetAccount = Account::retrieveAccount($targetAccountId, true);

            } catch (AccountNotExistException $e) {
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
                exit;
            } catch (InvalidDataException $e) {
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
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
                    $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
                }
            } else {
                /// Account is not a MedicalCenter
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
            }

            /// Exit in normal way
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/admin/verify#$targetAccountId");
        } else {
            /// Current user is not ADMIN
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }
}