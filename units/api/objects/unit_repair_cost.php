<?php 
class unit_repair_cost
{
    public $amount;
    public $year;
    public $week;
    function __construct($amount, $year, $week)
    {
        $this -> amount = $amount;
        $this -> year = $year;
        $this -> week = $week;

    }

}