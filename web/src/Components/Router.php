<?php declare(strict_types=1);

namespace Pulse\Components;

use Klein;

final class Router
{
    private static function getRoutes()
    {
        return [
            // ['METHOD', '/path, ['Pulse\Controllers\Controller', 'method']]

            // Base pages
            ['GET', '/', ['Pulse\Controllers\HomePageController', 'get']],
            ['GET', '/profile', ['Pulse\Controllers\ProfilePageController', 'get']],
            ['GET', '/showprofile', ['Pulse\Controllers\ProfilePageController', 'getShowProfile']],

            // Login handlers
            ['GET', '/login', ['Pulse\Controllers\LoginController', 'get']],
            ['POST', '/login', ['Pulse\Controllers\LoginController', 'post']],
            ['POST', '/logout', ['Pulse\Controllers\LogoutController', 'post']],

            // Registering Pages
            ['GET', '/register/medi', ['Pulse\Controllers\MedicalCenterRegistrationController', 'get']],
            ['POST', '/register/medi', ['Pulse\Controllers\MedicalCenterRegistrationController', 'post']],

            // Control Panel - Admin
            ['GET', '/control/admin', ['Pulse\Controllers\AdminControlPanelController', 'get']],
            ['GET', '/control/admin/dashboard', ['Pulse\Controllers\AdminControlPanelController', 'getAdminDashboardIframe']],
            ['GET', '/control/admin/verify', ['Pulse\Controllers\AdminControlPanelController', 'getAdminVerifyMedicalCentersIframe']],
            ['POST', '/control/admin/verify', ['Pulse\Controllers\AdminControlPanelController', 'postAdminVerifyMedicalCentersIframe']],


            // Control Panel - Doctor
            ['GET', '/control/doctor', ['Pulse\Controllers\DoctorControlPanelController', 'get']],
            ['GET', '/control/doctor/dashboard', ['Pulse\Controllers\DoctorControlPanelController', 'getDoctorDashboardIframe']],
            ['GET','/control/doctor/create/search',['Pulse\Controllers\DoctorControlPanelController','getDoctorCreatePrescriptionSearchPatientIframe']],
            ['POST','/control/doctor/create/search',['Pulse\Controllers\DoctorCreatePrescriptionController','postSearchPatient']],
            ['GET','/control/doctor/create/prescription',['Pulse\Controllers\DoctorControlPanelController','getDoctorCreatePrescriptionIframe']],
            ['POST', '/control/doctor/create/prescription', ['Pulse\Controllers\DoctorCreatePrescriptionController', 'post']],

            // Control Panel - Medical Center
            ['GET', '/control/med_center', ['Pulse\Controllers\MediControlPanelController', 'get']],
            ['GET', '/control/med_center/dashboard', ['Pulse\Controllers\MediControlPanelController', 'getMediDashboardIframe']],
            ['GET', '/control/med_center/register/doctor', ['Pulse\Controllers\MediControlPanelController', 'getMediRegisterDoctorIframe']],
            ['POST', '/control/med_center/register/doctor', ['Pulse\Controllers\DoctorRegistrationController', 'post']],
            ['GET', '/control/med_center/register/patient', ['Pulse\Controllers\MediControlPanelController', 'getMediRegisterPatientIframe']],
            ['POST', '/control/med_center/register/patient', ['Pulse\Controllers\PatientRegistrationController', 'post']],

            //Search pages
            ['GET', '/control/med_center/search/doctor', ['Pulse\Controllers\SearchDoctorController', 'getIframe']],
            ['POST', '/control/med_center/search/doctor', ['Pulse\Controllers\SearchDoctorController', 'postIframe']],
            ['GET', '/control/admin/search/doctor', ['Pulse\Controllers\SearchDoctorController', 'getIframe']],
            ['POST', '/control/admin/search/doctor', ['Pulse\Controllers\SearchDoctorController', 'postIframe']],

            ['GET', '/control/med_center/search/patient', ['Pulse\Controllers\SearchPatientController', 'getIframe']],
            ['POST', '/control/med_center/search/patient', ['Pulse\Controllers\SearchPatientController', 'postIframe']],
            ['GET', '/control/admin/search/patient', ['Pulse\Controllers\SearchPatientController', 'getIframe']],
            ['POST', '/control/admin/search/patient', ['Pulse\Controllers\SearchPatientController', 'postIframe']],
            ['GET', '/control/doctor/search/patient', ['Pulse\Controllers\SearchPatientController', 'getIframe']],
            ['POST', '/control/doctor/search/patient', ['Pulse\Controllers\SearchPatientController', 'postIframe']],

            // Error Handlers
            ['GET', '/404', ['Pulse\Controllers\ErrorController', 'error404']],
            ['POST', '/404', ['Pulse\Controllers\ErrorController', 'error404']],
            ['GET', '/405', ['Pulse\Controllers\ErrorController', 'error405']],
            ['POST', '/405', ['Pulse\Controllers\ErrorController', 'error405']],
            ['GET', '/500', ['Pulse\Controllers\ErrorController', 'error500']],
            ['POST', '/500', ['Pulse\Controllers\ErrorController', 'error500']],
            ['GET', '/undefined', ['Pulse\Controllers\ErrorController', 'errorUndefined']],
            ['POST', '/undefined', ['Pulse\Controllers\ErrorController', 'errorUndefined']],
            ['GET', '/lock', ['Pulse\Controllers\ErrorController', 'errorLock']],
            ['POST', '/lock', ['Pulse\Controllers\ErrorController', 'errorLock']],

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

            // Get controller class method reference
            $controller = new $route[2][0]();
            $method = $route[2][1];
            $callback = [$controller, $method];

            // Pass reference to Klein
            $klein->respond($type, $route_path, $callback);
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