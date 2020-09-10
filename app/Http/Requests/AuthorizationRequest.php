<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorizationRequest extends FormRequest
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
            'username' => 'required|string|between:1,60',
            'email' => 'required|string|max:320|email|unique:users,email',
            'password' => 'required|string|between:1,127',
            'role' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string'
        ];
    }
}
