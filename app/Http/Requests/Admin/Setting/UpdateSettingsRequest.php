<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $rules = [];

        foreach (config('settings.fields') as $key => $field) {
            $rules[$key] = $field['rules'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        $attributes = [];

        foreach (config('settings.fields') as $key => $field) {
            $attributes[$key] = mb_strtolower($field['label']);
        }

        return $attributes;
    }
}
