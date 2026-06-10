<?php

namespace Modules\Product\Domain\Models;

use Src\Infrastructure\Database\Model;

class Product
extends Model
{
    protected static string $table =
        'products';
}
