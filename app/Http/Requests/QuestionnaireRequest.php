<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionnaireRequest extends FormRequest
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
            'question_id' => 'required|integer|exists:questions,id',
            'answer_id' => 'required_without_all:answer|exists:answers,id',
            'answer' => 'required_without_all:answer_id|string|max:512',
        ];
    }
}
