<?php

namespace App\Http\Requests\Api\Locale;

use App\Models\Locale;
use Illuminate\Foundation\Http\FormRequest;

class LocaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locale = $this->route('locale');
        $id = $locale instanceof Locale ? $locale->id : null;
        return [
            'code' => ['required', 'string', 'max:10', 'unique:locales,code,' . $id],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
} 