<?php declare(strict_types=1);

namespace Pulse;

function getRoutes()
{
    return [
        // ['METHOD', '/path, ['Pulse\Controllers\Controller', 'method']]

        // Base pages
        ['GET', '/', ['Pulse\Controllers\HomePageController', 'get']],
        ['GET', '/profile', ['Pulse\Controllers\ProfilePageController', 'get']],

        // Test pages
        ['GET', '/test', ['Pulse\Controllers\Test\TestController', 'show']],
        ['POST', '/test', ['Pulse\Controllers\Test\TestController', 'show']],

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

        // Control Panel - Medical Center
        ['GET', '/control/med_center', ['Pulse\Controllers\MediControlPanelController', 'get']],
        ['GET', '/control/med_center/dashboard', ['Pulse\Controllers\MediControlPanelController', 'getMediDashboardIframe']],
        ['GET', '/control/med_center/register/doctor', ['Pulse\Controllers\MediControlPanelController', 'getMediRegisterDoctorIframe']],
        ['POST', '/control/med_center/register/doctor', ['Pulse\Controllers\DoctorRegistrationController', 'post']],
        ['GET', '/control/med_center/register/patient', ['Pulse\Controllers\MediControlPanelController', 'getMediRegisterPatientIframe']],
        ['POST', '/control/med_center/register/patient', ['Pulse\Controllers\PatientRegistrationController', 'post']],

        // Error Handlers
        ['GET', '/404', ['Pulse\Controllers\ErrorController', 'error404']],
        ['POST', '/404', ['Pulse\Controllers\ErrorController', 'error404']],
        ['GET', '/405', ['Pulse\Controllers\ErrorController', 'error405']],
        ['POST', '/405', ['Pulse\Controllers\ErrorController', 'error405']],
        ['GET', '/500', ['Pulse\Controllers\ErrorController', 'error500']],
        ['POST', '/500', ['Pulse\Controllers\ErrorController', 'error500']],
        ['GET', '/undefined', ['Pulse\Controllers\ErrorController', 'errorUndefined']],
        ['POST', '/undefined', ['Pulse\Controllers\ErrorController', 'errorUndefined']],

    ];
}

function getRouterErrorHandlers()
{
    return [
        [404, '404'],
        [405, '405'],
        [500, '500'],
    ];
}