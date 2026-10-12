<?php
namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => [
                'required','string','max:255',
            ],

            'email' => [
                'required','string','email',
                'max:255',Rule::unique('users', 'email')->ignore($user),
            ],

            'phone' => [
                'nullable','string','max:20', Rule::unique('users', 'phone')->ignore($user),
            ],

            'role' => [
                'required','in:admin,user',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Introduz um endereço de email válido.',
            'email.unique' => 'Este email já está registado.',
            'phone.unique' => 'Este número de telefone já está registado.',
            'role.required' => 'O papel do utilizador é obrigatório.',
            'role.in' => 'O papel selecionado é inválido.',
        ];
    }
}
