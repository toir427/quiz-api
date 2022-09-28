<?php

namespace App\Http\Requests;

use App\Rules\SurveyGender;
use App\Rules\SurveyStatus;
use App\Rules\SurveyType;
use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'status' => ['required', 'integer', new SurveyStatus()],
            'gender' => ['required', 'integer', new SurveyGender()],
            'type' => ['required', 'integer', new SurveyType()],
            'age_from' => 'required|integer|min:1|max:120',
            'age_to' => 'required|integer|min:1|max:120',
        ];
    }
}
