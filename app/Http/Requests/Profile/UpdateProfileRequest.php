<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $email = trim((string) $this->input('email'));

        // Email é opcional: string vazia vira null (evita conflito no unique).
        $this->merge([
            'email' => $email === '' ? null : mb_strtolower($email),
        ]);
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone'         => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
            'avatar'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'O nome é obrigatório.',
            'email.email'    => 'Introduz um email válido.',
            'email.unique'   => 'Este email já está a ser usado.',
            'phone.required' => 'O telefone é obrigatório.',
            'phone.unique'   => 'Este telefone já está a ser usado.',
            'avatar.image'   => 'O ficheiro deve ser uma imagem.',
            'avatar.mimes'   => 'A foto deve ser JPG, PNG ou WebP.',
            'avatar.max'     => 'A foto não pode ter mais de 2 MB.',
            'avatar.uploaded'=> 'Não foi possível enviar a foto. Tenta uma imagem mais pequena.',
        ];
    }
}