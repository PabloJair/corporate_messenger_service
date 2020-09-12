<?php

namespace App;
use App\Mail\RecoveryPasswordNotification;
use App\validator\IValidator;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class   User extends Authenticatable implements JWTSubject,IValidator
{
    use Notifiable, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = "user";
    // Rest omitted for brevitypublic static function where()

    protected $primaryKey   = 'id_user';
    protected $guarded= ['cod_company'];

    protected  $fillable = [

        'no_employee',
        'name',
        'phone_number',
        'paternal_surname',
        'maternal_surname',
        'password',
        'email',
        'photo_path',
        'status_user',
        'device_id',
        'active',
        'activation_token'

    ];


    public function validatePhoto(array $data)
    {
        return Validator::make($data, [
            'image' => 'image|required|mimes:jpeg,png'

        ]);

    }

    protected $hidden = [
        'password', 'remember_token', 'activation_token'
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

    public function validateDeleted(array $data)
    {
        return Validator::make($data, [
            'id_company'    =>'exists:'. $this->table . ',id_company',
        ]);
    }

    public function validateUpdated(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required',
                'max:150',
                'unique:' . $this->table . ',name_company'],
            'description_company'    =>'required|max:255',
        ]);

    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'name'=>['required', 'max:150'],
            'paternal_surname'=>['required', 'max:150'],
            'maternal_surname'=>['required', 'max:150'],
            'password'=>['required', 'max:2048'],
            'email'     => ['required',
                'max:150',
                'unique:' . $this->table . ',email'],

        ]);

    }



    public function routeNotificationForFcm()
    {
        return $this->device_id;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new RecoveryPasswordNotification($token));
    }
}
