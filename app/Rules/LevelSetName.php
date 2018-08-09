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
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Game editor blocks these symbols: * / \ | " ; : < > ?

        return preg_match('/^[a-zA-Z0-9`~!@#$%^&()\-_=+[\]{};\'.áéèïíöñ°³ ]+$/', $value) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid name.';
    }
}
