<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewInfoUserCompany extends Model
{
    protected $table = 'view_info_user_company';

    protected  $fillable = [
        'id_user', 'no_employee', 'name', 'paternal_surname','maternal_surname', 'email', 'photo_path',
        'name_area','icon_area','id_area',
        'name_company','id_company','logotype_company',
        'name_module','icon_module','id_module',
        'id_rol','name_rol',
        'status_user',
    ];


    protected  $hidden = [
        'password'
    ];

}
