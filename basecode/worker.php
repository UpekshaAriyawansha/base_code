<?php

require_once __DIR__ .
    '/vendor/autoload.php';

use Modules\User\Application\Jobs\SendWelcomeEmailJob;

$queuePath =
    __DIR__ .
    '/storage/queue/';

echo "Queue worker started...\n";

while (true) {

    $files =
        glob(
            $queuePath . '*.json'
        );

    foreach ($files as $file) {

        $job =
            json_decode(
                file_get_contents($file),
                true
            );

        switch ($job['job']) {

            case 'SendWelcomeEmailJob':

                (
                    new SendWelcomeEmailJob()
                )->handle(

                    $job['payload']

                );

                break;
        }

        unlink($file);

        echo "Processed: {$file}\n";
    }

    sleep(2);
}