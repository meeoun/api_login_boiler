<?php

namespace App\Http\Requests\Invitations;

use App\Models\Invitation;
use Illuminate\Foundation\Http\FormRequest;

class RespondRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
     return $this->user()->can('respond', Invitation::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => ['required'],
            'decision' => ['required']
        ];
    }
}
