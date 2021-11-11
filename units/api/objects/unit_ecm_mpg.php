<?php 
class unit_ecm_mpg
{
    public $mpg;
    public $week;
    public $year;

    function __construct($year, $week, $distance, $gallons)
    {
        $this -> week = $week;
        $this -> year = $year;
        $this -> mpg = $distance / $gallons;

    }

}