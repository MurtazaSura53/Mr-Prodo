import { Validator } from "../main";
export class LoginValidator extends Validator {
    constraints() {
        return {
            email: ['required', 'email'],
            password: ['required'],
        }
    }
    messages() {
        return {
            email: {
                required: 'Fill this field',
                email: 'Invalid email',
            },
            password: {
                required: 'Fill this field',
            }
        }
    }
}