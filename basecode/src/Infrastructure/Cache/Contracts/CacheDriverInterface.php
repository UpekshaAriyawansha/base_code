<?php

namespace Src\Infrastructure\Cache\Contracts;

interface CacheDriverInterface
{
    public function get(
        string $key
    ): mixed;

    public function put(
        string $key,
        mixed $value,
        int $ttl = 3600
    ): void;

    public function forget(
        string $key
    ): void;

    public function has(
        string $key
    ): bool;
}