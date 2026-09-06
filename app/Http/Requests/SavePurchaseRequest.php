<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SavePurchaseRequest extends FormRequest
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
            'supplier_name' => 'required|regex:/^[a-zA-Z,&\d\s\-]+$/|max:255',
            'purchase_date' => 'required|date',
            'purchase_items' => 'required|array|min:1',

            'purchase_items.*.product_id' => 'required|integer|exists:products,id',
            'purchase_items.*.qty' => 'required|numeric|gt:0',
            'purchase_items.*.price' => 'required|numeric|gt:0',
        ];
    }
    public function messages()
    {
        return [
            'supplier_name.required' => 'Fill this field',
            'supplier_name.regex' => 'Invalid supplier name',
            'supplier_name.max' => 'Too long',

            'purchase_date.required' => 'Select date',
            'purchase_date.date' => 'Invalid date',

            'purchase_items.required' => 'Add at least one product',
            'purchase_items.array' => 'Invalid purchase items',
            'purchase_items.min' => 'Add at least one product',

            'purchase_items.*.product_id.required' => 'Select product',
            'purchase_items.*.product_id.integer' => 'Invalid product',
            'purchase_items.*.product_id.exists' => 'Product not found',

            'purchase_items.*.qty.required' => 'Fill this field',
            'purchase_items.*.qty.numeric' => 'Invalid quantity',
            'purchase_items.*.qty.gt' => 'Quantity should be greater than 0',

            'purchase_items.*.price.required' => 'Fill this field',
            'purchase_items.*.price.numeric' => 'Invalid price',
            'purchase_items.*.price.gt' => 'Price should be greater than 0',
        ];
    }
}
