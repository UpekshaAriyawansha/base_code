<?php

namespace Modules\User\Presentation\Controllers;

use Src\Presentation\Controllers\Controller;

class UploadController extends Controller
{
    public function upload(): void
    {
        if (!isset($_FILES['file'])) {

            $this->error(
                'No file uploaded',
                422
            );

            return;
        }

        $file = $_FILES['file'];

        $extension =
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            );

        $filename =
            uniqid('logo_')
            . '.'
            . $extension;

        $uploadDir =
            __DIR__
            . '/../../../../public/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir(
                $uploadDir,
                0777,
                true
            );
        }

        move_uploaded_file(
            $file['tmp_name'],
            $uploadDir . $filename
        );

        $this->success(
            'Uploaded',
            [
                'path' =>
                    '/uploads/'
                    . $filename
            ]
        );
    }
}