<?php 
class unit_idle
{
    public $idle;
    
    public $week;
    public $year;

    function __construct($week, $year, $idle, $engine_time)
    {
        $this -> week = $week;
        $this -> year = $year;
        $this -> idle = $idle / $engine_time;

    }

}