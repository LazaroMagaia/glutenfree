<?php
namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 15) : LengthAwarePaginator;
    public function create(array $data) : User;
    public function findByLogin(string $login) : ?User;   // email ou telefone
    public function update(User $user, array $data) : User;
    public function delete(User $user) : bool;
    public function admins() : Collection;
}