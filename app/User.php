<?php

namespace App;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Uri;

#[Fillable([
    'name',
    'email',
    'password',
    'discord_id',
    'discord_avatar_url',
])]
#[Hidden([
    'password',
    'remember_token',
])]
#[UseFactory(UserFactory::class)]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function getAvatarUrl(int $size): string
    {
        return Uri::of($this->discord_avatar_url)
            ->withQuery(['size' => $size]);
    }
}
