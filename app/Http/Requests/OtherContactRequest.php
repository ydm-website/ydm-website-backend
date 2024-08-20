<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtherContactRequest extends FormRequest
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
            'instansi' => "required|max:255",
            'address' => "required|max:255",
            'call' => "required|max:255",
            'email' => "required|max:255",
            'website' => "required|max:255",
        ];
    }

    public function message(): array
    {
        return[
            "instansi.required" => "instansi is required",
            "instansi.max" => "instansi cannot contain more than 255 characters",
            "address.required" => "address is required",
            "address.max" => "address cannot contain more than 255 characters",
            "call.required" => "Number is required",
            "call.max" => "Number cannot contain more than 255 characters",
            "email.required" => "Email is required",
            "email.max" => "Email cannot contain more than 255 characters",
            "website.required" => "website is required",
            "website.max" => "website cannot contain more than 255 characters",
        ];
    }
}
