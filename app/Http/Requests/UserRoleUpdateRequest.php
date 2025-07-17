<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRoleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $current_role = $this->user()->roles[0]->name;
        $targetUser = $this->route('user');
        $current_role = $targetUser->roles[0]->name;
        return [
            'role_id' => 'required|string|exists:roles,name|not_in:'.$current_role,
        ];
    }


    public function messages()
    {
        return [
            'role_id.exists' => 'Role does not exist',
            'role_id.not_in' => 'This is the current role'
        ];

    }
}
