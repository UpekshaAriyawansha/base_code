<?php

namespace Src\Infrastructure\Queue;

class FileQueue implements QueueInterface
{
    private string $path;

    public function __construct()
    {
        $this->path =
            __DIR__ .
            '/../../../storage/queue/';

        if (!is_dir($this->path)) {

            mkdir(
                $this->path,
                0777,
                true
            );
        }
    }

    public function push(
        string $job,
        array $payload
    ): void {

        $filename =

            $this->path .

            uniqid() .

            '.json';

        file_put_contents(

            $filename,

            json_encode([

                'job' => $job,

                'payload' => $payload

            ])

        );
    }
}