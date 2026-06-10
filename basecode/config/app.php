<?php

return [

    'name' =>

        $_ENV['APP_NAME']
        ?? 'Basecode',

    'env' =>

        $_ENV['APP_ENV']
        ?? 'local',

    'debug' =>

        filter_var(

            $_ENV['APP_DEBUG']
            ?? false,

            FILTER_VALIDATE_BOOLEAN

        ),

    'url' =>

        $_ENV['APP_URL']
        ?? ''

];