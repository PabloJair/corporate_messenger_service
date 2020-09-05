<?php

namespace App;

use App\validator\IValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Area extends Model implements IValidator
{
    protected $table        = 'cat_area';
    protected $primaryKey   = 'id_area';
    public $timestamps      = false;
    public $incrementing    = true;
    protected $keyType      = 'int';



    protected $fillable = ['name_area','icon_area','description_area'];


    public function validate(array $data)
    {

        return Validator::make($data, [
            'name_area'     => ['required',
                                'max:150',
                                'unique:' . $this->table . ',name_area'],
            'description_area'    =>'required|max:255',
        ]);
    }

    public function validateDeleted(array $data)
    {
        return Validator::make($data, [
            'id_area'    =>'exists:'. $this->table . ',id_area',
        ]);
    }

    public function validateUpdated(array $data)
    {
        return Validator::make($data, [
            'name_area'     => ['required',
                'max:150',
                'unique:' . $this->table . ',name_area'],
            '    exists:'. $this->table . ',id_area',

                'description_area'    =>'required|max:255',
        ]);
    }
}
