<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isCentral();
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'role'      => ['required', 'in:branch,central'],
            'branch_id' => ['nullable', 'required_if:role,branch', 'exists:branches,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required_if' => 'A branch must be selected for users with the Branch role.',
        ];
    }
}

