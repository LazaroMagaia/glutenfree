<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function index(Request $request): View
    {
        return view('Backend.Perfil.index', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Só name, email e phone: o papel nunca pode ser alterado aqui.
        $data = collect($request->validated())
            ->only(['name', 'email', 'phone'])
            ->all();

        if ($request->hasFile('avatar')) {
            $newPath = $request->file('avatar')->store('avatars', 'public');
            $this->deleteAvatar($user);
            $data['avatar'] = $newPath;
        } elseif ($request->boolean('remove_avatar')) {
            $this->deleteAvatar($user);
            $data['avatar'] = null;
        }

        $this->userRepository->update($user, $data);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Perfil actualizado com sucesso.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $this->userRepository->update($request->user(), [
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Palavra-passe alterada com sucesso.');
    }

    private function deleteAvatar(User $user): void
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
    }
}