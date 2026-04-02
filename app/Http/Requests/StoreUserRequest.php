<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'employee_id'   => ['nullable', 'string', 'unique:users,employee_id'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'gender'        => ['nullable', 'in:male,female'],
            'join_date'     => ['nullable', 'date'],
            'role'          => ['nullable', 'exists:roles,name'],
        ];
    }
}
