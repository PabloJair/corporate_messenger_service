<?php

namespace App;

use App\validator\IValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class UserStatus extends Model implements IValidator
{
    protected $table        = 'cat_status_user';
    protected $primaryKey   = 'id_status_user';
    public $timestamps      = false;
    public $incrementing    = true;
    protected $keyType      = 'int';


    protected $fillable = ['name_status_user','description_status_user'];


    public function validateDeleted(array $data)
    {
        return Validator::make($data, [
            'id_status_user'    =>'exists:'. $this->table . ',id_status_user',
        ]);
    }

    public function validateUpdated(array $data)
    {
        return Validator::make($data, [
            'name_company'     => ['required',
                'max:150',
                'unique:' . $this->table . ',name_status_user'],
            '    exists:'. $this->table . ',id_status_user',

            'description_status_user'    =>'required|max:255',
        ]);

    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'name_status_user'     => ['required',
                'max:150',
                'unique:' . $this->table . ',name_area'],
            'description_status_user'    =>'required|max:255',
        ]);

    }

}
