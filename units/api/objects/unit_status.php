<?php 
class unit_status
{
    public $status;
    public $count;
    public $year;
    public $week;

    function __construct($week, $year, $status, $count)
    {
        $this -> week = $week;
        $this -> year = $year;
        $this -> status = $status;
        $this -> count = $count;

    }

}