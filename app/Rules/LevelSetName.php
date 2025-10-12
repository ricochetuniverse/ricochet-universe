<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LevelSetName implements ValidationRule
{
    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Game editor blocks these symbols: * / \ | " ; : < > ?
        if (preg_match('/^[a-zA-Z0-9`~!@#$%^&()\-_=+[\]{}\',.áéèïíöñ°³ ]+$/u', $value) !== 1) {
            $fail('The :attribute contains special/non-alpha-numeric characters that are not allowed, please simplify and remove them.');
        }
    }
}
