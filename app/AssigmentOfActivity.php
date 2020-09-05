<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssigmentOfActivity extends Model
{
    protected $table        = 'assigment_of_activities';
    protected $primaryKey   = 'id_assgiment_of_activity';
    public $timestamps      = false;
    public $incrementing    = true;
    protected $keyType      = 'int';


    protected $fillable = ['id_user','type_activity','status_activity','start_date','end_date','start_time','end_time','notes','stated_date','finish_date'];


}
