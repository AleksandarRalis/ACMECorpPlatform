<?php   

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpsertUserRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->route('user'))
            ],
            'department' => [
                'required',
                'string',
                'max:255',
            ],
            'position' => [
                'required',
                'string',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
            'password_confirmation' => [
                'required',
                'same:password',
            ],
        ];

        // For updates, make some fields optional and add role/is_active
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['department'] = ['nullable', 'string', 'max:255'];
            $rules['position'] = ['nullable', 'string', 'max:255'];
            $rules['phone'] = ['nullable', 'string', 'max:255'];
            $rules['password'] = ['nullable', 'string', 'min:8'];
            $rules['password_confirmation'] = ['nullable', 'same:password'];
            $rules['role'] = ['required', 'string', 'in:employee,admin'];
            $rules['is_active'] = ['required', 'boolean'];
            $rules['employee_id'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            
            'department.required' => 'The department field is required.',
            'department.string' => 'The department must be a string.',
            'department.max' => 'The department may not be greater than 255 characters.',
            
            'position.required' => 'The position field is required.',
            'position.string' => 'The position must be a string.',
            'position.max' => 'The position may not be greater than 255 characters.',
            
            'phone.required' => 'The phone field is required.',
            'phone.string' => 'The phone must be a string.',
            'phone.max' => 'The phone may not be greater than 255 characters.',
            
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters long.',
            
            'password_confirmation.required' => 'The password confirmation field is required.',
            'password_confirmation.same' => 'The password confirmation does not match.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'department' => 'Department',
            'position' => 'Position',
            'phone' => 'Phone',
            'password' => 'Password',
            'password_confirmation' => 'Password Confirmation',
            'role' => 'Role',
            'is_active' => 'Active Status',
            'employee_id' => 'Employee ID',
        ];
    }
}