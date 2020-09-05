<?php

namespace App;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class   User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = "user";
    // Rest omitted for brevitypublic static function where()

    protected  $fillable = [
       'id_user',
        'no_employee',
        'name',
        'paternal_surname',
        'maternal_surname',
        'password',
        'email',
        'photo_path',
        'status_user',

    ];

    protected  $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
