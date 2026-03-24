<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('userId');

        return [
            'name' => ['sometimes', 'string','min:3', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId, 'id'),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'location_code' => ['nullable', 'string', 'max:255'],
            'role_ids' => ['required', 'uuid', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.email' => 'Email format is invalid.',
            'email.unique' => 'This email is already taken.',
            'password.min' => 'Password must be at least 6 characters.',
            'role_id.required' => 'Role is required.',
            'role_ids.exists' => 'Selected role does not exist.',
        ];
    }
}