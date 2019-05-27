<?php declare(strict_types=1);

namespace Pulse\Components\Router;

use Pulse\Components\HttpHandler;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Exceptions\AccountNotExistException;
use Pulse\Models\Exceptions\InvalidDataException;
use Pulse\Models\Exceptions\AccountRejectedException;

class AuthenticationProxy implements ICallback
{
    private $controller;
    private $action;
    private $authenticatedUsers;

    public function __construct(string $controller, string $action, array $authenticatedUsers)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->authenticatedUsers = $authenticatedUsers;
    }

    public function execute()
    {
        $callback = new Callback($this->controller, $this->action);
        $currentAccount = $this->getCurrentAccount();

        if (
            count($this->authenticatedUsers) == 0 || // Anyone can access
            (
                $currentAccount != null && // If anyone can't access, has to be logged in
                in_array($currentAccount->getAccountType(), $this->authenticatedUsers) // Otherwise account has to be authenticated
            )
        ) {
            $callback->setCurrentAccount($currentAccount);
            $callback->execute();
            return;
        }
        HttpHandler::getInstance()->redirect("http://$_SERVER[HTTP_HOST]/401");
    }

    /**
     * @return Account|null
     */
    protected function getCurrentAccount(): ?Account
    {
        try {
            $session = LoginService::continueSession();
            if ($session != null) {
                return $session->getSessionAccount();
            } else {
                return null;
            }
        } catch (AccountNotExistException $e) {
            LoginService::signOutSession();
            return null;
        } catch (InvalidDataException $e) {
            LoginService::signOutSession();
            return null;
        } catch (AccountRejectedException $e) {
            LoginService::signOutSession();
            return null;
        }
    }
}