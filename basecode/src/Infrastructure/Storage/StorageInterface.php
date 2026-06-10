<?php

namespace Src\Infrastructure\Storage;

interface StorageInterface
{
    public function store(
        array $file
    ): string;
}