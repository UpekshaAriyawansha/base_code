<?php

namespace Src\Presentation\Http;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): void {

        Response::json(

            [

                'success' => true,

                'message' => $message,

                'data' => $data

            ],

            $status

        );
    }

    public static function error(
        string $message = 'Error',
        int $status = 400,
        mixed $errors = null
    ): void {

        Response::json(

            [

                'success' => false,

                'message' => $message,

                'errors' => $errors

            ],

            $status

        );
    }
}