<?php

namespace Src\Infrastructure\Queue;

class QueueManager
{
    public static function driver():
        QueueInterface
    {

        $driver =
            $_ENV['QUEUE_DRIVER']
            ?? 'file';

        return match ($driver) {

            'file' =>
                new FileQueue(),

            default =>
                new FileQueue()
        };
    }
}