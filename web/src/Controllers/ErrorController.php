<?php declare(strict_types=1);

namespace Pulse\Controllers;

class ErrorController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function error404()
    {
        $this->renderWithNoContext('errors/404.html.twig', null);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function error401()
    {
        $this->renderWithNoContext('errors/401.html.twig', null);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function error405()
    {
        $this->renderWithNoContext('errors/405.html.twig', null);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function error500()
    {
        $this->renderWithNoContext('errors/500.html.twig', null);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function errorUndefined()
    {
        $code = $this->httpHandler()->anyParameter('code');
        $this->render('errors/undefined.html.twig', array(
            'code' => $code
        ), null);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function errorLock()
    {
        $this->renderWithNoContext('errors/lock.html.twig', null);
    }
}