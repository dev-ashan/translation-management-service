<?php

namespace App\Http\Requests\Api\Locale;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocaleRequest extends FormRequest
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
            'code' => 'sometimes|string|max:10|unique:locales,code,' . $this->route('id'),
            'name' => 'sometimes|string|max:255',
            'is_active' => 'boolean'
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => __('validation.attributes.code'),
            'name' => __('validation.attributes.name'),
            'is_active' => __('validation.attributes.is_active'),
        ];
    }

    public function messages(): array
    {
        return [
            'code.max' => 'The locale code may not be greater than 10 characters.',
            'code.unique' => 'This locale code is already in use.',
            'name.max' => 'The locale name may not be greater than 255 characters.',
            'is_active.boolean' => 'The active status must be true or false.'
        ];
    }
}
