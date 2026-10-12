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

    public function paginateFiltered(
        ?string $search = null,
        ?string $role = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = User::query();

        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role !== null && $role !== '') {
            $query->where('role', $role);
        }

        return $query->latest()->paginate($perPage);
    }

    public function statistics(): array
    {
        return [
            'totalUsers' => User::count(),
            'totalAdmins' => User::where('role', UserRole::Admin)->count(),
            'totalCustomers' => User::where('role', 'user')->count(),
            'newUsers' => User::where(
                'created_at',
                '>=',
                now()->subDays(30)
            )->count(),
        ];
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
    public function findById(int $id) : ?User
    {
        return User::find($id);
    }
    public function delete(User $user) : bool
    {
        return $user->delete();
    }
}