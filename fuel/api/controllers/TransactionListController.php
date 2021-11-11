<?php //Error response object

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class TransactionListController
{

    function get_transaction_list($sort)
    {
        $parameters = array();

        $record_num = $_POST['start'];
        $draw = $_POST['draw'];
        $length = $_POST['length'];

        if($record_num == 1)
        {
            $record_num = 0;
        }

        if($sort != null)
        {
            $database = new database();
            $connection = $database -> connection();

            $sql_where = "WHERE wh_transaction_id IS NOT NULL ";
            $sql_order_by = "";

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
                $sql_where .= " AND substring(ft.{REDACTED},1,2) in (select chain_id from {REDACTED}  where FuelChains.chain_id = ? or FuelChains.group_with_id = ?) 
                                AND ft.chain_id != fc.exclude_id";
                array_push($parameters, $sort -> chain_id);
                array_push($parameters, $sort -> chain_id);
            }

            //Stop ID
            if($sort -> stop_id != '')
            {
                $sql_where .= " AND stop_id = ? ";
                array_push($parameters, $sort -> stop_id);
            }

            //Stop Name
            if($sort -> stop_name != '')
            {
                $sql_where .= " AND stop_name like ? ";
                array_push($parameters, '%' . $sort -> stop_name . '%');
            }

            //City
            if($sort -> city != '')
            {
                $sql_where .= " AND LOWER(city) LIKE ? ";
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
                $sql_where .= " AND LOWER(state) IN  " . $sql_2;
                
            }

            //Unit Number
            if($sort -> unit_number != '')
            {
                $sql_where .= " AND unit_number = ? ";
                array_push($parameters, $sort -> unit_number);
            }

            //Driver Code
            if($sort -> driver_code != '')
            {
                $sql_where .= " AND LOWER(driver_code) LIKE ? ";
                array_push($parameters, '%' . strtolower($sort -> driver_code) . '%');
            }

            //Driver Name
            if($sort -> driver_name != '')
            {
                $sql_where .= " AND LOWER(driver_name) LIKE ? ";
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
                
                $sql_where .= " AND (substring(terminal,1,3) IN  " . $sql_2;
                if ($nullFlag) {
                    $sql_where .= " OR terminal IS NULL)";
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
                    $sql_where .= " AND reefer_gallons > 0 ";
                }
                elseif($sort -> reefer == 'false')
                {
                    $sql_where .= " AND reefer_gallons <= 0 ";
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
                    $sql_where .= " AND other_gallons > 0 ";
                }
                elseif($sort -> def == 'O') //Only DEF was purchased
                {
                    $sql_where .= " AND (other_gallons > 0 and (total_gallons = 0 or total_gallons = other_gallons)) ";
                }
                elseif($sort -> def == 'E') //Exclude DEF-Only
                {
                    $sql_where .= " AND NOT (other_gallons > 0 AND other_gallons = total_gallons) ";
                }
                elseif($sort -> def == 'B') //DEF and other fuel was purchased
                {
                    $sql_where .= " AND other_gallons > 0 AND total_gallons > other_gallons";
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
                    $sql_where .= " AND (({REDACTED} != 'T' and fc.{REDACTED} is null) or {REDACTED} != 'Y') ";
                }
                else if($sort -> non_network == 'E')
                {
                    $sql_where .= " AND ({REDACTED} = 'T' or {REDACTED} = 'Y') ";
                }
            }

            //Non-Fuel Purchases
            if($sort -> non_fuel != '')
            {
                if($sort -> non_fuel == 'O')
                {
                    $sql_where .= " AND total_amount_due > 0 and total_gallons + reefer_gallons + other_gallons = 0 ";
                }
                else if($sort -> non_fuel == 'E')
                {
                    $sql_where .= " AND total_amount_due > 0 and total_gallons + reefer_gallons + other_gallons > 0 ";
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

            //if a sortby option was selected
            if($sort -> sortby)
            {
                if($sort -> sortby == 'time_desc')
                {
                    $sql_order_by = " ORDER BY date DESC, time DESC";
                }

                else if($sort -> sortby == 'time_asc')
                {
                    $sql_order_by = " ORDER BY date ASC, time ASC";
                }

                else if($sort -> sortby == 'term')
                {
                    $sql_order_by = " ORDER BY {REDACTED}";
                }

                else if($sort -> sortby == 'stop')
                {
                    $sql_order_by = " ORDER BY {REDACTED}";
                }

                else if($sort -> sortby == 'driver')
                {
                    $sql_order_by = " ORDER BY {REDACTED}";
                }

                else if($sort -> sortby == 'city')
                {
                    $sql_order_by = " ORDER BY {REDACTED}";
                }

                else if($sort -> sortby == 'st')
                {
                    $sql_order_by = " ORDER BY {REDACTED}";
                }

                else if($sort -> sortby == 'trac_gals_desc')
                {
                    $sql_order_by = " ORDER BY ({REDACTED} - {REDACTED}) DESC";
                }

                else if($sort -> sortby == 'reef_gals_desc')
                {
                    $sql_order_by = " ORDER BY {REDACTED} DESC";
                }

                else if($sort -> sortby == 'DEF_gals_desc')
                {
                    $sql_order_by = " ORDER BY {REDACTED} DESC";
                }

                else if($sort -> sortby == 'trac_gals_asc')
                {
                    $sql_order_by = " ORDER BY ORDER BY ({REDACTED} - {REDACTED}) ASC";
                }

                else if($sort -> sortby == 'reef_gals_asc')
                {
                    $sql_order_by = " ORDER BY {REDACTED} ASC";
                }

                else if($sort -> sortby == 'DEF_gals_asc')
                {
                    $sql_order_by = " ORDER BY {REDACTED} ASC";
                }

                else if($sort -> sortby == 't_cost_desc')
                {
                    $sql_order_by = " ORDER BY {REDACTED} DESC";
                }

                else if($sort -> sortby == 't_cost_asc')
                {
                    $sql_order_by = " ORDER BY {REDACTED} ASC";
                }
            }
            else 
            {
                $sql_order_by = " ORDER BY date DESC, time DESC";
            }

            array_push($parameters,  $record_num);

            $transactions = array();

            $sql = "
            SELECT
            {REDACTED},
                cast(date as varchar) 'date', 
                substring(right('0000' +cast({REDACTED} as varchar),4), 1,2) + ':' + substring(right('0000' +cast({REDACTED} as varchar),4), 3,2) 'time',
                {REDACTED},
                {REDACTED},
                {REDACTED},
                {REDACTED}, 
                {REDACTED},
                ft.{REDACTED},
                {REDACTED},
                {REDACTED}, 
                {REDACTED},
                case when {REDACTED} > 0 then 'Y' else 'N' end 'cash_advance_flag',
                {REDACTED},
                case when (({REDACTED} != 'T' and fc.{REDACTED} is null) or {REDACTED} != 'Y') then 'N'
                when ({REDACTED} = 'T' or {REDACTED} = 'Y') then 'Y'
                else 'N'
                end 'in_network',
                {REDACTED},
                {REDACTED},
                {REDACTED},
                {REDACTED},
                {REDACTED},
                {REDACTED},
                {REDACTED},
                {REDACTED},
                {REDACTED} + {REDACTED} + {REDACTED} + {REDACTED} 'misc_cost',
                {REDACTED},
                {REDACTED},
                {REDACTED},
                {REDACTED},
                {REDACTED},
                {REDACTED}
            FROM {REDACTED} ft
            join {REDACTED} dd on dd.{REDACTED} = substring({REDACTED}, 4, 3)
            left join {REDACTED}  fc on substring(ft.{REDACTED},1,2) = fc.{REDACTED}
            left join {REDACTED}  td on ft.{REDACTED}  = td.{REDACTED} 
            " . $sql_where . "
             " . $sql_order_by . "
             OFFSET cast(? as int) ROWS
             FETCH NEXT ". $length ." ROW ONLY
            ";
            
            //The count of the returned records with the filter
            $sql_count_filtered = "
                SELECT
                    count(*) 'count'
                FROM {REDACTED} ft
                join {REDACTED} dd on dd.{REDACTED} = substring({REDACTED}, 4, 3)
            left join {REDACTED}  fc on substring(ft.{REDACTED},1,2) = fc.{REDACTED}
                left join {REDACTED}  td on ft.{REDACTED}  = td.{REDACTED} 
                " . $sql_where . "
            ";

            //The count of the total records in the database table
            $sql_count_total = "
                SELECT
                    count(*) 'count'
                FROM {REDACTED} ft
                join {REDACTED} dd on dd.{REDACTED} = substring({REDACTED}, 4, 3)
            left join {REDACTED}  fc on substring(ft.{REDACTED},1,2) = fc.{REDACTED}
                left join {REDACTED}  td on ft.{REDACTED}  = td.{REDACTED} 
            ";

            //echo $sql;
            //echo var_dump($parameters);

            //Records from the query
            $results = sqlsrv_query($connection, $sql, $parameters);

            //Filtered count
            $filtered_count = sqlsrv_query($connection, $sql_count_filtered, $parameters);
            $filtered_count_result = sqlsrv_fetch_object($filtered_count);

            //Total record count
            $total_count = sqlsrv_query($connection, $sql_count_total, $parameters);
            $total_count_result = sqlsrv_fetch_object($total_count);

            if($results)
            {
                while($result = sqlsrv_fetch_object($results))
                {
                    array_push($transactions, $result);
                }
            }

            //Setup the return object.
            $return_object = new stdClass();
            $return_object -> recordsTotal = $total_count_result -> count;
            $return_object -> draw = $draw;
            $return_object -> recordsFiltered = $filtered_count_result -> count;
            $return_object -> data = $transactions;

            return json_encode($return_object);
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
                    $transactions = $this -> get_transaction_list($sort);
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