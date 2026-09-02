<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $product = $this->route('product');
        $purchasePrice = $this->input('purchase_price', $product?->purchase_price ?? 0);
        return [
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'name' => 'sometimes|required|regex:/^[a-zA-Z\d\s()&,.\/\-]+$/|max:255',
            'description' => 'sometimes|nullable|string',
            'unit' => [
                'sometimes',
                'required',
                'string',
                Rule::in(['pcs', 'kg', 'g', 'ltr', 'ml', 'meter', 'cm']),
            ],
            'stock' => 'sometimes|required|numeric|min:0',
            'purchase_price' => 'sometimes|required|numeric|min:1',
            'selling_price' => "sometimes|required|numeric|gt:{$purchasePrice}",
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
