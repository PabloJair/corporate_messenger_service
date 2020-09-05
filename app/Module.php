<?php

namespace App;

use App\validator\IValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Module extends Model implements IValidator
{
    protected $table        = 'cat_module';
    protected $primaryKey   = 'id_module';
    public $timestamps      = false;
    public $incrementing    = true;
    protected $keyType      = 'int';

    protected $fillable = ['name_module','icon_module','description_module'];

    public function validateDeleted(array $data)
    {
        return Validator::make($data, [
            'id_module'    =>'exists:'. $this->table . ',id_module',
        ]);
    }

    public function validateUpdated(array $data)
    {
        return Validator::make($data, [
            'name_module'     => ['required',
                'max:150',
                'unique:' . $this->table . ',name_module'],
            '    exists:'. $this->table . ',id_module',

            'description_module'    =>'required|max:255',
        ]);

    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'name_module'     => ['required',
                'max:150',
                'unique:' . $this->table . ',name_area'],
            'description_module'    =>'required|max:255',
        ]);

    }
}
