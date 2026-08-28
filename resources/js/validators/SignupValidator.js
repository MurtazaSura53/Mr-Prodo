import { Validator } from "../main";
export class SignupValidator extends Validator {
    constraints() {
        return {
            name: ['required', { pattern: /^[a-zA-Z\d\s]+$/ }, { max: 255 }],
            email: ['required', 'email'],
            password: ['required', { pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\W_]{8,32}$/ }],
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
            password: {
                required: 'Fill this field',
                pattern: 'Invalid or weak password',
            }
        }
    }
}