<?php
namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Notifikasi;

#[Fillable([
    'username',
    'name',
    'email',
    'password',
    'role',
    'status'
])]

#[Hidden([
    'password',
    'remember_token'
])]

class User extends Authenticatable
{
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

            // sementara jangan hashed dulu
            // supaya login plain text jalan
            // 'password' => 'hashed',
        ];
    }
    public function notifikasi()
{
    return $this->hasMany(
        Notifikasi::class,
        'id_user'
    );
}
}