<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'slug'
    ];

    /**
     * Get the owner of the team
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the members that belong to a particular team
     */
    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    /**
     * Get the designs for the team
     */
    public function designs()
    {
        return $this->hasMany(Design::class);
    }

    /**
     * Check if a team has a particular user/member.
     */
    public function hasUser(User $user)
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->first() ? true : false;
    }
}
