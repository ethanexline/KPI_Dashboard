<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class FuelDailyCostController
{
    function get_fuel_daily_cost($sort)
    {
        $parameters = array();

        if($sort != null)
        {
            $database = new database();
            $connection = $database -> connection();

            $sql_where = "WHERE {REDACTED} IS NOT NULL ";

            //Dates
            if($sort-> start_date != '' && $sort-> end_date != '')
            {
                $sql_where .= " and date between ? and ? ";
                array_push($parameters,  $sort -> start_date);
                array_push($parameters,  $sort -> end_date);

            }
            elseif ($sort -> start_date != '' && $sort -> end_date == '')
            {
                $sql_where .= " and date = ? ";
                array_push($parameters,  $sort -> start_date);

            }
            elseif ($sort -> start_date == '' && $sort -> end_date != '')
            {
                $sql_where .= " and date = ? ";
                array_push($parameters,  $sort -> end_date);
            }

            //Chain ID
            if($sort -> chain_id != '')
            {
                $sql_where .= " AND substring(ft.{REDACTED},1,2) in (select {REDACTED} from {REDACTED}  where {REDACTED} = ? or {REDACTED} = ?) 
                                AND ft.{REDACTED} != fc.{REDACTED}";
                array_push($parameters, $sort -> chain_id);
                array_push($parameters, $sort -> chain_id);
            }

            //Stop ID
            if($sort -> stop_id != '')
            {
                $sql_where .= " AND {REDACTED} = ? ";
                array_push($parameters, $sort -> stop_id);
            }

            //Stop Name
            if($sort -> stop_name != '')
            {
                $sql_where .= " AND {REDACTED} like ? ";
                array_push($parameters, '%' . $sort -> stop_name . '%');
            }

            //City
            if($sort -> city != '')
            {
                $sql_where .= " AND LOWER({REDACTED}) LIKE ? ";
                array_push($parameters, '' . strtolower($sort -> city) . '%');
            }

            //State
            if(count($sort -> states) > 0)
            {
                $sql_2 = "(";
                foreach($sort -> states as $stat)
                {
                    $sql_2 .= "?,";
                    array_push($parameters, $stat);

                }
                $sql_2 = substr_replace($sql_2 ,"",-1); //Remove the very last comma, as it will cause an error from SQL Server.
                $sql_2 .= ")";
                $sql_where .= " AND LOWER({REDACTED}) IN  " . $sql_2;
                
            }

            //Unit Number
            if($sort -> unit_number != '')
            {
                $sql_where .= " AND {REDACTED} = ? ";
                array_push($parameters, $sort -> unit_number);
            }

            //Driver Code
            if($sort -> driver_code != '')
            {
                $sql_where .= " AND LOWER({REDACTED}) LIKE ? ";
                array_push($parameters, '%' . strtolower($sort -> driver_code) . '%');
            }

            //Driver Name
            if($sort -> driver_name != '')
            {
                $sql_where .= " AND LOWER({REDACTED}) LIKE ? ";
                array_push($parameters, '%' . strtolower($sort -> driver_name) . '%');
            }

            if($sort -> company != '')
            {
                if($sort -> company == '102')
                {
                    $sql_where .= " AND td.{REDACTED}  = 102 ";
                }
                elseif($sort -> company == '*')
                {
                    $sql_where .= " AND td.{REDACTED}  not like 'BUM' ";
                }
                elseif($sort -> company == '301')
                {
                    $sql_where .= " AND td.{REDACTED}  = 301 ";
                }
            }

            //Terminal
            if(count($sort -> terminals) > 0)
            {
                $nullFlag = FALSE;
                $terminals = $sort -> terminals;
                $sql_2 = "(";
                foreach($sort -> terminals as $terminal)
                {
                    if ($terminal == "No Terminal") {
                        $nullFlag = TRUE;
                    }
                    $sql_2 .= "?,";
                    array_push($parameters, $terminal);

                }
                $sql_2 = substr_replace($sql_2 ,"",-1); //Remove the very last comma, as it will cause an error from SQL Server.
                if ($nullFlag) {
                    $sql_2 .= ")";
                }
                else {
                    $sql_2 .= "))";
                }
                
                $sql_where .= " AND (substring({REDACTED},1,3) IN  " . $sql_2;
                if ($nullFlag) {
                    $sql_where .= " OR {REDACTED} IS NULL)";
                }
                
            }

            if(count($sort -> divisions) > 0)
            {
                $divisions = $sort -> divisions;
                $sql_2 = "(";
                foreach($sort -> divisions as $division)
                {
                    $sql_2 .= "?,";
                    array_push($parameters, $division);
                }

                $sql_2 = substr_replace($sql_2, "", -1);
                $sql_2 .= ")";
                $sql_where .= " AND {REDACTED} IN " . $sql_2;
            }

            //Fees
            if($sort -> fees != '')
            {
                if($sort -> fees == 'true')
                {
                    $sql_where .= " AND fees > 0 ";
                }
                elseif($sort -> fees == 'false')
                {
                    $sql_where .= " AND fees <= 0 ";
                }
                else
                {
                    //Do nothing because the developer is an idiot and didn't pass a valid value.....
                }
            }

            //Reefer
            if($sort -> reefer != '')
            {
                if($sort -> reefer == 'true')
                {
                    $sql_where .= " AND {REDACTED} > 0 ";
                }
                elseif($sort -> reefer == 'false')
                {
                    $sql_where .= " AND {REDACTED} <= 0 ";
                }
                else
                {
                    //Do nothing because the developer is an idiot and didn't pass a valid value.....
                }
            }

            //DEF
            if($sort -> def != '')
            {
                if($sort -> def == 'I') //DEF was purchased
                {
                    $sql_where .= " AND {REDACTED} > 0 ";
                }
                elseif($sort -> def == 'O') //Only DEF was purchased
                {
                    $sql_where .= " AND ({REDACTED} > 0 and ({REDACTED} = 0 or {REDACTED} = {REDACTED})) ";
                }
                elseif($sort -> def == 'E') //Exclude DEF-Only
                {
                    $sql_where .= " AND NOT ({REDACTED} > 0 AND {REDACTED} = {REDACTED}) ";
                }
                elseif($sort -> def == 'B') //DEF and other fuel was purchased
                {
                    $sql_where .= " AND {REDACTED} > 0 AND {REDACTED} > {REDACTED}";
                }
                else
                {
                    //Do nothing because the developer is an idiot and didn't pass a valid value.....
                }
            }

            //Non-Network Stops
            if($sort -> non_network != '')
            {
                if($sort -> non_network == 'O')
                {
                    $sql_where .= " AND (({REDACTED} != 'T' and fc.{REDACTED} is null) or fc.{REDACTED} != 'Y') ";
                }
                else if($sort -> non_network == 'E')
                {
                    $sql_where .= " AND ({REDACTED} = 'T' or fc.{REDACTED} = 'Y') ";
                }
            }

            //Non-Fuel Purchases
            if($sort -> non_fuel != '')
            {
                if($sort -> non_fuel == 'O')
                {
                    $sql_where .= " AND {REDACTED} > 0 and {REDACTED} + {REDACTED} + {REDACTED} = 0 ";
                }
                else if($sort -> non_fuel == 'E')
                {
                    $sql_where .= " AND {REDACTED} > 0 and {REDACTED} + {REDACTED} + {REDACTED} > 0 ";
                }
            }

            //Bulk Fuel purchases
            if($sort -> bulk)
            {
                if($sort -> bulk == 'O')
                {
                    $sql_where .= " AND {REDACTED} = 'T' ";
                }
                else if($sort -> bulk == 'E')
                {
                    $sql_where .= " AND {REDACTED} != 'T' ";
                }
            }

            $sql = "
            SELECT 
            substring(cast(date as varchar), 1, 10) 'date',
            
            sum(case when total_fuel_cost - other_fuel_cost = 0 then 0 else ((total_fuel_cost - other_fuel_cost) - case when {REDACTED} = 0 then 0 else 
                (((total_gallons - other_gallons) / ((total_gallons - other_gallons) + reefer_gallons)) * {REDACTED}) end) end) 'tractor_cost',

            sum(case when total_reefer_cost = 0 then 0 else (total_reefer_cost - case when {REDACTED} = 0 then 0 else (1 - (case when ((total_gallons - other_gallons) + 
                reefer_gallons) = 0 then 0 else (((total_gallons - other_gallons) / 
                ((total_gallons - other_gallons) + reefer_gallons))) end)) * {REDACTED} end) end) 'reefer_cost',

            sum(other_fuel_cost) 'DEF_cost',

            sum(cash_advance_amount + cash_advance_fee) 'cash_advance',

            sum(product_1_cost + product_2_cost + product_3_cost + oil_cost + fees) 'all_else'

            FROM {REDACTED} ft
            join {REDACTED} dd on dd.{REDACTED} = substring({REDACTED}, 4, 3)
            left join {REDACTED}  fc on substring(ft.{REDACTED},1,2) = fc.{REDACTED}
            left join {REDACTED}  td on ft.{REDACTED}  = td.{REDACTED} 
            " . $sql_where . "
            group by date";

            //Records from the query
            $results = sqlsrv_query($connection, $sql, $parameters);
            $types = array();

            if ($results) {
                while($type = sqlsrv_fetch_object($results))
                    array_push($types, array($type -> date, $type -> tractor_cost, $type -> reefer_cost, $type -> DEF_cost, $type -> cash_advance, $type -> all_else));
            }

            return json_encode($types);
        }
        else
        {
            $error = new error_response('500', 'Request could not be processed, please check the API\'s input for errors.');
            http_response_code(500);
            return json_encode($error);
        }

    }

    //Validates that an attribute (variable) exists in a sorting object.
    //Must have the "error_response" object in the code.
    //PARAMS: $sort = The sorting object, $required_fields = array of the required fields. Must be all STRING values
    //Returns an error_response object listing the missing fields or "true" if all fields are present.
    function validate_sort($sort, $required_fields)
    {
        $missing_fields = array();

        foreach($required_fields as $field)
        {

            if(!property_exists($sort, $field))
            {
                array_push($missing_fields, $field);
            }
        }
        
        if(count($missing_fields) > 0)
        {
            return new error_response('500', 'Request could not be processed, missing ' . json_encode($missing_fields) .' variable(s) in the sort object.');
        }
        else
        {
            return true;
        }


    }
    
    function post()
    {
        $required_fields = array('start_date', 'end_date', 'chain_id', 'stop_id', 'stop_name', 
                                 'city', 'states', 'unit_number', 'driver_code', 'driver_name', 'terminals',
                                 'fees', 'reefer', 'def', 'non_network', 'non_fuel', 'bulk', 'company');

        if(!empty($_POST['sort']))
        {
            try
            {
                $sort = json_decode($_POST['sort']);
                //echo var_dump($sort);
                $validator = $this -> validate_sort($sort, $required_fields);
                if($validator === true)
                {
                    $transactions = $this -> get_fuel_daily_cost($sort);
                    return $transactions;
                }
                else
                {
                    http_response_code(500);
                    return json_encode($validator);
                }
            }
            catch (Exception $e)
            {
                $error = new error_response('500', $e);
                http_response_code(500);
                return json_encode($error);
            }

        }
        else
        {
            $error = new error_response('500', 'Request could not be processed, please include a "sort" JSON object');
            http_response_code(500);
            return json_encode($error);
        }

    }

}