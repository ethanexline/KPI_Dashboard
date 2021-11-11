<?php
require_once("./objects/unit_detail.php"); //Order Count  object

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class unitDetailController
{
    function get_unit_detail($unit)
    {
        $database = new database();
        $connection = $database -> connection();
        $unit_detail_return = array();
        
        $sql = "
                SELECT 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}, 
                {REDACTED}
                FROM [{REDACTED}].[{REDACTED}].[{REDACTED}]
                WHERE {REDACTED} = '" . $unit . "'";

        $results = sqlsrv_query($connection, $sql);

        if ($results) {
            while($unit_detail_object = sqlsrv_fetch_object($results))
            {
                $unit_detail_return = new unit_detail($unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, 
                $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, 
                $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, 
                $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, 
                $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED}, 
                $unit_detail_object -> {REDACTED}, $unit_detail_object -> {REDACTED});
            }

        }          
        return $unit_detail_return;
    }

    function update_unit_detail($unnum, $lender, $depreciate_per_mile, $projected_trade_date, $acquisition_price, $acquisition_date, $loan_no, $interest_rate, 
                                $term_of_loan, $sell_price, $trade_type)
    {
        $database = new database();
        $connection = $database -> connection();
        $parameters = array();

        $sql = "UPDATE [{REDACTED}].[{REDACTED}].[{REDACTED}] SET ";
        
        if ($lender != null and $lender != "") {
            $sql .= "[{REDACTED}] = ?, ";
            array_push($parameters, $lender);
        } 

        if ($depreciate_per_mile != null and $depreciate_per_mile != "") {
            $sql .= "[{REDACTED}] = ?, ";
            array_push($parameters, $depreciate_per_mile);
        } 

        if ($projected_trade_date != null and $projected_trade_date != "") {
            $sql .= "[{REDACTED}] = ?, ";
            array_push($parameters, $projected_trade_date);
        } 

        if ($acquisition_price != null and $acquisition_price != "") {
            $sql .= "[{REDACTED}] = ?, ";
            array_push($parameters, $acquisition_price);
        } 

        if ($acquisition_date != null and $acquisition_date != "") {
            $sql .= "[{REDACTED}] = ?, ";
            array_push($parameters, $acquisition_date);
        } 

        if ($loan_no != null and $loan_no != "") {
            $sql .= "[{REDACTED}] = ?, ";
            array_push($parameters, $loan_no);
        } 

        if ($interest_rate != null and $interest_rate != "") {
            $sql .= "[{REDACTED}] = ?, ";
            array_push($parameters, $interest_rate);
        } 

        if ($term_of_loan != null and $term_of_loan != "") {
            $sql .= "[{REDACTED}] = ?, ";
            array_push($parameters, $term_of_loan);
        } 

        if ($sell_price != null and $sell_price != "") {
            $sql .= "[{REDACTED}] = ?, ";
            array_push($parameters, $sell_price);
        } 

        if ($trade_type != null and $trade_type != "") {
            $sql .= "[{REDACTED}] = ?, ";
            array_push($parameters, $trade_type);
        } 

        $trimmed_sql = rtrim($sql, ", ");
        $trimmed_sql .= " WHERE [{REDACTED}] = ?";
        array_push($parameters, $unnum);

        $results = sqlsrv_query($connection, $trimmed_sql, $parameters);

        if ($results === false) {
            return "error";
        }

        $rows_affected = sqlsrv_rows_affected($results);

        if ($rows_affected === false) {
            return "error";
        }

        elseif ($rows_affected === 0) {
            return "no such unit";
        }
        
        else {
            return "worked";
        }
    }

    //Implementation of the POST request
    function post($unit)
    {
        $decodeUnit = json_decode($unit);
        echo json_encode($this -> get_unit_detail($decodeUnit));
    }

    function update()
    {
        echo json_encode($this -> update_unit_detail($_POST['unnum'], $_POST['lnd'], $_POST['depr'], $_POST['projected_trade_date'], $_POST['aquisition_price'],
        $_POST['acquisition_date'], $_POST['loan_num'], $_POST['interest_rate'], $_POST['loan_term'], $_POST['sell_price'], $_POST['trade_type']));
    }
}
