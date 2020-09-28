<?php

namespace App\Http\Requests\User;

use App\Rules\User\MatchOldPassword;
use App\Rules\User\PreventOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePassword extends FormRequest
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
            'current_password' => ['required', new MatchOldPassword()],
            'password' => ['required', 'confirmed', new PreventOldPassword()]
        ];
    }
}
