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

    public function __construct(
        private readonly bool $allowCommas = false
    ) {}

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

        // Level sets named with commas can't be rated due to a game bug, this is only allowed for old level sets
        // https://gitlab.com/ngyikp/ricochet-levels/-/work_items/35
        if (! $this->allowCommas && str_contains($value, ',')) {
            $fail('The :attribute contains commas that are not allowed due to game bugs, please remove them.');
        }
    }
}
