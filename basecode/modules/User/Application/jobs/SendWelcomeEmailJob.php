<?php

namespace Modules\User\Application\Jobs;

class SendWelcomeEmailJob
{
    public function handle(
        array $payload
    ): void {

        file_put_contents(

            __DIR__ .
            '/../../../../storage/logs/mail.log',

            "Queued welcome email sent to: " .

            $payload['email'] .

            PHP_EOL,

            FILE_APPEND

        );
    }
}