<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeroRequest extends FormRequest
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
            'title' => "required|max:255",
            'image' => "mimes:jpg,bmp,png",
            'deskripsi' => "required",
        ];
    }

    public function message(): array
    {
        return[
            "title.required" => "Title is required",
            "title.max" => "Title cannot contain more than 255 characters",
            "image.mimes" => "Image must be in JPG, BMP, or PNG format",
            "deskripsi.required" => "Deskripsi is required",
        ];
    }
}
