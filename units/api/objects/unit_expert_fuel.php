<?php 
class unit_expert_fuel
{
    public $compliant_percent;
    public $week;
    public $year;

    function __construct($year, $week, $compliant, $total)
    {
        $this -> week = $week;
        $this -> year = $year;
        if ($total != 0) {
            $this -> compliant_percent = $compliant / $total;
        }
        else {
            $this -> compliant_percent = $compliant / 1;
        }

    }

}