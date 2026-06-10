<?php

require_once __DIR__ . '/../bootstrap/app.php';

use Src\Presentation\Http\Request;
use Src\Presentation\Routing\Router;
use Src\Presentation\Http\Response;

use Src\Presentation\Middleware\AuthMiddleware;
use Src\Presentation\Middleware\RoleMiddleware;

use Src\Infrastructure\Cache\CacheManager;

use Modules\User\Presentation\Controllers\AuthController;
use Modules\User\Presentation\Controllers\UserController;
use Modules\User\Presentation\Controllers\UploadController;
use Modules\Role\Presentation\Controllers\RoleController;

use Modules\User\Domain\Models\User;

use Modules\Permission\Presentation\Controllers\PermissionController;

use Src\Presentation\Middleware\CorsMiddleware;

use Modules\Setting\Presentation\Controllers\SettingController;

use Modules\Email\Presentation\Controllers\EmailController;

CorsMiddleware::handle();

$request = new Request();
$router = new Router();

$container = $GLOBALS['container'];

$authController = $container->make(AuthController::class);
$userController = $container->make(UserController::class);
$uploadController = $container->make(UploadController::class);
$emailController =$container->make(EmailController::class);

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

$router->post(
    '/api/auth/login',
    [$authController, 'login']
);

/*
|--------------------------------------------------------------------------
| Current User
|--------------------------------------------------------------------------
*/

$router->get(
    '/api/me',
    function () {
        Response::json([
            'user' => $_SERVER['user']
        ]);
    },
    [
        AuthMiddleware::class
    ]
);


$router->post(
    '/api/auth/logout',
    [$authController, 'logout'],
    [AuthMiddleware::class]
);

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/api/users',
    [$userController, 'index'],
    [
        AuthMiddleware::class
    ]
);

$router->post(
    '/api/users',
    [$userController, 'create'],
    [
        AuthMiddleware::class
    ]
);

$router->get(
    '/api/users/{id}',
    [$userController, 'show'],
    [
        AuthMiddleware::class
    ]
);

$router->put(
    '/api/users/{id}',
    [$userController, 'update'],
    [
        AuthMiddleware::class
    ]
);

$router->delete(
    '/api/users/{id}',
    [$userController, 'delete'],
    [
        AuthMiddleware::class
    ]
);

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/api/admin',
    function () {
        Response::json([
            'message' => 'Welcome Admin'
        ]);
    },
    [
        AuthMiddleware::class,
        [
            RoleMiddleware::class,
            ['admin']
        ]
    ]
);

/*
|--------------------------------------------------------------------------
| Upload Routes
|--------------------------------------------------------------------------
*/

$router->post(
    '/api/upload',
    [$uploadController, 'upload'],
    [
        AuthMiddleware::class
    ]
);

/*
|--------------------------------------------------------------------------
| Cache Test
|--------------------------------------------------------------------------
*/

$router->get(
    '/api/cache-test',
    function () {

        CacheManager::driver()->put(
            'name',
            'Upeksha',
            5
        );

        $value = CacheManager::driver()->get('name');

        Response::json([
            'cache' => $value
        ]);
    }
);

$router->get(
    '/api/relation-test',
    function () {

        $user = User::find(1);

        Response::json([
            'user' => $user,
            'role' => $user->role()
        ]);
    }
);

$router->get(
    '/api/container-test',
    function () use ($container) {

        $controller = $container->get(
            \Modules\User\Presentation\Controllers\UserController::class
        );

        echo json_encode([
            'success' => true,
            'controller' => get_class($controller)
        ]);
    }
);

/*
|--------------------------------------------------------------------------
| Role Routes
|--------------------------------------------------------------------------
*/

$roleController = $container->make(RoleController::class);

$router->get(
    '/api/roles',
    [$roleController, 'index'],
    [
        AuthMiddleware::class
    ]
);

$router->get(
    '/api/roles/{id}',
    [$roleController, 'show'],
    [
        AuthMiddleware::class
    ]
);

$router->post(
    '/api/roles',
    [$roleController, 'create'],
    [
        AuthMiddleware::class
    ]
);

$router->put(
    '/api/roles/{id}',
    [$roleController, 'update'],
    [
        AuthMiddleware::class
    ]
);

$router->delete(
    '/api/roles/{id}',
    [$roleController, 'delete'],
    [
        AuthMiddleware::class
    ]
);

/*
|--------------------------------------------------------------------------
| Permission Routes
|--------------------------------------------------------------------------
*/

$permissionController = $container->make(
    PermissionController::class
);

$router->get(
    '/api/permissions',
    [$permissionController, 'index'],
    [
        AuthMiddleware::class
    ]
);

$router->post(
    '/api/permissions',
    [$permissionController, 'create'],
    [
        AuthMiddleware::class
    ]
);

$router->put(
    '/api/permissions/{id}',
    [$permissionController, 'update'],
    [
        AuthMiddleware::class
    ]
);

$router->delete(
    '/api/permissions/{id}',
    [$permissionController, 'delete'],
    [
        AuthMiddleware::class
    ]
);

/*
|--------------------------------------------------------------------------
| User / Role Assignment
|--------------------------------------------------------------------------
*/

$router->post(
    '/api/users/{id}/roles',
    [$userController, 'assignRole'],
    [
        AuthMiddleware::class
    ]
);

$router->post(
    '/api/roles/{id}/permissions',
    [$roleController, 'assignPermission'],
    [
        AuthMiddleware::class
    ]
);




/*
|--------------------------------------------------------------------------
| Settings
|--------------------------------------------------------------------------
*/

$settingController = $container->make(
    SettingController::class
);


$router->get(
    '/api/settings/general',
    [$settingController, 'general'],
    [
        AuthMiddleware::class
    ]
);

$router->post(
    '/api/settings/general',
    [$settingController, 'save'],
    [
        AuthMiddleware::class
    ]
);


/*
|--------------------------------------------------------------------------
| Insights
|--------------------------------------------------------------------------
*/

$insightController = $container->make(
    \Modules\Insight\Presentation\Controllers\InsightController::class
);

$router->get(
    '/api/insights',
    [$insightController, 'index'],
    [
        AuthMiddleware::class
    ]
);





/*
|--------------------------------------------------------------------------
| Email 
|--------------------------------------------------------------------------
*/

$router->get(
    '/api/email-setup',
    function () {

        $controller =
            new \Modules\Email\Presentation\Controllers\EmailController(
                new \Modules\Email\Application\Services\EmailService(
                    new \Modules\Email\Infrastructure\Persistence\EmailRepository(),
                    new \Modules\Email\Infrastructure\Persistence\EmailLogRepository()
                )
            );

        $controller->email();
    }
);


$router->post(
    '/api/email-setup',
    function () {

        $controller =
            new \Modules\Email\Presentation\Controllers\EmailController(
                new \Modules\Email\Application\Services\EmailService(
                    new \Modules\Email\Infrastructure\Persistence\EmailRepository(),
                    new \Modules\Email\Infrastructure\Persistence\EmailLogRepository()
                )
            );

        $controller->saveEmail();
    }
);


$router->post(
    '/api/email-setup/test',
    function () {

        $controller =
            new \Modules\Email\Presentation\Controllers\EmailController(
                new \Modules\Email\Application\Services\EmailService(
                    new \Modules\Email\Infrastructure\Persistence\EmailRepository(),
                    new \Modules\Email\Infrastructure\Persistence\EmailLogRepository()
                )
            );

        $controller->test();
    }
);


$router->get(
    '/api/email-logs',
    function () {

        $repo = new \Modules\Email\Infrastructure\Persistence\EmailLogRepository();

        \Src\Presentation\Http\Response::json([
            'success' => true,
            'data' => $repo->all()
        ]);
    }
);


/*
|--------------------------------------------------------------------------
| Audit Logs
|--------------------------------------------------------------------------
*/
$auditController = $container->make(
    \Modules\Audit\Presentation\Controllers\AuditLogController::class
);

$router->get(
    '/api/audit-logs',
    [$auditController, 'index'],
    [AuthMiddleware::class]
);

$router->get(
    '/api/audit-logs/export',
    [$auditController, 'export'],
    [AuthMiddleware::class]
);

/*
|--------------------------------------------------------------------------
| Dispatch Request
|--------------------------------------------------------------------------
*/

$router->dispatch(
    $request->method(),
    $request->uri()
);