<?php 
class state_revenue
{
    public $state;
    public $revenue;

    function __construct($state, $revenue)
    {
        $this -> state = $state;
        $this -> revenue = $revenue;

    }

}