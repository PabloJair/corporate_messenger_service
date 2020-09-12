<?php

namespace App;

use App\validator\IValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class UserIformationCompanys extends Model implements IValidator
{


    protected $table        = 'info_user_company';
    protected $primaryKey   = 'id_info_user_company';
    public $timestamps      = false;
    public $incrementing    = true;
    protected $keyType      = 'int';

    protected $fillable = ['id_user','id_company','id_area','id_rol'];

    public function validateDeleted(array $data)
    {
        return Validator::make($data, [
            'id_info_user_company'    =>'exists:'. $this->table . ',id_info_user_company',
        ]);
    }

    public function validateUpdated(array $data)
    {

        return Validator::make($data, [
            'id_company_module'    =>'exists:'. $this->table . ',id_company_module',
            'id_info_user_company'    =>'exists:cat_status_user,id_info_user_company',
            'id_company'    =>'exists:cat_module,id_module',
            'id_module'    =>'exists:cat_company,id_company',
            'id_area'    =>'exists:cat_area,id_area',
            'id_rol'    =>'exists:cat_rol,id_rol',

        ]);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'id_info_user_company'    =>'exists:cat_status_user,id_info_user_company',
            'id_company'    =>'exists:cat_module,id_module',
            'id_module'    =>'exists:cat_company,id_company',
            'id_area'    =>'exists:cat_area,id_area',
            'id_rol'    =>'exists:cat_rol,id_rol',

        ]);    }
}
