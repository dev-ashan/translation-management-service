<?php

namespace App\Http\Requests\Api\Translation;

use Illuminate\Foundation\Http\FormRequest;

class TranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'locale_id' => 'required|exists:locales,id',
            'key' => 'required|string|max:255',
            'value' => 'required|string',
            'group' => 'required|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id'
        ];
    }
} 