<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSignupRequest extends FormRequest
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
            'name' => 'required|regex:/^[a-zA-Z\d\s]+$/|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\W_]{8,32}$/',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Fill this field',
            'name.regex' => 'Invalid name',
            'name.max' => 'Name is too long',

            'email.required' => 'Fill this field',
            'email.email' => 'Invalid email',
            'email.unique' => 'Email already exists',

            'password.required' => 'Fill this field',
            'password.regex' => 'Invalid or weak password',
        ];
    }
}
