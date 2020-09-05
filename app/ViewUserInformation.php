<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewUserInformation extends Model
{
    protected $table = 'view_permission_user_application';

    protected  $fillable = [
        'id_user', 'no_employee', 'name', 'paternal_surname','maternal_surname', 'email', 'photo_path',
        'name_area','icon_area','id_area',
        'name_company','id_company','logotype_company',
        'name_module','icon_module','id_module',
        'id_rol','name_rol',
        'status_user',
        'can_create','can_delete','can_select','can_update'
    ];

    protected  $hidden = [
        'password'
    ];

}
