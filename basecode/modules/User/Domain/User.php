<?php

namespace Modules\User\Domain;

class User
{
    public function __construct(

        public int $id,

        public string $firstName,

        public string $lastName,

        public string $email

    ) {}
}