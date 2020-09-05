<?php


namespace App\Models;


class ResponseModel
{

    public $Code = CodeResponse::ERROR;
    public $Message = '';
    public $Data;
    public $Error;


    public function __construct($Code,$Message,$Data =null,$Error = null){
        $this->Code     = $Code;
        $this->Message  = $Message;
        $this->Data     = $Data;
        $this->Error     = $Error;


    }

}
