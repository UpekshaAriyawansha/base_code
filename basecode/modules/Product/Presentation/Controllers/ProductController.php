<?php

namespace Modules\Product\Presentation\Controllers;

use Src\Presentation\Controllers\Controller;

class ProductController
extends Controller
{
    public function index(): void
    {
        $this->success(

            'List fetched successfully',

            []

        );
    }

    public function show(
        int $id
    ): void {

        $this->success(

            'Single item fetched',

            [
                'id' => $id
            ]

        );
    }

    public function create(): void
    {
        $this->success(

            'Item created successfully'

        );
    }

    public function update(
        int $id
    ): void {

        $this->success(

            'Item updated successfully',

            [
                'id' => $id
            ]

        );
    }

    public function delete(
        int $id
    ): void {

        $this->success(

            'Item deleted successfully',

            [
                'id' => $id
            ]

        );
    }
}
