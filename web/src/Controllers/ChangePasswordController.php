<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\Credentials;
use Pulse\Models\Exceptions\AccountAlreadyExistsException;
use Pulse\Models\Exceptions\AccountNotExistException;

class ChangePasswordController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        $current_account = $this->getCurrentAccount();
        if ($current_account instanceof Account) {
            $this->render("ChangePassword.html.twig", array(), $current_account);
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]");
        }
    }

    public function post()
    {
        $current_account = $this->getCurrentAccount();
        if ($current_account instanceof Account) {
            $prevPassword = $this->httpHandler()->postParameter("prev");
            $newPassword = $this->httpHandler()->postParameter("new");
            $retypePassword = $this->httpHandler()->postParameter("retype");

            if ($newPassword != null and $newPassword != "" and $newPassword == $retypePassword) {
                try {
                    $isCorrect = Credentials::isPasswordCorrectOfUser($current_account->getAccountId(), $prevPassword);
                    if (!$isCorrect) {
                        $msg = "Current Password Incorrect";
                        $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/changepsw?error=$msg");
                        return;
                    }
                    Credentials::setNewPasswordOfUser($current_account->getAccountId(), $newPassword);
                    $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/profile");
                } catch (AccountNotExistException $e) {
                    $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]");
                } catch (AccountAlreadyExistsException $e) {
                    $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
                }
            } else {
                $msg = "Retype Password Mismatch";
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/changepsw?error=$msg");
            }
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]");
        }
    }
}