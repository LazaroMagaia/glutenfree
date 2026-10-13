<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\User\UserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function index(Request $request)
    {
        $users = $this->userRepository->paginateFiltered(
            search: $request->input('search'),
            role: $request->input('role'),
            perPage: 10,
            excludeId: $request->user()->id,
        );

        return view('Backend.Admin.Users.index', [
            'users' => $users,
            ...$this->userRepository->statistics(),
        ]);
    }
    public function create()
    {
        return view('Backend.Admin.Users.create');
    }
   
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();

        // Palavra-passe padrão
        $validatedData['password'] = Hash::make('12345678');

        $user = $this->userRepository->create($validatedData);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Utilizador {$user->name} criado com sucesso.");
    }
    public function edit(User $user)
    {
        return view('Backend.Admin.Users.edit', [
            'user' => $user,
        ]);
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $user = $this->userRepository->update($user, $validatedData);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Utilizador {$user->name} atualizado com sucesso.");
    }
    public function destroy(User $user, Request $request)
    {
        abort_if($user->is($request->user()), 403, 'Não podes eliminar a tua própria conta.');
        $this->userRepository->delete($user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Utilizador {$user->name} eliminado com sucesso.");
    }
    public function show(User $user, Request $request)
    {
        abort_if($user->is($request->user()), 403, 'Não podes visualizar a tua própria conta, faz isso na tua área de utilizador.');
        return view('Backend.Admin.Users.show', [
            'user' => $user,
        ]);
    }
}