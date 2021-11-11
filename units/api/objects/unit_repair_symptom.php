<?php 
class unit_repair_symptom
{
    public $amount;
    public $description;

    function __construct($amount, $description)
    {
        $this -> amount = $amount;
        $this -> description = $description;
    }

}