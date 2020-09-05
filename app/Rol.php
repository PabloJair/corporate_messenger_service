<?php

namespace App;

use App\validator\IValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Rol extends Model implements IValidator
{
    protected $table        = 'cat_rol';
    protected $primaryKey   = 'id_rol';
    public $timestamps      = false;
    public $incrementing    = true;
    protected $keyType      = 'int';


    protected $fillable = ['name_rol','description_rol'];

    public function validateDeleted(array $data)
    {
        return Validator::make($data, [
            'id_rol'    =>'exists:'. $this->table . ',id_rol',
        ]);
    }

    public function validateUpdated(array $data)
    {
        return Validator::make($data, [
            'name_rol'     => ['required',
                'max:150',
                'unique:' . $this->table . ',name_rol'],
            '    exists:'. $this->table . ',id_rol',

            'description_rol'    =>'required|max:255',
        ]);

    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'name_rol'     => ['required',
                'max:150',
                'unique:' . $this->table . ',name_area'],
            'description_rol'    =>'required|max:255',
        ]);

    }

}
