import { Validator } from "../main.js";

export class StoreProductValidator extends Validator {
    constraints() {
        return {
            name: ['required', { pattern: /^[a-zA-Z\d\s()&,.\/\-]+$/ }, { max: 255 }],
            stock: ['required', 'number', { minValue: 0 }],
            purchase_price: ['required', 'number', { minValue: 1 }],
            selling_price: ['required', 'number', { gtField: 'purchase_price' }]
        }
    }
    messages() {
        return {
            name: {
                required: 'Fill this field',
                pattern: 'Invalid name',
                max: 'Too long'
            },
            stock: {
                required: 'Fill this field',
                number: 'Invalid stock',
                minValue: 'Negative value not allowed'
            },
            purchase_price: {
                required: 'Fill this field',
                number: 'Invalid price',
                minValue: 'Minimum price should be 1'
            },
            selling_price: {
                required: 'Fill this field',
                number: 'Invalid price',
                gtField: 'Selling price must be greater'
            }
        }
    }
}