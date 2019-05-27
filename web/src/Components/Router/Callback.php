<?php declare(strict_types=1);

namespace Pulse\Components\Router;

use Pulse\Models\AccountSession\Account;

class Callback implements ICallback
{
    private $controller;
    private $action;
    private $currentAccount;

    public function __construct(string $controller, string $action)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->currentAccount = null;
    }

    /**
     * @param Account|null $currentAccount
     */
    public function setCurrentAccount(?Account $currentAccount): void
    {
        $this->currentAccount = $currentAccount;
    }

    public function execute()
    {
        $initiatedController = new $this->controller();
        $callback = [$initiatedController, $this->action];
        $callback($this->currentAccount);
    }
}