<?php declare(strict_types=1);

namespace Pulse\Controllers\Test;

use DB;
use Pulse\BaseController;
use Pulse\Framework\Session;

class LoginController extends BaseController
{
    public function show()
    {
        $text = "";
        $post = $this->getRequest()->getBodyParameter('user');
        try {
            if ($post != null) {
                $text .= "Request given as name = $post. ";
                $user = DB::queryFirstRow("SELECT ID FROM test WHERE FirstName=%s;", $post);
                $id = $user['ID'];

                $text .= "ID = $id. ";
                if ($user != null) {
                    if (isset($_SESSION["SESSION_KEY"])) {
                        /// Logged in as another user while being logged in as one
                        /// Have to delete first login details
                        Session::closeSessionWithContext($_SESSION["SESSION_USER"], $_SESSION["SESSION_KEY"]);
                        $text .= "Deleted Previous Authentication. ";
                    }
                    /// Now login as the other
                    $session = Session::createSession($id);
                    $_SESSION["SESSION_USER"] = $id;
                    $_SESSION["SESSION_KEY"] = $session->getSessionKey();
                    $text .= "Logged in as $id. ";
                } else if (isset($_SESSION["SESSION_USER"])) {
                    /// Previously logged user asking to resume
                    $session = Session::resumeSession($_SESSION["SESSION_USER"], $_SESSION["SESSION_KEY"]);
                    if ($session == null) {
                        // Session.php expired
                        unset($_SESSION["SESSION_KEY"]);
                        unset($_SESSION["SESSION_USER"]);
                        $text .= "Session.php Expired. ";
                    } else {
                        $_SESSION["SESSION_USER"] = $id;
                        $_SESSION["SESSION_KEY"] = $session->getSessionKey();
                        $text .= "Resumed as $id. ";
                    }
                }

                /// After login redirect back to this page
                header("Location: http://$_SERVER[HTTP_HOST]/test/login");
                exit();
            } else {
                $text .= "No request. ";
                if (isset($_SESSION["SESSION_USER"])) {
                    $id = $_SESSION["SESSION_USER"];
                    /// Previously logged user asking to resume
                    $session = Session::resumeSession($_SESSION["SESSION_USER"], $_SESSION["SESSION_KEY"]);
                    if ($session == null) {
                        // Session.php expired
                        unset($_SESSION["SESSION_KEY"]);
                        unset($_SESSION["SESSION_USER"]);
                        $text .= "Session.php Expired. ";
                    } else {
                        $_SESSION["SESSION_USER"] = $id;
                        $_SESSION["SESSION_KEY"] = $session->getSessionKey();
                        $text .= "Resumed as $id. ";
                    }
                }
            }

        } catch (\Exception $e) {
            $text .= "BaseUser does not exist. ";
            unset($_SESSION["SESSION_KEY"]);
            unset($_SESSION["SESSION_USER"]);
        }


        $db_session_query = DB::query("SELECT user, ip_address, user_agent, created, expires, HEX(session_key) FROM sessions;");
        $user_agents = DB::query("SELECT id, user_agent FROM user_agents;");
        $user_agents_mapped = array();

        for($i = 0; $i<count($user_agents); $i++){
            $user_agents_mapped[$user_agents[$i]['id']]  = $user_agents[$i]['user_agent'];
        }
        $data = [
            'text' => $text,
            'session' => $_SESSION,
            'user_agents' => $user_agents_mapped,
            'db_session' => $db_session_query
        ];
        $this->render('LoginTemplate.html.twig', $data);
    }
}