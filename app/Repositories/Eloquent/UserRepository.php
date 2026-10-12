<?php
namespace App\Repositories\Eloquent;
use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    public function all() : Collection
    {
        return User::all();
    }
    public function paginate(int $perPage = 15) : LengthAwarePaginator
    {
        return User::paginate($perPage);
    }
    public function findByLogin(string $login) : ?User
    {
        $login = Str::lower($login);
        return filter_var($login, FILTER_VALIDATE_EMAIL)
            ? User::where('email', Str::lower($login))->first()
            : User::where('phone', User::normalizePhone($login))->first();
    }
    public function create(array $data) : User
    {
        return User::create($data);
    }
    public function update(User $user, array $data) : User
    {
        $user->update($data);
        return $user;
    }
    public function admins(): Collection
    {
        return User::where('role', UserRole::Admin)->get();
    }
    public function delete(User $user) : bool
    {
        return $user->delete();
    }
}