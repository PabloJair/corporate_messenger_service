<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewUserInformation extends Model
{
    protected $table = 'view_users_information';


    protected  $hidden = [
        'password'
    ];

}
