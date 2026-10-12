<?php
namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_creates_user(): void
    {
        $repository = app(UserRepositoryInterface::class);

        $user = $repository->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '827017601',
            'password' => bcrypt('password123'),
        ]);

        $this->assertInstanceOf(User::class, $user);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
    public function test_repository_updates_user(): void
    {
        $repository = app(UserRepositoryInterface::class);

        // Criar um usuário para testar
        $user = User::create([
            'name' => 'User Original',
            'email' => 'original@example.com',
            'phone' => '827017601',
            'password' => bcrypt('password123'),
        ]);

        // Atualizar os dados do usuário
        $updatedUser = $repository->update($user, [
            'name' => 'User Atualizado',
            'email' => 'updated@example.com',
        ]);

        // Verificar se o método devolveu um usuário
        $this->assertInstanceOf(User::class, $updatedUser);

        // Verificar os dados atualizados no objeto
        $this->assertSame('User Atualizado', $updatedUser->name);
        $this->assertSame('updated@example.com', $updatedUser->email);

        // Verificar se os dados foram realmente atualizados no banco
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'User Atualizado',
            'email' => 'updated@example.com',
        ]);

        // Garantir que os dados antigos já não existem para esse usuário
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => 'original@example.com',
        ]);
    }
}
