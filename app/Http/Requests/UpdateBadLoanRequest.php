<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBadLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'proprietor' => ['nullable', 'string', 'max:255'],
            'reg_no' => ['nullable', 'string', 'max:100'],
            'reg_authority_date' => ['nullable', 'date'],
            'pan' => ['nullable', 'string', 'max:50'],
            'ctz' => ['nullable', 'string', 'max:50'],
            'nin' => ['nullable', 'string', 'max:50'],
            'guarantor_details' => ['nullable', 'string'],
            'client_code' => ['nullable', 'string', 'max:50'],
            'main_code' => ['nullable', 'string', 'max:50'],
            'loan_type' => ['nullable', 'string', 'max:100'],
            'limit' => ['nullable', 'numeric', 'min:0'],
            'sanction_date' => ['nullable', 'date'],
            'last_renew_date' => ['nullable', 'date'],
            'last_repayment_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'principal_os' => ['required', 'numeric', 'min:0'],
            'interest_os' => ['required', 'numeric', 'min:0'],
            'loan_class' => ['nullable', 'string', 'max:100'],
            '7_days_notice_date' => ['nullable', 'date'],
            '15_days_notice_date' => ['nullable', 'date'],
            '21_days_notice_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }
}

