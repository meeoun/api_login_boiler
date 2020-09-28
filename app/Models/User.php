<?php

namespace App\Models;


use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{


    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','tagline','about', 'user_name', 'location', 'formatted_address', 'available_to_hire'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected $geometry = ['location'];
    protected $geometryAsText = true;

    public function newQuery()
    {
        if (!empty($this->geometry) && $this->geometryAsText === true) {
            $raw = '';
            foreach ($this->geometry as $column) {
                $raw .= 'ST_AsText(`' . $this->table . '`.`' . $column . '`) as `' . $column . '`, ';
            }
            $raw = substr($raw, 0, -2);

            return parent::newQuery()->addSelect('*', DB::raw($raw));
        }
    }


    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'participants');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getUserChat($user_id)
    {
        $chat = $this->chats()
            ->whereHas('participants', function ($query) use ($user_id){
                $query->where('user_id', $user_id);
            })->first();

        return $chat;
    }




    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);

    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }


    public function getJWTIdentifier()
    {
       return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getLocationCoordinatesAttribute()
    {
        return array("longitude"=>$this->getLongitude(), "latitude"=>$this->getLatitude());
    }

    private function getLongitude()
    {
        if($this->location) {
            $value = $this->location->getValue();
            $x = str_replace("POINT(", "", $value);
            $x = substr($x, 0, strpos($x, ","));
            return $x;
        }
        return null;
    }

    private function getLatitude()
    {
        if($this->location)
        {
            $value = $this->location->getValue();
            $y = explode(",", $value);
            $y = str_replace(" ","", $y[1]);
            return rtrim($y,')');
        }
        return null;
    }


    public function designs()
    {
        return $this->hasMany(Design::class);
    }


    public static function boot() {
        parent::boot();

        static::creating( function( $model ) {

            $model->formatLocation();

        } );

        static::updating( function( $model ) {

            $model->formatLocation();

        } );
    }

    protected function formatLocation() {
        if($this->x && $this->y) {
            $this->location = DB::raw('POINT(' . $this->x . ', ' . $this->y . ')');
            unset($this->y);
            unset($this->x);
        }
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    public function ownedTeams()
    {
        return $this->teams()->where('owner_id', $this->id);
    }

    public function teamOwner(Team $team)
    {

        return (bool)$this->teams()
            ->where('teams.id', $team->id)
            ->where('owner_id', $this->id)
            ->count();
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'recipient_email', 'email');
    }



}
