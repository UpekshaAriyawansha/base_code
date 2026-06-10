<?php

return [

    'secret' =>

        $_ENV['JWT_SECRET']
        ?? '',

    'ttl' =>

        $_ENV['JWT_TTL']
        ?? 3600

];