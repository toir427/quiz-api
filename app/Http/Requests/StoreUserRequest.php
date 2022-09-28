<?php

namespace App\Http\Requests;

use App\Rules\UserGender;
use App\Rules\UserStatus;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        $user = request()->user();
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'birthday' => 'required|date',
            'status' => ['required','integer', new UserStatus()],
            'gender' => ['required','integer', new UserGender()],
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6|same:password',
        ];
    }
}
