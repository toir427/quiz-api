<?php

namespace App\Rules;

use App\Models\Survey;
use Illuminate\Contracts\Validation\Rule;

class SurveyGender implements Rule
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
        return in_array($value, Survey::$genders);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The `gender` does not exist.';
    }
}
