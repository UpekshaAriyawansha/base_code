<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Infrastructure/Support/helpers.php';

use Dotenv\Dotenv;
use Src\Core\Container\Container;
use Src\Core\Exceptions\Handler;
use Src\Infrastructure\Events\EventDispatcher;

use Modules\User\Application\Events\UserCreatedEvent;
use Modules\User\Application\Listeners\SendWelcomeEmailListener;

use Src\Providers\AppServiceProvider;
use Src\Providers\EventServiceProvider;
use Src\Support\Config;
use Src\Core\Providers\ProviderRepository;

/*
|--------------------------------------------------------------------------
| AUDIT EVENTS
|--------------------------------------------------------------------------
*/

use Modules\Audit\Application\Events\UserCreatedAuditEvent;
use Modules\Audit\Application\Events\UserUpdatedAuditEvent;
use Modules\Audit\Application\Events\UserDeletedAuditEvent;
use Modules\Audit\Application\Events\UserLoggedInAuditEvent;
use Modules\Audit\Application\Events\UserLoggedOutAuditEvent;
use Modules\Audit\Application\Events\RoleCreatedAuditEvent;
use Modules\Audit\Application\Events\RoleUpdatedAuditEvent;
use Modules\Audit\Application\Events\RoleDeletedAuditEvent;
use Modules\Audit\Application\Events\RoleAssignedAuditEvent;
use Modules\Audit\Application\Events\PermissionAssignedAuditEvent;

/*
|--------------------------------------------------------------------------
| AUDIT LISTENERS
|--------------------------------------------------------------------------
*/

use Modules\Audit\Application\Listeners\UserCreatedAuditListener;
use Modules\Audit\Application\Listeners\UserUpdatedAuditListener;
use Modules\Audit\Application\Listeners\UserDeletedAuditListener;
use Modules\Audit\Application\Listeners\LoginAuditListener;
use Modules\Audit\Application\Listeners\LogoutAuditListener;
use Modules\Audit\Application\Listeners\RoleCreatedAuditListener;
use Modules\Audit\Application\Listeners\RoleUpdatedAuditListener;
use Modules\Audit\Application\Listeners\RoleDeletedAuditListener;
use Modules\Audit\Application\Listeners\RoleAssignedAuditListener;
use Modules\Audit\Application\Listeners\PermissionAssignedAuditListener;

use Modules\Audit\Application\Events\SettingsAuditEvent;
use Modules\Audit\Application\Listeners\SettingsAuditListener;

$cors = new \Src\Infrastructure\Http\Middleware\CorsMiddleware();
$cors->handle();

/*
|--------------------------------------------------------------------------
| ENV
|--------------------------------------------------------------------------
*/

$dotenv = Dotenv::createImmutable(
    __DIR__ . '/../'
);

$dotenv->load();

Config::load();

/*
|--------------------------------------------------------------------------
| CONTAINER
|--------------------------------------------------------------------------
*/

$container = new Container();

$providers =
    config('providers.providers');

$repository =
    new ProviderRepository(
        $container
    );

$repository->load(
    $providers
);

/*
|--------------------------------------------------------------------------
| REGISTER PROVIDERS
|--------------------------------------------------------------------------
*/

foreach ([
    AppServiceProvider::class,
    EventServiceProvider::class
] as $provider) {

    (new $provider(
        $container
    ))->register();
}

$GLOBALS['container'] =
    $container;

/*
|--------------------------------------------------------------------------
| ERROR HANDLER
|--------------------------------------------------------------------------
*/

Handler::register();

/*
|--------------------------------------------------------------------------
| EVENT DISPATCHER
|--------------------------------------------------------------------------
*/

// $events =
//     new EventDispatcher();

$events =
    $container->make(
        EventDispatcher::class
    );

    error_log(
    'BOOTSTRAP EVENT: ' .
    spl_object_id($events)
);
/*
|--------------------------------------------------------------------------
| USER EVENTS
|--------------------------------------------------------------------------
*/

$events->listen(
    UserCreatedEvent::class,
    [
        new SendWelcomeEmailListener(),
        'handle'
    ]
);

/*
|--------------------------------------------------------------------------
| USER AUDIT EVENTS
|--------------------------------------------------------------------------
*/

$events->listen(
    UserCreatedAuditEvent::class,
    [
        new UserCreatedAuditListener(),
        'handle'
    ]
);

$events->listen(
    UserUpdatedAuditEvent::class,
    [
        new UserUpdatedAuditListener(),
        'handle'
    ]
);

$events->listen(
    UserDeletedAuditEvent::class,
    [
        new UserDeletedAuditListener(),
        'handle'
    ]
);

$events->listen(
    UserLoggedInAuditEvent::class,
    [
        new LoginAuditListener(),
        'handle'
    ]
);

$events->listen(
    UserLoggedOutAuditEvent::class,
    [
        new LogoutAuditListener(),
        'handle'
    ]
);

/*
|--------------------------------------------------------------------------
| ROLE AUDIT EVENTS
|--------------------------------------------------------------------------
*/

$events->listen(
    RoleCreatedAuditEvent::class,
    [
        new RoleCreatedAuditListener(),
        'handle'
    ]
);

$events->listen(
    RoleUpdatedAuditEvent::class,
    [
        new RoleUpdatedAuditListener(),
        'handle'
    ]
);

$events->listen(
    RoleDeletedAuditEvent::class,
    [
        new RoleDeletedAuditListener(),
        'handle'
    ]
);

$events->listen(
    RoleAssignedAuditEvent::class,
    [
        new RoleAssignedAuditListener(),
        'handle'
    ]
);

$events->listen(
    PermissionAssignedAuditEvent::class,
    [
        new PermissionAssignedAuditListener(),
        'handle'
    ]
);



$events->listen(
    SettingsAuditEvent::class,
    [
        $container->make(SettingsAuditListener::class),
        'handle'
    ]
);

/*
|--------------------------------------------------------------------------
| GLOBAL EVENTS
|--------------------------------------------------------------------------
*/

$GLOBALS['events'] =
    $events;



// require_once __DIR__ . '/../vendor/autoload.php';

// require_once __DIR__ . '/../src/Infrastructure/Support/helpers.php';

// use Dotenv\Dotenv;
// use Src\Core\Container\Container;
// use Src\Core\Exceptions\Handler;
// use Src\Infrastructure\Events\EventDispatcher;
// use Modules\User\Application\Events\UserCreatedEvent;
// use Modules\User\Application\Listeners\SendWelcomeEmailListener;
// use Src\Providers\AppServiceProvider;
// use Src\Providers\EventServiceProvider;
// use Src\Support\Config;
// use Src\Core\Providers\ProviderRepository;


// $dotenv = Dotenv::createImmutable(
//     __DIR__ . '/../'
// );

// $dotenv->load();

// Config::load();

// $container =
//     new Container();

// // $container->test();

// $providers =
//     config('providers.providers');

// $repository =
//     new ProviderRepository(
//         $container
//     );

// $repository->load(
//     $providers
// );

// /*
// |--------------------------------------------------------------------------
// | Register Providers
// |--------------------------------------------------------------------------
// */

// $providers = [

//     AppServiceProvider::class,

//     EventServiceProvider::class

// ];

// foreach ($providers as $provider) {

//     (new $provider(
//         $container
//     ))->register();
// }


// $GLOBALS['container'] =
//     $container;


// Handler::register();


// $events =
//     new EventDispatcher();

// $events->listen(

//     UserCreatedEvent::class,

//     [
//         new SendWelcomeEmailListener(),
//         'handle'
//     ]

// );

// $GLOBALS['events'] =
//     $events;
