<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'user_id' => 'required|numeric',
            'assignee_id' => 'required|numeric',
            'task_name' => 'required|string|max:25',
            'task_description' => 'nullable|string|max:250',
            'urgency' => 'nullable|numeric|between:1,5',
            'is_private' => 'nullable|bool',
        ];
    }
}
