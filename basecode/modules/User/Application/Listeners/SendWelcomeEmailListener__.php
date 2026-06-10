<?php

namespace Modules\User\Application\Listeners;

use Modules\User\Application\Events\UserCreatedEvent;

class SendWelcomeEmailListener
{
    public function handle(
        UserCreatedEvent $event
    ): void {

        file_put_contents(

            __DIR__ .
            '/../../../../storage/logs/mail.log',

            "Welcome email sent to: " .

            $event->user['email'] .

            PHP_EOL,

            FILE_APPEND

        );
    }
}