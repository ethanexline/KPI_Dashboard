<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class FuelVBulkController
{
    function get_fuelVBulk($year)
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
            case when avg(e.bulk_ppg) = 0 then 0 else avg(e.bulk_ppg) end 'bulk_ppg',

            case when sum({REDACTED} - {REDACTED}) = 0 then 0 else (sum(case when {REDACTED} - {REDACTED} = 0 then 0 else (({REDACTED} - {REDACTED})
                            - case when {REDACTED} = 0 then 0 else ((({REDACTED} - {REDACTED}) 
                            / (({REDACTED} - {REDACTED}) + {REDACTED})) * {REDACTED}) end) end))
                            / sum({REDACTED} - {REDACTED}) end 'tractor_ppg'

            FROM {REDACTED} ft

            join {REDACTED} dv on dv.{REDACTED} = substring({REDACTED}, 4, 3)
            join {REDACTED}.{REDACTED} dd on ft.date = dd.Date
            join {REDACTED}  td on ft.{REDACTED}  = td.{REDACTED} 

            join 
                (select 
                Month 'not_month',
                MonthName 'month',
                year(ft.date) 'year',
                case when sum({REDACTED} - {REDACTED}) = 0 then 0 else (sum(case when {REDACTED} - {REDACTED} = 0 then 0 else (({REDACTED} - {REDACTED})
                                - case when {REDACTED} = 0 then 0 else ((({REDACTED} - {REDACTED}) 
                                / (({REDACTED} - {REDACTED}) + {REDACTED})) * {REDACTED}) end) end))
                                / sum({REDACTED} - {REDACTED}) end 'bulk_ppg'

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
                and {REDACTED} = 'VA'
                and {REDACTED} = 'T'
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
            and {REDACTED} = 'VA'
            and {REDACTED} != 'T'
            and ft.date <= GETDATE()
            group by dd.{REDACTED}, {REDACTED}, {REDACTED}(ft.date)
            order by dd.{REDACTED}";

            //Records from the query
            $results = sqlsrv_query($connection, $sql, $parameters);
            $avgs = array();

            if ($results) {
                while($avg = sqlsrv_fetch_object($results))
                    array_push($avgs, array($avg -> month, $avg -> year, $avg -> bulk_ppg, $avg -> tractor_ppg));
                }

            return json_encode($avgs);
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
                $avgs = $this -> get_fuelVBulk($year);
                return $avgs;
                
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