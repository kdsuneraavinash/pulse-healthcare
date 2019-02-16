<?php declare(strict_types=1);

namespace Pulse\Controllers\Test;

use DB;
use Pulse\Controllers\BaseController;

class TestController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show()
    {
        $get_string = $this->getRequest()->getQueryParameter('key');
        $post_string = $this->getRequest()->getBodyParameter('key');
        $db_query = DB::query("SELECT * FROM test;");
        $db_session_query = DB::query("SELECT * FROM sessions;");

        $data = [
            'get' => $get_string,
            'post' => $post_string,
            'db' => $db_query,
            'site' => "http://$_SERVER[HTTP_HOST]",
            'db_session' => $db_session_query
        ];
        $this->render('TestTemplate.html.twig', $data, null);
    }
}
