<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengurusRequest extends FormRequest
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
            'name' => "required|max:255",
            'jabatan' => "required|max:255",
            'image' => "mimes:jpg,bmp,png",
            'peran_id' => "required|exists:peran_pengurus,id"
        ];
    }

    public function message(): array
    {
        return[
            "name.required" => "Name is required",
            "name.max" => "Name cannot contain more than 255 characters",
            "jabatan.required" => "Jabatan is required",
            "jabatan.max" => "Jabatan cannot contain more than 255 characters",
            "image.mimes" => "Image must be in JPG, BMP, or PNG format",
            "peran_id.required" => "Peran ID is required",
            "peran_id.exists" => "Peran ID is not found",
        ];
    }
}
