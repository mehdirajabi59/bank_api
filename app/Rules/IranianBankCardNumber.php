<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IranianBankCardNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove any non-numeric characters
        $cardNumber = preg_replace('/\D/', '', $value);

        // Check if the card number is exactly 16 digits
        if (strlen($cardNumber) !== 16) {
            $fail(__('validation.card_number_invalid', ['attribute' => ':attribute']));
        }

        // Apply the Luhn algorithm
        $sum = 0;
        $isEven = false;

        for ($i = 15; $i >= 0; $i--) {
            $digit = (int)$cardNumber[$i];

            if ($isEven) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
            $isEven = !$isEven;
        }

         if ($sum % 10 !== 0) {
             $fail(__('validation.card_number_invalid', ['attribute' => ':attribute']));
         }
    }
}
