<?php

namespace App\Http\Requests\Api\Profile;

use Illuminate\Foundation\Http\FormRequest;

class CreateImageProfileRequest extends FormRequest
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
            'profile_image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:5120' // 2MB máximo
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'profile_image.required' => 'La imagen de perfil es obligatoria.',
            'profile_image.image' => 'El archivo debe ser una imagen válida.',
            'profile_image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.',
            'profile_image.max' => 'La imagen no debe superar los 5MB.'
        ];
    }
}