<?php

namespace Src\Infrastructure\Logging;

use Monolog\Logger as MonoLogger;
use Monolog\Handler\StreamHandler;

class Logger
{
    private static ?MonoLogger $logger =
        null;

    public static function channel():
        MonoLogger {

        if (self::$logger === null) {

            $config =
                config(
                    'logging.channels.app'
                );

            self::$logger =
                new MonoLogger('app');

            self::$logger->pushHandler(

                new StreamHandler(

                    $config['path'],

                    $config['level']

                )

            );
        }

        return self::$logger;
    }
}