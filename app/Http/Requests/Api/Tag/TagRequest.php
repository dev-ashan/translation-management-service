<?php

namespace App\Http\Requests\Api\Tag;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tagId = $this->route('tag') instanceof \App\Models\Tag
            ? $this->route('tag')->id
            : $this->route('tag');
        return [
            'name' => 'required|string|max:255|unique:tags,name,' . $tagId,
        ];
    }
} 