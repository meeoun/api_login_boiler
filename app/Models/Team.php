<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::created(function ($team){
            auth()->user()->teams()->attach($team->id);
        });



    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function designs()
    {
        return $this->hasMany(Design::class);
    }

    public function hasUser(User $user)
    {
        return $this->members()->where('user_id',$user->id)->first() ? true : false;
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function  hasPendingInvite($email)
    {
        return (bool)$this->invitations()->where('recipient_email', $email)->count();
    }


}
