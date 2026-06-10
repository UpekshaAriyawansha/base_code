<?php

namespace Src\Infrastructure\Cache;

class FileCache implements CacheInterface
{
    private string $path;

    public function __construct()
    {
        $this->path =
            __DIR__ .
            '/../../../storage/cache/';

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
    )
    {
        $file =
            $this->path .
            md5($key);

        if (!file_exists($file)) {

            return null;
        }

        $data =
            unserialize(
                file_get_contents($file)
            );

        if (
            time() > $data['expires_at']
        ) {

            unlink($file);

            return null;
        }

        return $data['value'];
    }

    public function put(
        string $key,
        mixed $value,
        int $ttl = 3600
    ): void {

        $file =
            $this->path .
            md5($key);

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
            $this->path .
            md5($key);

        if (file_exists($file)) {

            unlink($file);
        }
    }
}