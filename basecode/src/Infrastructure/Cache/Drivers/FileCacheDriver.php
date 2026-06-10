<?php

namespace Src\Infrastructure\Cache\Drivers;

use Src\Infrastructure\Cache\Contracts\CacheDriverInterface;

class FileCacheDriver
implements CacheDriverInterface
{
    private string $path;

    public function __construct()
    {
        $this->path =
            __DIR__
            . '/../../../../storage/cache';

        if (!is_dir($this->path)) {

            mkdir(
                $this->path,
                0777,
                true
            );
        }
    }

    public function get(
        string $key
    ): mixed {

        $file =
            $this->filePath($key);

        if (!file_exists($file)) {

            return null;
        }

        $contents =
            unserialize(
                file_get_contents($file)
            );

        if (
            $contents['expires_at']
            < time()
        ) {

            unlink($file);

            return null;
        }

        return $contents['value'];
    }

    public function put(
        string $key,
        mixed $value,
        int $ttl = 3600
    ): void {

        $file =
            $this->filePath($key);

        file_put_contents(

            $file,

            serialize([

                'value' => $value,

                'expires_at' =>
                    time() + $ttl

            ])

        );
    }

    public function forget(
        string $key
    ): void {

        $file =
            $this->filePath($key);

        if (file_exists($file)) {

            unlink($file);
        }
    }

    public function has(
        string $key
    ): bool {

        return $this->get($key)
            !== null;
    }

    private function filePath(
        string $key
    ): string {

        return $this->path
            . '/'
            . md5($key)
            . '.cache';
    }
}