<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    // Os erros ficam num "bag" próprio para não se misturarem com os do perfil.
    protected $errorBag = 'updatePassword';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'different:current_password', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required'         => 'Introduz a palavra-passe actual.',
            'current_password.current_password' => 'A palavra-passe actual está incorrecta.',
            'password.required'                 => 'Introduz a nova palavra-passe.',
            'password.confirmed'                => 'A confirmação não coincide com a nova palavra-passe.',
            'password.different'                => 'A nova palavra-passe deve ser diferente da actual.',
            'password.min'                      => 'A palavra-passe deve ter pelo menos :min caracteres.',
        ];
    }
}