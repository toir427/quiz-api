<?php
/**
 * @copyright Copyright © 2021 Tuychiev Toir
 * @author    Tuychiev Toir <info@tuychiev.com>
 * @link      https://www.tuychiev.com
 */

namespace App\Http\Requests;

use App\Rules\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
            'current_password' => ['required', 'string', 'min:6', new MatchOldPassword],
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6|same:password',
        ];
    }
}
