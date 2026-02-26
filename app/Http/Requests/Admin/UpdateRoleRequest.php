<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('id');

        if (!$userId && $this->has('id')) {
            $userId = $this->input('id');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'role_ids' => 'required|array|min:1', // Changed from role_id to role_ids
            'role_ids.*' => 'exists:roles,id', // Validate each role ID
            'is_active' => 'boolean',
        ];

        if ($userId) {
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId)
            ];
        } else {
            $rules['email'] = 'required|email';
        }

        $rules['password'] = 'nullable|string|min:8|confirmed';

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The role name is required.',
            'name.unique' => 'This role name already exists.',
            'display_name.required' => 'The display name is required.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure permissions is always an array
        if ($this->has('permissions') && empty($this->permissions)) {
            $this->merge(['permissions' => []]);
        }
    }
}
