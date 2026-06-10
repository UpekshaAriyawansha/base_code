<?php

require 'vendor/autoload.php';

use Modules\Email\Application\Services\EmailService;
use Modules\Email\Infrastructure\Persistence\EmailRepository;

try {

    $repository = new EmailRepository();

    $service = new EmailService(
        $repository
    );

    var_dump(
        $service->getEmailSettings()
    );

} catch (\Throwable $e) {

    echo $e->getMessage() . PHP_EOL;
    echo $e->getFile() . PHP_EOL;
    echo $e->getLine() . PHP_EOL;
}





// require __DIR__ . '/vendor/autoload.php';

// use Modules\Email\Domain\Models\EmailSetting;

// var_dump(
//     class_exists(
//         EmailSetting::class
//     )
// );