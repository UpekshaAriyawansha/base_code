<?php

namespace Src\Infrastructure\Cache;

interface CacheInterface
{
    public function get(
        string $key
    );

    public function put(
        string $key,
        mixed $value,
        int $ttl = 3600
    ): void;

    public function forget(
        string $key
    ): void;
}