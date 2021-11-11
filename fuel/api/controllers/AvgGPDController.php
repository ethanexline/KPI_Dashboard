<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class AvgGPDController
{
    function get_avg_gpd($year)
    {
        $parameters = array();

        if($year != null)
        {
            $database = new database();
            $connection = $database -> connection();

            $sql = "
            select 
            dd.{REDACTED},
            dd.{REDACTED} 'month',
            year(ft.date) 'year',
            (e.gallonsb4 / (DATEDIFF(day, {REDACTED}, {REDACTED}) + 1)) 'gallons'

            FROM {REDACTED} ft

            join {REDACTED} dv on dv.{REDACTED} = substring({REDACTED}, 4, 3)
            join {REDACTED}.{REDACTED} dd on ft.date = dd.Date
            join {REDACTED}  td on ft.{REDACTED} = td.{REDACTED} 
            
            join (
                select {REDACTED} 'help',
                {REDACTED} 'month',
                year(ft.date) 'year',
                case when sum({REDACTED} - {REDACTED}) = 0 then 0 else sum({REDACTED} - {REDACTED}) end 'gallonsb4'

                FROM {REDACTED} ft
                join {REDACTED} dv on dv.{REDACTED} = substring({REDACTED}, 4, 3)
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
                group by {REDACTED}, {REDACTED}, {REDACTED}(ft.date)) e 
                on e.{REDACTED} = dd.{REDACTED}
                
            where {REDACTED}  != 'BUM'
            and {REDACTED}  != 'LMC'
            and {REDACTED}  != 'ENG'
            and {REDACTED}  != 'MTC'
            and td.{REDACTED}  = 102
            and year(ft.date) = " . $year . "
            and substring({REDACTED}, 4, 3) != '800'
            and ft.date <= GETDATE()
            group by dd.{REDACTED}, {REDACTED}, {REDACTED}(ft.date), e.gallonsb4, {REDACTED}, {REDACTED}
            order by dd.{REDACTED}";

            //Records from the query
            $results = sqlsrv_query($connection, $sql, $parameters);
            $gallons = array();

            if ($results) {
                while($gallon = sqlsrv_fetch_object($results))
                    array_push($gallons, array($gallon -> month, $gallon -> year, $gallon -> gallons));
                }

            return json_encode($gallons);
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
                $gallons = $this -> get_avg_gpd($year);
                return $gallons;
                
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