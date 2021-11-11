<?php 
class rate_per_mile
{
    public $amount;
    public $average;

    function __construct($amount, $average)
    {
        $this -> amount = $amount;
        $this -> average = $average;
    }

}