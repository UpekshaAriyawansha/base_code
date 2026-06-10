<?php

namespace Src\Presentation\Requests;

use Src\Validation\Validator;

abstract class FormRequest
{
    abstract public function rules():
        array;

    public function validate(): array
    {
        $data = json_decode(

            file_get_contents(
                'php://input'
            ),

            true

        );

        Validator::validate(

            $data,

            $this->rules()

        );

        return $data;
    }
}