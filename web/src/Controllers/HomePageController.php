<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\AccountSession\Account;

class HomePageController extends BaseController
{
    /**
     * @param Account|null $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get(?Account $currentAccount)
    {
        $this->renderWithNoContext('HomePage.twig',  $currentAccount);
    }
}