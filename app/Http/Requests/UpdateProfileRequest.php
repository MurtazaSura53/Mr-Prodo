<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
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
            'name' => 'sometimes|required|regex:/^[a-zA-Z\d\s]+$/|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . Auth::id(),
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Fill this field',
            'name.regex' => 'Invalid name',
            'name.max' => 'Name is too long',

            'email.required' => 'Fill this field',
            'email.email' => 'Invalid email',
            'email.unique' => 'Email already exists',
        ];
    }
}
