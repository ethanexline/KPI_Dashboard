<?php
class error_response
{
    public $error_code;
    public $error_message;

    function __construct($error_code, $error_message)
    {
        $this -> error_code = $error_code;
        $this -> error_message = $error_message;
    }
}