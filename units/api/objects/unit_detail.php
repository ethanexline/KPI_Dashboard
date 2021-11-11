<?php 
class unit_detail
{
    public $unnum;
    public $unyear;
    public $unmake;
    public $unmodel;
    public $unstrmiles;
    public $unendmiles;
    public $unfstloaddte;
    public $untermdte;
    public $unit_delete_status;
    public $serial_number;
    public $lender;
    public $depreciate_per_mile;
    public $projected_trade_date;
    public $acquisition_price;
    public $acquisition_date;
    public $loan_no;
    public $interest_rate;
    public $term_of_loan;
    public $sell_price;
    public $trade_type;

    function __construct($unnum, $unyear, $unmake, $unmodel, $unstrmiles, $unendmiles, $unfstloaddte, $untermdte, $unit_delete_status, $serial_number, 
                         $lender, $depreciate_per_mile, $projected_trade_date, $acquisition_price, $acquisition_date, $loan_no, $interest_rate, 
                         $term_of_loan, $sell_price, $trade_type)
    {
        $this -> unnum = $unnum;
        $this -> unyear = $unyear;
        $this -> unmake = $unmake;
        $this -> unmodel = $unmodel;
        $this -> unstrmiles = $unstrmiles;
        $this -> unendmiles = $unendmiles;
        $this -> unfstloaddte = $unfstloaddte;
        $this -> untermdte = $untermdte;
        $this -> unit_delete_status = $unit_delete_status;
        $this -> serial_number = $serial_number;
        $this -> lender = $lender;
        $this -> depreciate_per_mile = $depreciate_per_mile;
        $this -> projected_trade_date = $projected_trade_date;
        $this -> acquisition_price = $acquisition_price;
        $this -> acquisition_date = $acquisition_date;
        $this -> loan_no = $loan_no;
        $this -> interest_rate = $interest_rate;
        $this -> term_of_loan = $term_of_loan;
        $this -> sell_price = $sell_price;
        $this -> trade_type = $trade_type;
    }

}