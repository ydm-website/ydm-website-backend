<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetailBidangRequest extends FormRequest
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
            'deskripsi' => "required",
            'image' => "mimes:jpg,bmp,png",
            'bidang_id' => "exists:bidang,id",
        ];
    }

    public function message(): array
    {
        return[
            "name.required" => "Title is required",
            "name.max" => "Title cannot contain more than 255 characters",
            "deskripsi.required" => "Deskripsi is required",
            "image.mimes" => "Image must be in JPG, BMP, or PNG format",
            "bidang_id.exists" => "Bidang ID is not found",
        ];
    }
}
