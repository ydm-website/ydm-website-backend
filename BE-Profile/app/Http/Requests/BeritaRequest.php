<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BeritaRequest extends FormRequest
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
            'author' => "required|max:255",
            'image' => "mimes:jpg,bmp,png",
            'content' => "required",
            'kategori_id' => "required|exists:kategori_berita,id",
        ];
    }

    public function message(): array
    {
        return[
            "title.required" => "Title is required",
            "title.max" => "Title cannot contain more than 255 characters",
            "author.required" => "Author is required",
            "author.max" => "Author cannot contain more than 255 characters",
            "image.mimes" => "Image must be in JPG, BMP, or PNG format",
            "content.required" => "Content is required",
            "kategori_id.required" => "Kategori ID is required",
            "kategori_id.exists" => "Kategori ID is not found",
        ];
    }
}
