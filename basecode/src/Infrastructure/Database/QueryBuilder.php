<?php

namespace Src\Infrastructure\Database;

use PDO;

class QueryBuilder
{
    private PDO $db;

    private string $table;

    private array $where = [];

    private array $bindings = [];

    private ?int $limit = null;

    private ?int $offset = null;

    private ?string $orderBy = null;

    private string $orderDirection = 'ASC';

    public function __construct(
        PDO $db
    ) {
        $this->db = $db;
    }

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    public function table(
        string $table
    ): self {

        $this->table = $table;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Where
    |--------------------------------------------------------------------------
    */

    public function where(
        string $column,
        mixed $operator,
        mixed $value = null
    ): self {

        if ($value === null) {

            $value = $operator;

            $operator = '=';
        }

        $this->where[] =
            "{$column} {$operator} ?";

        $this->bindings[] =
            $value;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Order By
    |--------------------------------------------------------------------------
    */

    public function orderBy(
        string $column,
        string $direction = 'ASC'
    ): self {

        $this->orderBy =
            $column;

        $this->orderDirection =
            strtoupper($direction);

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Limit
    |--------------------------------------------------------------------------
    */

    public function limit(
        int $limit
    ): self {

        $this->limit = $limit;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Offset
    |--------------------------------------------------------------------------
    */

    public function offset(
        int $offset
    ): self {

        $this->offset = $offset;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Get Results
    |--------------------------------------------------------------------------
    */

public function get(): array
{
    $sql =
        "SELECT * FROM {$this->table}";

    if ($this->where) {

        $sql .=
            " WHERE " .
            implode(
                ' AND ',
                $this->where
            );
    }

    if ($this->orderBy) {

        $sql .=
            " ORDER BY {$this->orderBy} {$this->orderDirection}";
    }

    if ($this->limit !== null) {

        $sql .=
            " LIMIT {$this->limit}";
    }

    if ($this->offset !== null) {

        $sql .=
            " OFFSET {$this->offset}";
    }

    error_log('SQL: ' . $sql);
    error_log('Bindings: ' . json_encode($this->bindings));

    $stmt =
        $this->db->prepare($sql);

    $stmt->execute($this->bindings);

    $results =
        $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ IMPORTANT: reset state after execution
    $this->reset();

    return $results;
}

    /*
    |--------------------------------------------------------------------------
    | First Result
    |--------------------------------------------------------------------------
    */

    public function first(): ?array
    {
        $this->limit(1);

        $results =
            $this->get();

        return $results[0]
            ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Find By ID
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): ?array {

        return $this
            ->where(
                'id',
                '=',
                $id
            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Insert
    |--------------------------------------------------------------------------
    */

    public function insert(
        array $data
    ): bool {

        $columns =
            implode(
                ', ',
                array_keys($data)
            );

        $placeholders =
            implode(
                ', ',
                array_fill(
                    0,
                    count($data),
                    '?'
                )
            );

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            $columns,
            $placeholders
        );

        $stmt =
            $this->db->prepare(
                $sql
            );

        return $stmt->execute(
            array_values($data)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        array $data
    ): bool {

        $fields = [];

        foreach (
            array_keys($data)
            as $column
        ) {

            $fields[] =
                "{$column} = ?";
        }

        $sql =
            "UPDATE {$this->table} SET " .
            implode(
                ', ',
                $fields
            );

        if ($this->where) {

            $sql .=
                " WHERE " .
                implode(
                    ' AND ',
                    $this->where
                );
        }

        $stmt =
            $this->db->prepare(
                $sql
            );

        return $stmt->execute(
            array_merge(
                array_values($data),
                $this->bindings
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(): bool
    {
        $sql =
            "DELETE FROM {$this->table}";

        if ($this->where) {

            $sql .=
                " WHERE " .
                implode(
                    ' AND ',
                    $this->where
                );
        }

        $stmt =
            $this->db->prepare(
                $sql
            );

        return $stmt->execute(
            $this->bindings
        );
    }


    private function reset(): void
{
    $this->where = [];
    $this->bindings = [];
    $this->limit = null;
    $this->offset = null;
    $this->orderBy = null;
    $this->orderDirection = 'ASC';
}
}
