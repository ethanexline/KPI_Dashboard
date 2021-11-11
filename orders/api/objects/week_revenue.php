<?php 
class week_revenue
{
    public $amount;
    public $week;
    public $year;

    function __construct($amount, $week, $year)
    {
        $this -> amount = $amount;
        $this -> week = $week;
        $this -> year = $year;
    }

}