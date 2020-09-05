<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table        = 'message';
    protected $primaryKey   = 'id_message';
    public $timestamps      = false;
    public $incrementing    = true;
    protected $keyType      = 'int';


    protected $fillable = ['text_message','time_stamp'];
    protected $guarded= ['id_user'];
}
