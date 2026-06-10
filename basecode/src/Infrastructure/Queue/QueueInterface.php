<?php

namespace Src\Infrastructure\Queue;

interface QueueInterface
{
    public function push(
        string $job,
        array $payload
    ): void;
}