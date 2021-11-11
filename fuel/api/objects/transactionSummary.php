<?php
class transactionSummary {
    
    public $tractor_gallons;
    public $tractor_cost;
    public $reefer_gallons;
    public $reefer_cost;
    public $fuel_total_gallons;
    public $fuel_total_cost;
    public $def_gallons;
    public $def_cost;
    public $grand_total_gallons;
    public $grand_total_cost;
    public $fees;
    public $discPG;
    public $avgPPG;
    public $rebate;
    public $stops;

    function __construct($total_gallons, $other_gallons, $fuel_total_cost, $other_fuel_cost, $reefer_gallons, $reefer_cost, $rebate_amount, 
    $fees, $stops, $gals) {

        if ($rebate_amount > 0) {
            $this -> tractor_gallons = number_format(($total_gallons - $other_gallons), 2);
            $this -> tractor_cost = "$" . strval(number_format((($fuel_total_cost - $other_fuel_cost) - ((($total_gallons - $other_gallons) / $gals) * $rebate_amount)), 2));
            $this -> reefer_gallons = number_format($reefer_gallons, 2);
            $this -> reefer_cost = "$" . strval(number_format(($reefer_cost - ((1 - (($total_gallons - $other_gallons) / $gals)) * $rebate_amount)), 2));
            $this -> fuel_total_gallons = number_format($gals, 2);
            $this -> fuel_total_cost = "$" . strval(number_format(((($fuel_total_cost - $other_fuel_cost) - ((($total_gallons - $other_gallons) / $gals) * $rebate_amount)) + 
            ($reefer_cost - ((1 - (($total_gallons - $other_gallons) / $gals)) * $rebate_amount))), 2));
            $this -> def_gallons = number_format($other_gallons, 2);
            $this -> def_cost = "$" . strval(number_format($other_fuel_cost, 2));
            $this -> grand_total_gallons = number_format(($gals + $other_gallons), 2);
            $this -> grand_total_cost = "$" . strval(number_format(((($fuel_total_cost - $other_fuel_cost) - ((($total_gallons - $other_gallons) / $gals) * $rebate_amount)) + 
            ($reefer_cost - ((1 - (($total_gallons - $other_gallons) / $gals)) * $rebate_amount)) + $other_fuel_cost), 2));
            $this -> fees = "$" . strval(number_format($fees, 2));
            $this -> discPG = number_format(($rebate_amount / $gals), 4);
            $this -> avgPPG = number_format((((($fuel_total_cost - $other_fuel_cost) - ((($total_gallons - $other_gallons) / $gals) * $rebate_amount)) + 
            ($reefer_cost - ((1 - (($total_gallons - $other_gallons) / $gals)) * $rebate_amount))) / $gals), 3);
            $this -> rebate = "$" . strval(number_format($rebate_amount, 2));
            $this -> stops = number_format($stops);
        }
        
        else if ($gals > 0) {
            $this -> tractor_gallons = number_format(($total_gallons - $other_gallons), 2);
            $this -> tractor_cost = "$" . strval(number_format(($fuel_total_cost - $other_fuel_cost), 2));
            $this -> reefer_gallons = number_format($reefer_gallons, 2);
            $this -> reefer_cost = "$" . strval(number_format($reefer_cost, 2));
            $this -> fuel_total_gallons = number_format($gals, 2);
            $this -> fuel_total_cost = "$" . strval(number_format((($fuel_total_cost - $other_fuel_cost) + $reefer_cost), 2));
            $this -> def_gallons = number_format($other_gallons, 2);
            $this -> def_cost = "$" . strval(number_format($other_fuel_cost, 2));
            $this -> grand_total_gallons = number_format(($gals + $other_gallons), 2);
            $this -> grand_total_cost = "$" . strval(number_format(($fuel_total_cost + $reefer_cost + $other_fuel_cost), 2));
            $this -> fees = "$" . strval(number_format($fees, 2));
            $this -> discPG = ".0000";
            $this -> avgPPG = number_format(((($fuel_total_cost - $other_fuel_cost) + $reefer_cost) / $gals), 3);
            $this -> rebate = "$0.00";
            $this -> stops = number_format($stops);
        }

        else {
            $this -> tractor_gallons = "0.00";
            $this -> tractor_cost = "$0.00";
            $this -> reefer_gallons = "0.00";
            $this -> reefer_cost = "$0.00";
            $this -> fuel_total_gallons = "0.00";
            $this -> fuel_total_cost = "$0.00";
            $this -> def_gallons = number_format($other_gallons, 2);
            $this -> def_cost = "$" . strval(number_format($other_fuel_cost, 2));
            $this -> grand_total_gallons = "0.00";
            $this -> grand_total_cost = "$" . strval(number_format(($fuel_total_cost + $reefer_cost + $other_fuel_cost), 2));
            $this -> fees = "$" . strval(number_format($fees, 2));
            $this -> discPG = ".0000";
            $this -> avgPPG = ".000";
            $this -> rebate = "$0.00";
            $this -> stops = number_format($stops);
        }
    }
}