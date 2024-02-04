<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'description'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function friends(): HasMany
    {
        return $this->hasMany(Friend::class, "user_id");
    }

    public function joined(): HasMany
    {
        return $this->hasMany(HasJoined::class, "user_id");
    }

    public function groups(): HasManyThrough
    {
        return $this->hasManyThrough(Group::class, HasJoined::class, "user_id", "id", "id", "group_id");
    }

    //no se si es funcional
    public function friendsUser(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Friend::class, "friend_id", "id", "id", "user_id");
    }

    public function userFriends(): HasMany
    {
        return $this->hasMany(Friend::class, "friend_id");
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function tokens(): MorphMany
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }
}
