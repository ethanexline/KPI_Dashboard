<?php 
class daily_revenue
{
    public $amount;
    public $dayName;
    public $date;

    function __construct($amount, $dayName, $date)
    {
        $this -> amount = $amount;
        $this -> dayName = $dayName;
        $this -> date = $date;
    }

}