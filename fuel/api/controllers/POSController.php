<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class POSController
{
    function get_POS($year)
    {
        $parameters = array();

        if($year != null)
        {
            $database = new database();
            $connection = $database -> connection();

            $sql = "
            select 
            {REDACTED} 'not_this_one',
            {REDACTED} 'month',
            year(ft.date) 'year',
            sum({REDACTED}) 'rebate'
            FROM {REDACTED} ft
            join {REDACTED}.{REDACTED} dd on ft.date = dd.Date
            join {REDACTED}  td on ft.{REDACTED}  = td.{REDACTED} 
            where {REDACTED}  != 'BUM'
            and {REDACTED}  != 'LMC'
            and {REDACTED}  != 'ENG'
            and {REDACTED}  != 'MTC'
            and td.{REDACTED}  = 102
            and year(ft.date) = " . $year . "
            and substring({REDACTED}, 4, 3) != '800'
            and ft.date <= GETDATE()
            group by {REDACTED}, {REDACTED}, {REDACTED}(ft.date)
            order by dd.{REDACTED}";

            //Records from the query
            $results = sqlsrv_query($connection, $sql, $parameters);
            $rebates = array();

            if ($results) {
                while($rebate = sqlsrv_fetch_object($results))
                    array_push($rebates, array($rebate -> month, $rebate -> year, $rebate -> rebate));
                }

            return json_encode($rebates);
        }
        else
        {
            $error = new error_response('500', 'Request could not be processed, please check the API\'s input for errors.');
            http_response_code(500);
            return json_encode($error);
        }

    }
    
    function post()
    {

        if(!empty($_POST['year']))
        {
            try
            {
                $year = json_decode($_POST['year']);
                $rebates = $this -> get_POS($year);
                return $rebates;
                
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