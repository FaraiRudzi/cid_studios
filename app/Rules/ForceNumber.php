<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ForceNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Rule 1: Must be exactly 7 characters long.
        if (strlen($value) !== 7) {
            return false;
        }

        // Rule 2: First 6 characters must be digits.
        $numbers = substr($value, 0, 6);
        if (!ctype_digit($numbers)) {
            return false;
        }

        // Rule 3: The first digit must be '0' or '9'.
        $firstDigit = $value[0];
        if ($firstDigit !== '0' && $firstDigit !== '9') {
            return false;
        }

        // Rule 4: The 7th character must be a letter.
        $letter = substr($value, 6, 1);
        if (!ctype_alpha($letter)) {
            return false;
        }

        // Rule 5: The final letter must not contain 'U', 'O', or 'I'.
        // We use a case-insensitive check.
        if (in_array(strtoupper($letter), ['U', 'O', 'I'])) {
            return false;
        }

        // If all checks pass, the rule is valid.
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not a valid force number. It must be 7 characters: 6 digits starting with 0 or 9, followed by a letter (excluding U, O, I).';
    }
}