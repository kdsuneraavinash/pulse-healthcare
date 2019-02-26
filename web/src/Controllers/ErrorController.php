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
        $this->render('errors/404.html.twig', array(), null);
    }
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function error405()
    {
        $this->render('errors/405.html.twig', array(), null);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function error500()
    {
        $this->render('errors/500.html.twig', array(), null);
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
}