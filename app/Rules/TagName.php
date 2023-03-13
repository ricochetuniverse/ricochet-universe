<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class TagName implements Rule
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
        // In-game catalog uses these characters as a delimiter
        return ! Str::contains($value, [',', ';']);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute cannot contain commas or semicolons.';
    }
}
