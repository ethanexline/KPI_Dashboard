<?php
require_once('utility/settings.php');

class utils
{    
    function MakeUrlRandom($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    function MakeUrlVersion()
    {
        $settings = new settings();
        return '?seq=' . $settings -> getVersionNumber();
    }
}
