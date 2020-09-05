<?php

namespace App;

use App\validator\IValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CompanyModule extends Model implements IValidator
{
    protected $table        = 'company_module';
    protected $primaryKey   = 'id_company_module';
    public $timestamps      = false;
    public $incrementing    = true;
    protected $keyType      = 'int';


    protected $fillable = ['id_company','id_module'];

    public function validateDeleted(array $data)
    {
        return Validator::make($data, [
            'id_company_module'    =>'exists:'. $this->table . ',id_company_module',
        ]);
    }

    public function validateUpdated(array $data)
    {
        return Validator::make($data, [
            'id_company_module'    =>'exists:'. $this->table . ',id_company_module',
            'id_company'    =>'exists:cat_module,id_module',
            'id_module'    =>'exists:cat_company,id_company',

        ]);

    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'id_company'    =>'exists:cat_module,id_module',
            'id_module'    =>'exists:cat_company,id_company',
        ]);

    }


}
