<?php 
class loaded_miles
{
    public $miles;
    public $week;
    public $year;

    function __construct($miles, $week, $year)
    {
        $this -> miles = $miles;
        $this -> week = $week;
        $this -> year = $year;
    }

}