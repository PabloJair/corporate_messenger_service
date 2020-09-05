<?php


namespace App\Models;
use MyCLabs\Enum\Enum;

 class CodeResponse extends Enum
{
    const SUCCESS = 200;
    const ERROR = 300;
    const INCOMPLETE_DATA = 310;
    const ERROR_DATA = 320;


 }
