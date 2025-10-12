<?php

declare(strict_types=1);

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidTimestamp implements ValidationRule
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
        $passes = filter_var($value, FILTER_VALIDATE_INT) !== false && $value >= 0 && $value <= Carbon::now()->addYear()->getTimestamp();
        if (! $passes) {
            $fail('The :attribute must be a valid timestamp.');
        }
    }
}
