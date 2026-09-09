<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SaveSaleRequest extends FormRequest
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
            'customer_name' => 'nullable|regex:/^[a-zA-Z\s]+$/|max:255',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|regex:/^[\d]{10,20}$/',
            'sale_date' => 'required|date',
            'sale_items' => 'required|array|min:1',

            'sale_items.*.product_id' => 'required|integer|exists:products,id',
            'sale_items.*.qty' => 'required|numeric|gt:0',
            'sale_items.*.price' => 'required|numeric|gt:0',
        ];
    }
    public function after(): array
    {
        return [
            function (Validator $validator) {

                foreach ($this->sale_items ?? [] as $index => $item) {
                    $product = Product::find($item['product_id'] ?? null);
                    if (
                        $product &&
                        isset($item['price']) &&
                        $item['price'] < $product->purchase_price
                    ) {
                        $validator->errors()->add(
                            "sale_items.$index.price",
                            "Sale price must be greater than purchase price"
                        );
                    }
                }
            },
        ];
    }
    public function messages()
    {
        return [
            'customer_name.regex' => 'Invalid name',
            'customer_name.max' => 'Too long',

            'customer_email.email' => 'Invalid email',

            'customer_phone.regex' => 'Invalid phone.no',

            'sale_date.required' => 'Fill this field',
            'sale_date.date' => 'Invalid date',

            'sale_items.required' => 'Add sale items first',
            'sale_items.array' => 'Invalid sale items format',
            'sale_items.min' => 'Minimum 1 sale item is required',

            'sale_items.*.product_id.required' => 'Select product',
            'sale_items.*.product_id.integer' => 'Invalid product',
            'sale_items.*.product_id.exists' => 'Product not found',

            'sale_items.*.qty.required' => 'Fill this field',
            'sale_items.*.qty.numeric' => 'Invalid qty',
            'sale_items.*.qty.gt' => 'Must be greater then 0',

            'sale_items.*.price.required' => 'Fill this field',
            'sale_items.*.price.numeric' => 'Invalid price',
            'sale_items.*.price.gt' => 'Must be greater then 0',
        ];
    }
}
