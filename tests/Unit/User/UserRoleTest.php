<?php
namespace Tests\Unit\User;

use App\Models\User;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    public function test_admin_goes_to_admin_index()
    {
        $user = User::factory()->admin()->make();

        $this->assertTrue($user->isAdmin());
        $this->assertSame('admin.index', $user->homeRoute());
    }
     public function test_normal_user_goes_to_user_index(): void
    {
        $user = User::factory()->make();

        $this->assertFalse($user->isAdmin());
        $this->assertSame('user.index', $user->homeRoute());
    }
}