<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|regex:/^[a-zA-Z\d\s()&,.\/\-]+$/|max:255',
            'description' => 'nullable|string',
            'unit' => [
                'required',
                'string',
                Rule::in(['pcs', 'kg', 'g', 'ltr', 'ml', 'meter', 'cm']),
            ],
            'stock' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|gt:purchase_price',
        ];
    }
    public function messages()
    {
        return [
            'category_id.required' => 'Category not provided',
            'category_id.integer' => 'Invalid category',
            'category_id.exists' => 'Category not found',

            'name.required' => 'Fill this field',
            'name.regex' => 'Invalid name',
            'name.max' => 'Too long',

            'description.string' => 'Invalid description',

            'unit.required' => 'Select unit',
            'unit.string' => 'Invalid unit',
            'unit.in' => 'Unit not supported',

            'stock.required' => 'Fill this field',
            'stock.numeric' => 'Invalid stock',
            'stock.min' => 'Negative value not allowed',

            'purchase_price.required' => 'Fill this field',
            'purchase_price.numeric' => 'Invalid price',
            'purchase_price.min' => 'Minimum price should be 1',

            'selling_price.required' => 'Fill this field',
            'selling_price.numeric' => 'Invalid price',
            'selling_price.gt' => 'Selling price must be greater',
        ];
    }
}
