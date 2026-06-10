<?php

namespace Src\Presentation\Resources;

abstract class JsonResource
{
    public static function make(
        array $data
    ): array {

        return (
            new static()
        )->toArray(
            $data
        );
    }

    public static function collection(
        array $items
    ): array {

        return array_map(

            fn ($item) =>

                static::make(
                    $item
                ),

            $items

        );
    }

    abstract public function toArray(
        array $data
    ): array;
}