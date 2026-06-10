<?php

namespace Modules\Email\Presentation\Controllers;

use Exception;
use Modules\Email\Application\Services\EmailService;
use Src\Presentation\Controllers\Controller;

class EmailController extends Controller
{
    public function __construct(
        private EmailService $service
    ) {
    }



public function email(): void
{
    try {

        $this->success(
            'Email settings loaded',
            $this->service
                ->getEmailSettings()
        );

    } catch (\Throwable $e) {

        $this->error(
            $e->getMessage(),
            500
        );
    }
}


    public function saveEmail(): void
    {
        try {

            $data = json_decode(
                file_get_contents('php://input'),
                true
            );

            if (!$data) {

                $this->error(
                    'Invalid payload',
                    422
                );

                return;
            }

            $this->service
                ->saveEmailSettings(
                    $data
                );

            $this->success(
                'Email settings saved successfully'
            );

        } catch (Exception $e) {

            $this->error(
                $e->getMessage(),
                500
            );
        }
    }

public function test(): void
{
    try {

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $to = $data['to'] ?? null;

        if (!$to) {
            $this->error('Email address required', 422);
            return;
        }

        $mailService = new \Modules\Email\Application\Services\EmailService(
            new \Modules\Email\Infrastructure\Persistence\EmailRepository(),
            new \Modules\Email\Infrastructure\Persistence\EmailLogRepository()
        );

        $mailService->send(
            $to,
            'SMTP Test Email',
            '<h2>✅ Email configuration works successfully!</h2>'
        );

        $this->success(
            'Test email sent successfully'
        );

    } catch (\Throwable $e) {

        $this->error(
            $e->getMessage(),
            500
        );
    }
}

}