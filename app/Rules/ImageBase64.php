<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ImageBase64 implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regex = '/^data:[a-zA-Z0-9\/+]+;base64,[a-zA-Z0-9\/+]+={0,2}$/';

        if (!is_string($value) || (bool)preg_match($regex, $value) === false) {
            $fail('validation.base64string')->translate();
        } else {
            $result = mime_content_type($value);

            if (!$result) {
                $fail('validation.image')->translate();
            } else {
                $result = explode('/', $result);

                (count($result) && $result[0] === 'image') ?: $fail('validation.image')->translate();
            }
        }
    }
}
