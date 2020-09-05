<?php


namespace App\validator;


interface IValidator
{
    public  function validateDeleted(array $data);
    public  function validateUpdated(array $data);
    public  function validate(array $data);


}
