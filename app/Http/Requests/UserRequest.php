<?php

namespace App\Http\Requests;

use App\Rules\UserGender;
use App\Rules\UserStatus;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required',
            'birthday' => 'required|date',
            'status' => ['required', 'integer', new UserStatus()],
            'gender' => ['required', 'integer', new UserGender()],
        ];
    }
}
