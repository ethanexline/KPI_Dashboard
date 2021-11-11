<?php
class order_count
{
    public $year;
    public $week;
    public $amount;

    function __construct($year, $week, $amount)
    {
        $this -> year = $year;
        $this -> week = $week;
        $this -> amount = $amount;

    }
    
}



?>