import { Validator } from '../main.js';
export class UpdateProfileValidator extends Validator {
    constraints() {
        return {
            name: ['required', { pattern: /^[a-zA-Z\d\s]+$/ }, { max: 255 }],
            email: ['required', 'email'],
        }
    }
    messages() {
        return {
            name: {
                required: 'Fill this field',
                pattern: 'Invalid name',
                max: 'Name is too long',
            },
            email: {
                required: 'Fill this field',
                email: 'Invalid email',
            },
        }
    }
}