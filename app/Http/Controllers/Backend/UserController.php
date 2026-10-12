<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Models\User;
use App\Enums\UserRole;
class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
    }
    public function store(Request $request)
    {
        if ($request->filled('phone')) {
            $request->merge(['phone' => User::normalizePhone($request->input('phone'))]);
        }

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'regex:/^\+\d{9,15}$/', 'unique:users,phone'],
            'email'    => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', Rule::enum(UserRole::class)],
        ]);

        $user = $this->userRepository->create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Utilizador {$user->name} criado com sucesso.");
    }
}
