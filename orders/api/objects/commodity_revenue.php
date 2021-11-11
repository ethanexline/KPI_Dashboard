<?php 
class commodity_revenue
{
    public $commodity;
    public $amount;

    function __construct($amount, $commodity)
    {
        $this -> amount = $amount;
        $this -> commodity = $commodity;
    }

}