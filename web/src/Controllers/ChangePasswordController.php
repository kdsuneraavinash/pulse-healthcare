<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\Credentials;
use Pulse\Models\Exceptions\AccountAlreadyExistsException;
use Pulse\Models\Exceptions\AccountNotExistException;

class ChangePasswordController extends BaseController
{
    /**
     * @param Account|null $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get(?Account $currentAccount)
    {
        $this->renderWithNoContext("ChangePassword.twig", $currentAccount);
    }

    public function post(?Account $currentAccount)
    {
        $prevPassword = $this->httpHandler()->postParameter("prev");
        $newPassword = $this->httpHandler()->postParameter("new");
        $retypePassword = $this->httpHandler()->postParameter("retype");

        if ($newPassword != null and $newPassword != "" and $newPassword == $retypePassword) {
            try {
                $isCorrect = Credentials::isPasswordCorrectOfUser($currentAccount->getAccountId(), $prevPassword);
                if (!$isCorrect) {
                    $msg = "Current Password Incorrect";
                    $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/changepsw?error=$msg");
                    return;
                }
                Credentials::setNewPasswordOfUser($currentAccount->getAccountId(), $newPassword);
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/profile");
            } catch (AccountNotExistException $e) {
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]");
            } catch (AccountAlreadyExistsException $e) {
                $this->redirectToErrorPage(405);
            }
        } else {
            $msg = "Retype Password Mismatch";
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/changepsw?error=$msg");
        }

    }
}