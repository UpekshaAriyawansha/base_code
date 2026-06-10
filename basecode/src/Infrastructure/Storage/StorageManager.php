<?php

namespace Src\Infrastructure\Storage;

class StorageManager
{
    public static function driver():
        StorageInterface
    {

        return new LocalStorage();
    }
}