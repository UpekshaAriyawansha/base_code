<?php

namespace Src\Infrastructure\Cache;

use Src\Infrastructure\Cache\Contracts\CacheDriverInterface;
use Src\Infrastructure\Cache\Drivers\FileCacheDriver;
use Src\Infrastructure\Cache\Drivers\RedisCacheDriver;

class CacheManager
{
    public static function driver():
        CacheDriverInterface {

        return match (

            config('cache.driver')

        ) {

            'redis' =>

                new RedisCacheDriver(),

            default =>

                new FileCacheDriver()

        };
    }
}