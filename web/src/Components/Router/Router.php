<?php declare(strict_types=1);

namespace Pulse\Components\Router;

use Pulse\Components\HttpHandler;
use Pulse\Models\Enums\AccountList;
use Klein;


final class Router
{
    private static function getRoutes()
    {

        return [
            // ['METHOD', '/path, ['Pulse\Controllers\Controller', 'method'], ['AuthenticatedUsers']]

            // Base pages
            ['GET', '/', ['Pulse\Controllers\HomePageController', 'get'], AccountList::Anyone],
            ['GET', '/profile', ['Pulse\Controllers\ProfilePageController', 'get'], AccountList::AnyAccount],
            ['GET', '/showprofile', ['Pulse\Controllers\ProfilePageController', 'getShowProfile'], AccountList::AnyAccount],
            ['GET', '/timeline', ['Pulse\Controllers\TimelineController', 'get'], AccountList::DoctorAndPatient],
            ['GET', '/changepsw', ['Pulse\Controllers\ChangePasswordController', 'get'], AccountList::AnyAccount],
            ['POST', '/changepsw', ['Pulse\Controllers\ChangePasswordController', 'post'], AccountList::AnyAccount],


            // Login handlers
            ['GET', '/login', ['Pulse\Controllers\LoginController', 'get'], AccountList::Anyone],
            ['POST', '/login', ['Pulse\Controllers\LoginController', 'post'], AccountList::Anyone],
            ['POST', '/logout', ['Pulse\Controllers\LogoutController', 'post'], AccountList::Anyone],

            // Registering Pages
            ['GET', '/register/medi', ['Pulse\Controllers\MedicalCenterRegistrationController', 'get'], AccountList::Anyone],
            ['POST', '/register/medi', ['Pulse\Controllers\MedicalCenterRegistrationController', 'post'], AccountList::Anyone],

            // Control Panel - Admin
            ['GET', '/control/admin', ['Pulse\Controllers\AdminControlPanelController', 'get'], AccountList::AdminOnly],
            ['GET', '/control/admin/dashboard', ['Pulse\Controllers\AdminControlPanelController', 'getAdminDashboardIframe'], AccountList::AdminOnly],
            ['GET', '/control/admin/verify', ['Pulse\Controllers\AdminControlPanelController', 'getAdminVerifyMedicalCentersIframe'], AccountList::AdminOnly],
            ['POST', '/control/admin/verify', ['Pulse\Controllers\AdminControlPanelController', 'postAdminVerifyMedicalCentersIframe'], AccountList::AdminOnly],


            // Control Panel - Doctor
            ['GET', '/control/doctor', ['Pulse\Controllers\DoctorControlPanelController', 'get'], AccountList::DoctorOnly],
            ['GET', '/control/doctor/create/search', ['Pulse\Controllers\DoctorControlPanelController', 'getDoctorCreatePrescriptionSearchPatientIframe'], AccountList::DoctorOnly],
            ['POST', '/control/doctor/create/search', ['Pulse\Controllers\DoctorCreatePrescriptionController', 'postSearchPatient'], AccountList::DoctorOnly],
            ['GET', '/control/doctor/create/prescription', ['Pulse\Controllers\DoctorControlPanelController', 'getDoctorCreatePrescriptionIframe'], AccountList::DoctorOnly],
            ['POST', '/control/doctor/create/prescription', ['Pulse\Controllers\DoctorCreatePrescriptionController', 'post'], AccountList::DoctorOnly],

            // Control Panel - Patient
            ['GET', '/control/patient', ['Pulse\Controllers\PatientControlPanelController', 'get'], AccountList::PatientOnly],
            ['GET', '/control/patient/timeline', ['Pulse\Controllers\PatientControlPanelController', 'getPatientTimelineIframe'], AccountList::PatientOnly],

            // Control Panel - Medical Center
            ['GET', '/control/med_center', ['Pulse\Controllers\MediControlPanelController', 'get'], AccountList::MedicalCenterOnly],
            ['GET', '/control/med_center/register/doctor', ['Pulse\Controllers\MediControlPanelController', 'getMediRegisterDoctorIframe'], AccountList::MedicalCenterOnly],
            ['POST', '/control/med_center/register/doctor', ['Pulse\Controllers\DoctorRegistrationController', 'post'], AccountList::MedicalCenterOnly],
            ['GET', '/control/med_center/register/patient', ['Pulse\Controllers\MediControlPanelController', 'getMediRegisterPatientIframe'], AccountList::MedicalCenterOnly],
            ['POST', '/control/med_center/register/patient', ['Pulse\Controllers\PatientRegistrationController', 'post'], AccountList::MedicalCenterOnly],

            //Search pages
            ['GET', '/control/med_center/search/doctor', ['Pulse\Controllers\SearchDoctorController', 'getIframe'], AccountList::MedicalCenterOnly],
            ['POST', '/control/med_center/search/doctor', ['Pulse\Controllers\SearchDoctorController', 'postIframe'], AccountList::MedicalCenterOnly],
            ['GET', '/control/admin/search/doctor', ['Pulse\Controllers\SearchDoctorController', 'getIframe'], AccountList::AdminOnly],
            ['POST', '/control/admin/search/doctor', ['Pulse\Controllers\SearchDoctorController', 'postIframe'], AccountList::AdminOnly],
            ['GET', '/control/admin/search/patient', ['Pulse\Controllers\SearchPatientController', 'getIframe'], AccountList::AdminOnly],
            ['POST', '/control/admin/search/patient', ['Pulse\Controllers\SearchPatientController', 'postIframe'], AccountList::AdminOnly],
            ['GET', '/control/doctor/search/patient', ['Pulse\Controllers\SearchPatientController', 'getIframe'], AccountList::PatientOnly],
            ['POST', '/control/doctor/search/patient', ['Pulse\Controllers\SearchPatientController', 'postIframe'], AccountList::PatientOnly],
            ['GET', '/control/patient/search/doctor', ['Pulse\Controllers\SearchDoctorController', 'getIframe'], AccountList::DoctorOnly],
            ['POST', '/control/patient/search/doctor', ['Pulse\Controllers\SearchDoctorController', 'postIframe'], AccountList::DoctorOnly],

            // Error Handlers
            ['GET', '/404', ['Pulse\Controllers\ErrorController', 'error404'], AccountList::Anyone],
            ['POST', '/404', ['Pulse\Controllers\ErrorController', 'error404'], AccountList::Anyone],
            ['GET', '/405', ['Pulse\Controllers\ErrorController', 'error405'], AccountList::Anyone],
            ['POST', '/405', ['Pulse\Controllers\ErrorController', 'error405'], AccountList::Anyone],
            ['GET', '/500', ['Pulse\Controllers\ErrorController', 'error500'], AccountList::Anyone],
            ['POST', '/500', ['Pulse\Controllers\ErrorController', 'error500'], AccountList::Anyone],
            ['GET', '/undefined', ['Pulse\Controllers\ErrorController', 'errorUndefined'], AccountList::Anyone],
            ['POST', '/undefined', ['Pulse\Controllers\ErrorController', 'errorUndefined'], AccountList::Anyone],
            ['GET', '/lock', ['Pulse\Controllers\ErrorController', 'errorLock'], AccountList::Anyone],
            ['POST', '/lock', ['Pulse\Controllers\ErrorController', 'errorLock'], AccountList::Anyone],

            // API
            ['GET', '/api/login', ['Pulse\Controllers\API\LoginController', 'login'], AccountList::Anyone],
            ['GET', '/api/timeline', ['Pulse\Controllers\API\TimelineController', 'timeline'], AccountList::Anyone],
            ['GET', '/api/profile', ['Pulse\Controllers\API\ProfileController', 'profile'], AccountList::Anyone],
            ['GET', '/api/logout', ['Pulse\Controllers\API\LoginController', 'logout'], AccountList::Anyone],


        ];
    }

    public static function getRouterErrorHandlers(): array
    {
        return array(
            404 => '404',
            405 => '405',
            500 => '500'
        );
    }

    public static function init()
    {
        $klein = new Klein\Klein();

        /// Get routes has 2D arrays where each row is a route
        /// and first = TYPE, second = /path, third = function response()
        $routes = self::getRoutes();
        foreach ($routes as $route) {
            // Get type (POST/GET) and path (/login)
            $type = $route[0];
            $route_path = $route[1];

            // Get proxy interface
            $proxy = new AuthenticationProxy($route[2][0], $route[2][1], $route[3]);

            // Pass reference to Klein
            $klein->respond($type, $route_path, [$proxy, 'execute']);
        }

        /// getRouterErrorHandlers() has dictionary like array where each key is a handler
        ///
        /// getRouterDefaultErrorHandler($code) will have the
        /// default response (Unhandled error)
        $klein->onHttpError(function (int $code) {
            $router_err_handlers = self::getRouterErrorHandlers();
            // If handler is supported
            if (array_key_exists($code, $router_err_handlers)) {
                HttpHandler::getInstance()->redirect("http://$_SERVER[HTTP_HOST]/$code");
            } else {
                // Default handler
                HttpHandler::getInstance()->redirect("http://$_SERVER[HTTP_HOST]/undefined?code=$code");
            }
        });

        $klein->dispatch();
    }
}