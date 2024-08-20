<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8',
            'role_id' => "required|exists:roles,id"
        ];
    }

    public function messages():array
    {
        return[
            'name.required' => 'Name is required',
            'name.max' => 'Name cannot contain more than 255 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.unique' => 'Email already exists',
            'email.max' => 'Email cannot contain more than 255 characters',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'role_id.required' => 'Role is required',
            "role_id.exists" => "Role ID is not found"
        ];
    }
}
