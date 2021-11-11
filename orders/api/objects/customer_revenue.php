<?php 
class customer_revenue
{
    public $customer;
    public $amount;

    function __construct($amount, $customer)
    {
        $this -> amount = $amount;
        $this -> customer = $customer;
    }

}