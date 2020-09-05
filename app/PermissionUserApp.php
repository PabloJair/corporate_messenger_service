<?php

namespace App;

use App\validator\IValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PermissionUserApp extends Model implements IValidator
{


    protected $table        = 'permission_user_application';
    protected $primaryKey   = 'id_permission_user_application';
    public $timestamps      = false;
    public $incrementing    = true;
    protected $keyType      = 'int';

    protected $fillable = ['id_info_user_company','id_company_module','can_delete','can_update','can_select','can_create'];

    public function validateDeleted(array $data)
    {
        return Validator::make($data, [
            'id_permission_user_application'    =>'exists:'. $this->table . ',id_info_user_company',
        ]);
    }

    public function validateUpdated(array $data)
    {

        return Validator::make($data, [
            'id_permission_user_application'    =>'exists:'. $this->table . ',id_company_module',
            'id_info_user_company'    =>'exists:info_user_company,id_info_user_company',
            'id_company_module'    =>'exists:company_module,id_company_module',

        ]);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'id_info_user_company'    =>'exists:info_user_company,id_info_user_company',
            'id_company_module'    =>'exists:company_module,id_company_module',

        ]);
    }

}
