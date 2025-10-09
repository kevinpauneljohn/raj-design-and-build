<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('add client');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required','string'],
            'middlename' => ['string','nullable'],
            'lastname' => ['required','string'],
            'email' => ['unique:clients','nullable','email'],
            'date_of_birth' => ['nullable','date'],
            'mobile_number' => ['nullable'],
            'address' => ['required'],
        ];
    }
}
