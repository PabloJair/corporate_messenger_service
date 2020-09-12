<?php

namespace App;

use App\validator\IValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Company extends Model implements IValidator
{

    protected $table        = 'cat_company';
    protected $primaryKey   = 'id_company';
    public $timestamps      = false;
    public $incrementing    = true;
    protected $keyType      = 'int';


    protected $fillable = ['name_company','description_company','logotype_company','cod_company'];

    public function validateDeleted(array $data)
    {
        return Validator::make($data, [
            'id_company'    =>'exists:'. $this->table . ',id_company',
        ]);
    }

    public function validateUpdated(array $data)
    {
        return Validator::make($data, [
            'name_company'     => ['required',
                'max:150',
                'unique:' . $this->table . ',name_company'],
            '    exists:'. $this->table . ',id_company',

            'description_company'    =>'required|max:255',
        ]);

    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'name_company'     => ['required',
                'max:150',
                'unique:' . $this->table . ',name_area'],
            'description_company'    =>'required|max:255',
        ]);

    }
}
