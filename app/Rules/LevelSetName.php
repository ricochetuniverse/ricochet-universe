<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LevelSetName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        // Game editor blocks these symbols: * / \ | " ; : < > ?

        return preg_match('/^[a-zA-Z0-9`~!@#$%^&()\-_=+[\]{}\',.áéèïíöñ°³ ]+$/', $value) === 1;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute contains special/non-alpha-numeric characters that are not allowed, please simplify and remove them.';
    }
}
