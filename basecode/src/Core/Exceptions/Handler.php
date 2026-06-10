<?php

namespace Src\Core\Exceptions;

use Throwable;
use Src\Validation\ValidationException;
use Src\Core\Exceptions\BusinessException;
use Src\Infrastructure\Logging\Logger;

class Handler
{
    public static function register(): void
    {
        set_exception_handler(
            [self::class, 'handleException']
        );

        set_error_handler(
            [self::class, 'handleError']
        );
    }

    public static function handleException(
        Throwable $exception
    ): void {

        /*
        |--------------------------------------------------------------------------
        | CLI Mode
        |--------------------------------------------------------------------------
        */

        if (PHP_SAPI === 'cli') {

            echo PHP_EOL;
            echo "ERROR: " .
                $exception->getMessage();
            echo PHP_EOL;

            echo "FILE: " .
                $exception->getFile();
            echo PHP_EOL;

            echo "LINE: " .
                $exception->getLine();
            echo PHP_EOL;

            return;
        }

        header(
            'Content-Type: application/json'
        );

        if (
            $exception instanceof ValidationException
        ) {

            http_response_code(422);

            echo json_encode([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $exception->errors()
            ]);

            return;
        }

        if (
            $exception instanceof BusinessException
        ) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => $exception->getMessage()
            ]);

            return;
        }

        http_response_code(500);

        $response = [
            'success' => false,
            'message' => 'Internal Server Error'
        ];

        if (config('app.debug')) {

            $response['exception'] =
                $exception->getMessage();

            $response['file'] =
                $exception->getFile();

            $response['line'] =
                $exception->getLine();
        }

        echo json_encode($response);

        Logger::channel()->error(

            $exception->getMessage(),

            [
                'file' =>
                    $exception->getFile(),

                'line' =>
                    $exception->getLine(),

                'trace' =>
                    $exception->getTraceAsString()
            ]
        );
    }

    public static function handleError(
        int $severity,
        string $message,
        string $file,
        int $line
    ): void {

        throw new \ErrorException(
            $message,
            0,
            $severity,
            $file,
            $line
        );
    }
}