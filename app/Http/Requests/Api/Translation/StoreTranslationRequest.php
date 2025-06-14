<?php

namespace App\Http\Requests\Api\Translation;

use Illuminate\Foundation\Http\FormRequest;

class StoreTranslationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'locale_id' => 'required|exists:locales,id',
            'key' => 'required|string|max:255',
            'value' => 'required|string',
            'group' => 'required|string|max:255',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ];
    }

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
