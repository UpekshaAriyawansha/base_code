<?php

namespace Src\Validation;

class Validator
{
    public static function validate(
        array $data,
        array $rules
    ): void {

        $errors = [];

        foreach (
            $rules as $field => $fieldRules
        ) {

            /*
            |--------------------------------------------------------------------------
            | Convert String Rules To Array
            |--------------------------------------------------------------------------
            */

            if (is_string($fieldRules)) {

                $fieldRules =
                    explode(
                        '|',
                        $fieldRules
                    );
            }

            foreach ($fieldRules as $rule) {

                /*
                |--------------------------------------------------------------------------
                | Required
                |--------------------------------------------------------------------------
                */

                if (
                    $rule === 'required'
                    &&
                    empty($data[$field])
                ) {

                    $errors[$field][] =
                        "The {$field} field is required.";
                }

                /*
                |--------------------------------------------------------------------------
                | Email
                |--------------------------------------------------------------------------
                */

                if (
                    $rule === 'email'
                    &&
                    !filter_var(
                        $data[$field] ?? '',
                        FILTER_VALIDATE_EMAIL
                    )
                ) {

                    $errors[$field][] =
                        "The {$field} must be a valid email.";
                }

                /*
                |--------------------------------------------------------------------------
                | Minimum Length
                |--------------------------------------------------------------------------
                */

                if (
                    str_starts_with(
                        $rule,
                        'min:'
                    )
                ) {

                    $min =
                        (int) str_replace(
                            'min:',
                            '',
                            $rule
                        );

                    if (
                        strlen(
                            $data[$field] ?? ''
                        ) < $min
                    ) {

                        $errors[$field][] =
                            "The {$field} must be at least {$min} characters.";
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Throw Validation Exception
        |--------------------------------------------------------------------------
        */

        if ($errors) {

            throw new ValidationException(
                $errors
            );
        }
    }
}