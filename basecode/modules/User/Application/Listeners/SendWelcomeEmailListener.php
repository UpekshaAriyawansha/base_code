<?php

namespace Modules\User\Application\Listeners;

use Modules\User\Application\Events\UserCreatedEvent;
use Src\Infrastructure\Queue\QueueManager;

class SendWelcomeEmailListener
{
    public function handle(
        UserCreatedEvent $event
    ): void {

        QueueManager::driver()
            ->push(

                'SendWelcomeEmailJob',

                [

                    'email' =>
                        $event->user['email']

                ]

            );
    }
}