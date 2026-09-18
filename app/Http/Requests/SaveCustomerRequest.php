<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveCustomerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'name' => 'required|regex:/^[a-zA-Z\d\s]+$/|max:255',
            'phone' => 'nullable|regex:/^[\d]{10,20}$/',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Fill this field',
            'name.regex' => 'Invalid name',
            'phone.regex' => 'Invalid phone.no',
        ];
    }
}
