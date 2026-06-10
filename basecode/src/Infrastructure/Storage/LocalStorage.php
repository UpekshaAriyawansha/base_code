<?php

namespace Src\Infrastructure\Storage;

class LocalStorage
    implements StorageInterface
{
    public function store(
        array $file
    ): string {

        $extension =
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            );

        $filename =

            uniqid() .

            '.' .

            $extension;

        $destination =

            __DIR__ .

            '/../../../public/uploads/' .

            $filename;

        move_uploaded_file(

            $file['tmp_name'],

            $destination

        );

        return '/uploads/' .
            $filename;
    }
}