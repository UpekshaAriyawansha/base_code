<?php

namespace Src\Infrastructure\Cache\Drivers;

use Redis;

use Src\Infrastructure\Cache\Contracts\CacheDriverInterface;

class RedisCacheDriver
implements CacheDriverInterface
{
    private Redis $redis;

    public function __construct()
    {
        $this->redis =
            new Redis();

        $this->redis->connect(

            config('redis.host'),

            config('redis.port')

        );
    }

    public function get(
        string $key
    ): mixed {

        $value =
            $this->redis->get($key);

        return $value
            ? unserialize($value)
            : null;
    }

    public function put(
        string $key,
        mixed $value,
        int $ttl = 3600
    ): void {

        $this->redis->setex(

            $key,

            $ttl,

            serialize($value)

        );
    }

    public function forget(
        string $key
    ): void {

        $this->redis->del($key);
    }

    public function has(
        string $key
    ): bool {

        return $this->redis->exists(
            $key
        );
    }
}