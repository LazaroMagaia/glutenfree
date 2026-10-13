<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use App\Enums\UserRole;
#[Fillable(['name', 'email', 'password', 'avatar', 'phone', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{   
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'role'              => UserRole::class,
        ];
    }
    public static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }
        if (strlen($digits) === 9) {          // 84 123 4567 -> 258...
            $digits = '258'.$digits;
        }

        return '+'.$digits;
    }
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }
    public function isCustomer(): bool
    {
        return $this->role === UserRole::User;
    }
    public function homeRoute(): string
    {
        return $this->isAdmin() ? 'admin.index' : 'user.index';
    }
    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->avatar) {
                return null;
            }

            // Se um dia guardares um URL completo (ex.: login social), usa-o tal como está
            if (str_starts_with($this->avatar, 'http')) {
                return $this->avatar;
            }

            return Storage::disk('public')->url($this->avatar);
        });
    }
}




