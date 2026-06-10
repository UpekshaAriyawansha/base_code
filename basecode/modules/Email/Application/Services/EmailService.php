<?php

namespace Modules\Email\Application\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;
use Modules\Email\Infrastructure\Persistence\EmailRepository;
use Modules\Email\Infrastructure\Persistence\EmailLogRepository;


class EmailService
{
    public function __construct(
        private EmailRepository $repository,
        private EmailLogRepository $logRepository
    ) {}

    public function getEmailSettings(): array
    {
        return $this->repository->getEmailSettings();
    }

    public function saveEmailSettings(array $data): bool
    {
        return $this->repository->saveEmailSettings($data);
    }



    public function send(string $to, string $subject, string $html): bool
{
    $config = $this->repository->getEmailSettings();

    if (empty($config['smtp_host']) || empty($config['username'])) {
        throw new \Exception("Email SMTP configuration is missing.");
    }

    $mail = new PHPMailer(true);

    try {

        // =====================
        // SMTP CONFIG
        // =====================
        $mail->isSMTP();
        $mail->Host       = $config['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];

        if ($config['encryption'] === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($config['encryption'] === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPSecure = false;
        }

        $mail->Port = (int) $config['smtp_port'];

        // =====================
        // SENDER
        // =====================
        $mail->setFrom(
            $config['sender_email'],
            $config['sender_name']
        );

        $mail->addAddress($to);

        // =====================
        // CONTENT
        // =====================
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html;

        $result = $mail->send();

        // =====================
        // LOG SUCCESS
        // =====================
        $this->logRepository->create([
            'recipient' => $to,
            'subject'   => $subject,
            'status'    => 'success',
            'error'     => null,
        ]);

        return $result;

    } catch (\Throwable $e) {

        // =====================
        // LOG FAILURE
        // =====================
        $this->logRepository->create([
            'recipient' => $to,
            'subject'   => $subject,
            'status'    => 'failed',
            'error'     => $e->getMessage(),
        ]);

        throw new \Exception("Mail Error: " . $e->getMessage());
    }
}


    /**
     * Send email using SMTP
     */
    // public function send(string $to, string $subject, string $html): bool
    // {
    //     $config = $this->repository->getEmailSettings();

    //     if (empty($config['smtp_host']) || empty($config['username'])) {
    //         throw new \Exception("Email SMTP configuration is missing.");
    //     }

    //     $mail = new PHPMailer(true);

    //     try {

    //         // =====================
    //         // SMTP CONFIG
    //         // =====================
    //         $mail->isSMTP();
    //         $mail->Host       = $config['smtp_host'];
    //         $mail->SMTPAuth   = true;
    //         $mail->Username   = $config['username'];
    //         $mail->Password   = $config['password'];

    //         // Encryption handling (important fix)
    //         if ($config['encryption'] === 'ssl') {
    //             $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    //         } elseif ($config['encryption'] === 'tls') {
    //             $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //         } else {
    //             $mail->SMTPSecure = false;
    //         }

    //         $mail->Port = (int) $config['smtp_port'];

    //         // =====================
    //         // SENDER INFO
    //         // =====================
    //         $mail->setFrom(
    //             $config['sender_email'],
    //             $config['sender_name']
    //         );

    //         // =====================
    //         // RECIPIENT
    //         // =====================
    //         $mail->addAddress($to);

    //         // =====================
    //         // CONTENT
    //         // =====================
    //         $mail->isHTML(true);
    //         $mail->Subject = $subject;
    //         $mail->Body    = $html;

    //         return $mail->send();

    //     } catch (MailException $e) {
    //         throw new \Exception("Mail Error: " . $e->getMessage());
    //     }
    // }
}



// namespace Modules\Email\Application\Services;

// use PHPMailer\PHPMailer\PHPMailer;
// use Modules\Email\Infrastructure\Persistence\EmailRepository;

// class EmailService
// {
//     public function __construct(
//         private EmailRepository $repository
//     ) {}

//     public function getEmailSettings(): array
//     {
//         return $this->repository
//             ->getEmailSettings();
//     }

//     public function saveEmailSettings(
//         array $data
//     ): bool {

//         return $this->repository
//             ->saveEmailSettings(
//                 $data
//             );
//     }


//     public function send(
//         string $to,
//         string $subject,
//         string $html
//     ): bool {

//         $config = $this->repository->getEmailSettings();

//         $mail = new PHPMailer(true);

//         $mail->isSMTP();
//         $mail->Host       = $config['smtp_host'];
//         $mail->SMTPAuth   = true;
//         $mail->Username   = $config['username'];
//         $mail->Password   = $config['password'];
//         $mail->SMTPSecure = $config['encryption'];
//         $mail->Port       = (int) $config['smtp_port'];

//         $mail->setFrom(
//             $config['sender_email'],
//             $config['sender_name']
//         );

//         $mail->addAddress($to);

//         $mail->isHTML(true);
//         $mail->Subject = $subject;
//         $mail->Body    = $html;

//         return $mail->send();
//     }
// }