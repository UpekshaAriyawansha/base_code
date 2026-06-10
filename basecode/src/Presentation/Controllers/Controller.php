<?php

namespace Src\Presentation\Controllers;

use Src\Presentation\Http\Response;
use Src\Presentation\Http\ApiResponse;

abstract class Controller
{
    protected function json(
        mixed $data,
        int $status = 200
    ): void {

        Response::json(
            $data,
            $status
        );
    }

    protected function success(
        string $message,
        mixed $data = null,
        int $status = 200
    ): void {

        ApiResponse::success(
            $data,
            $message,
            $status
        );
    }

    protected function error(
        string $message,
        int $status = 400,
        mixed $errors = null
    ): void {

        ApiResponse::error(
            $message,
            $status,
            $errors
        );
    }
}