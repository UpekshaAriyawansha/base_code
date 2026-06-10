<?php

namespace Src\Infrastructure\Database;

abstract class Model implements \JsonSerializable
{
    protected static string $table;

    protected array $attributes = [];

    public function __construct(
        array $attributes = []
    ) {
        $this->attributes = $attributes;
    }

    /*
    |--------------------------------------------------------------------------
    | Query Builder
    |--------------------------------------------------------------------------
    */

    public static function query(): QueryBuilder
    {
        return (new QueryBuilder(
            Database::connection()
        ))->table(
            static::$table
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Get All
    |--------------------------------------------------------------------------
    */

    public static function all(): array
    {
        $results =
            static::query()
                ->get();

        return array_map(
            fn ($item) => new static($item),
            $results
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Find
    |--------------------------------------------------------------------------
    */

    public static function find(
        int $id
    ): ?static {

        $result =
            static::query()
                ->find($id);

        if (!$result) {
            return null;
        }

        return new static($result);
    }

    /*
    |--------------------------------------------------------------------------
    | Where
    |--------------------------------------------------------------------------
    */

    public static function where(
        string $column,
        string $operator,
        mixed $value = null
    ): QueryBuilder {

        return static::query()
            ->where(
                $column,
                $operator,
                $value
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public static function create(
        array $data
    ): bool {

        return static::query()
            ->insert($data);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    protected function hasMany(
        string $related,
        string $foreignKey,
        string $localKey = 'id'
    ): array {

        $value = $this->{$localKey};

        return $related::query()
            ->where(
                $foreignKey,
                '=',
                $value
            )
            ->get();
    }

    protected function belongsTo(
        string $related,
        string $foreignKey,
        string $ownerKey = 'id'
    ): ?array {

        $value = $this->{$foreignKey};

        return $related::query()
            ->where(
                $ownerKey,
                '=',
                $value
            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute Access
    |--------------------------------------------------------------------------
    */

    public function __get(
        string $key
    ): mixed {

        return $this->attributes[$key]
            ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Array Conversion
    |--------------------------------------------------------------------------
    */

    public function toArray(): array
    {
        return $this->attributes;
    }

    /*
    |--------------------------------------------------------------------------
    | JSON Serialization
    |--------------------------------------------------------------------------
    */

    public function jsonSerialize(): array
    {
        return $this->attributes;
    }
}