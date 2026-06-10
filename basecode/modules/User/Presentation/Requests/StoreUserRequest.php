<?php

namespace Modules\User\Presentation\Requests;

use Src\Presentation\Requests\FormRequest;

class StoreUserRequest
extends FormRequest
{
    public function rules(): array
    {
        return [

            'first_name' =>
                'required',

            'email' =>
                'required|email',

            'password' =>
                'required'

        ];
    }
}
