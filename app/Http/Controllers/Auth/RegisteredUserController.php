<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // 84 123 4567 -> +258841234567 (assim o "unique" funciona sempre)
        $request->merge(['phone' => User::normalizePhone((string) $request->input('phone'))]);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'regex:/^\+\d{9,15}$/', 'unique:'.User::class],
            'email'    => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone.regex'  => 'Introduza um número de telefone válido.',
            'phone.unique' => 'Este telefone já está registado.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => UserRole::User, // nunca vem do formulário
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route($user->homeRoute());
    }
}
