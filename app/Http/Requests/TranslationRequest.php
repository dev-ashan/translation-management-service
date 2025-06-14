<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for validating translation data
 * 
 * This request handles validation for both creating and updating translations,
 * ensuring that all required data is present and valid.
 */
class TranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'locale_id' => 'required|exists:locales,id',
            'key' => 'required|string|max:255',
            'value' => 'required|string',
            'group' => 'required|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'locale_id.required' => 'The locale is required.',
            'locale_id.exists' => 'The selected locale is invalid.',
            'key.required' => 'The translation key is required.',
            'key.max' => 'The translation key may not be greater than 255 characters.',
            'value.required' => 'The translation value is required.',
            'group.required' => 'The translation group is required.',
            'group.max' => 'The translation group may not be greater than 255 characters.',
            'tags.array' => 'The tags must be an array.',
            'tags.*.exists' => 'One or more selected tags are invalid.'
        ];
    }
} 