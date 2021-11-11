<?php //Error response object
require('C:\Apache24\htdocs\KPIGraphs\vendor\spout\src\Spout\Autoloader\autoload.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Row;

ini_set('memory_limit', '1024M'); // or you could use 1G
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

class TransactionDownloadController
{

    function make_download($file)
    {
        //$file = "../downloads/hello.txt";
        // Quick check to verify that the file exists
        if( !file_exists($file) ) die("File not found");
        // Force the download
        header("Content-Disposition: attachment; filename=" . basename($file));
        header("Content-Type: application/octet-stream;");
        readfile($file);
    }

    function get_transaction_list($sort)
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
                    $sql_order_by = " ORDER BY ({REDACTED} - {REDACTED}) ASC";
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

            $transactions = array();

            $sql = "
            SELECT
            {REDACTED},
            {REDACTED} + '-' + {REDACTED} 'driver code/name',
            {REDACTED},
            substring({REDACTED},4,3) 'division',
            {REDACTED} + '-' + {REDACTED} 'stop id/name',
            {REDACTED},
            {REDACTED},
            {REDACTED} - {REDACTED} 'tractor fuel gallons',

            cast(case when {REDACTED} - {REDACTED} = 0 then 0 else (({REDACTED} - {REDACTED}) - case when {REDACTED} = 0 then 0 else 
                ((({REDACTED} - {REDACTED}) / (({REDACTED} - {REDACTED}) + {REDACTED})) * {REDACTED}) end) end as decimal(7,2)) 'tractor_cost',

            cast(case when ({REDACTED} - {REDACTED}) = 0 then 0 else ((case when {REDACTED} - {REDACTED} = 0 then 0 else (({REDACTED} - {REDACTED})
                - case when {REDACTED} = 0 then 0 else ((({REDACTED} - {REDACTED}) 
                / (({REDACTED} - {REDACTED}) + {REDACTED})) * {REDACTED}) end) end))
                / ({REDACTED} - {REDACTED}) end as decimal(7,3)) 'tractor_ppg',

                {REDACTED},

            cast(case when {REDACTED} = 0 then 0 else ({REDACTED} - case when {REDACTED} = 0 then 0 else (1 - (case when (({REDACTED} - {REDACTED}) + 
            {REDACTED}) = 0 then 0 else ((({REDACTED} - {REDACTED}) / 
                (({REDACTED} - {REDACTED}) + {REDACTED}))) end)) * {REDACTED} end) end as decimal(7,2)) 'reefer_cost',

            cast(case when ({REDACTED}) = 0 then 0 else ((case when {REDACTED} = 0 then 0 else ({REDACTED} - case when {REDACTED} = 0 then 0 else 
                (1 - (case when (({REDACTED} - {REDACTED}) + {REDACTED}) = 0 then 0 else ((({REDACTED} - {REDACTED}) / 
                (({REDACTED} - {REDACTED}) + {REDACTED}))) end)) * {REDACTED} end) end)) / ({REDACTED}) end as decimal(7,3)) 'reefer_ppg',

                {REDACTED},
                {REDACTED},
            cast({REDACTED} / case when {REDACTED} = 0 then 1 else {REDACTED} end as decimal(7,3)) 'DEF Per gallon',
            {REDACTED},
            {REDACTED},
            {REDACTED},
            {REDACTED},
            cast(date as varchar) 'date', 
            substring(right('0000' +cast({REDACTED} as varchar),4), 1,2) + ':' + substring(right('0000' +cast({REDACTED} as varchar),4), 3,2) 'time'
            FROM {REDACTED} ft
            join {REDACTED} dd on dd.{REDACTED} = substring({REDACTED}, 4, 3)
            left join {REDACTED}  fc on substring(ft.{REDACTED},1,2) = fc.{REDACTED}
            left join {REDACTED}  td on ft.{REDACTED}  = td.{REDACTED} 
            " . $sql_where . "
             " . $sql_order_by . "
            ";
            

            //echo $sql;
            //echo var_dump($parameters);

            try
            {
                //Records from the query
                $results = sqlsrv_query($connection, $sql, $parameters);

                if($results)
                {
                    $cells = [
                        WriterEntityFactory::createCell('Term'),
                        WriterEntityFactory::createCell('Driver'),
                        WriterEntityFactory::createCell('Unit'),
                        WriterEntityFactory::createCell('Division'),
                        WriterEntityFactory::createCell('Stop ID/Name'),
                        WriterEntityFactory::createCell('Stop City'),
                        WriterEntityFactory::createCell('State'),
                        WriterEntityFactory::createCell('Tractor Gals'),
                        WriterEntityFactory::createCell('Tractor Cost'),
                        WriterEntityFactory::createCell('Tractor PPG'),
                        WriterEntityFactory::createCell('Reefer Gals'),
                        WriterEntityFactory::createCell('Reefer Cost'),
                        WriterEntityFactory::createCell('Reefer PPG'),
                        WriterEntityFactory::createCell('DEF Gals'),
                        WriterEntityFactory::createCell('DEF Cost'),
                        WriterEntityFactory::createCell('DEF PPG'),
                        WriterEntityFactory::createCell('Rebate'),
                        WriterEntityFactory::createCell('Hub'),
                        WriterEntityFactory::createCell('Cash Adv'),
                        WriterEntityFactory::createCell('Invoice #'),
                        WriterEntityFactory::createCell('Date'),
                        WriterEntityFactory::createCell('Time')
                    ];

                    /** Create a style with the StyleBuilder */
                    $style = (new StyleBuilder())
                    ->setFontBold()
                    ->setFontSize(12)
                    ->setFontColor(Color::WHITE)
                    ->setShouldWrapText()
                    ->setCellAlignment(CellAlignment::LEFT)
                    ->setBackgroundColor(Color::rgb(223, 27, 22))
                    ->build();

                    while($result = sqlsrv_fetch_object($results))
                    {
                        array_push($transactions, $result);
                    }
                
                    $file = '../downloads/test.xlsx';
                    $writer = WriterEntityFactory::createXLSXWriter();
                    // $writer = WriterEntityFactory::createODSWriter();
                    // $writer = WriterEntityFactory::createCSVWriter();

                    $writer->openToFile($file); // write data to a file or to a PHP stream
                    //$writer->openToBrowser($fileName); // stream data directly to the browser

                    $singleRow = WriterEntityFactory::createRow($cells, $style);

                    $writer->addRow($singleRow);

                    foreach ($transactions as $values){
                        $value_array = (array)$values;
                        $value_array['tractor fuel gallons'] = (double)$value_array['tractor fuel gallons'];
                        $value_array['tractor_cost'] = (double)$value_array['tractor_cost'];
                        $value_array['tractor_ppg'] = (double)$value_array['tractor_ppg'];
                        $value_array['reefer_gallons'] = (double)$value_array['reefer_gallons'];
                        $value_array['reefer_cost'] = (double)$value_array['reefer_cost'];
                        $value_array['reefer_ppg'] = (double)$value_array['reefer_ppg'];
                        $value_array['other_gallons'] = (double)$value_array['other_gallons'];
                        $value_array['other_fuel_cost'] = (double)$value_array['other_fuel_cost'];
                        $value_array['DEF Per gallon'] = (double)$value_array['DEF Per gallon'];
                        $value_array['total_rebate_amount'] = (double)$value_array['total_rebate_amount'];
                        $value_array['cash_advance_amount'] = (double)$value_array['cash_advance_amount'];

                        $rowFromValues = WriterEntityFactory::createRowFromArray($value_array);
                        $writer->addRow($rowFromValues);
                    }
                    
                    $writer->close();



                    $this -> make_download($file);
                }
            }
            catch(Exception $e)
            {
                echo $e;
            }
            
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