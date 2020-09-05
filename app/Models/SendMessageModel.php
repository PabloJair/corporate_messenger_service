<?php


namespace App\Models;



class SendMessageModel
{

    public $id_user_from = 0;
    public $id_user_to = 0;
    public $type_message = TypeMessage::NORMAL;
    public $message = "";

}
