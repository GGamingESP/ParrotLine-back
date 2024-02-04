<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function hasJoineds(): HasMany
    {
        return $this->hasMany(HasJoined::class);
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, HasJoined::class, 'group_id', 'id', 'id', 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
