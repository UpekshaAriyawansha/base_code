<?php

namespace Modules\User\Application\Events;

class UserCreatedEvent
{
    public array $user;

    public function __construct(
        array $user
    ) {

        $this->user = $user;
    }
}