<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('add supplier');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required','unique:suppliers,company_name'],
            'company_address' => ['required'],
            'email' => ['nullable', 'email', 'unique:suppliers,email'],
            'mobile_number' => ['nullable', 'unique:suppliers,mobile_number'],
            'telephone' => ['nullable', 'unique:suppliers,telephone', 'required_without:mobile_number'],
        ];
    }
}
