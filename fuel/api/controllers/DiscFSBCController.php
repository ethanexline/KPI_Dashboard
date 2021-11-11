<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class DiscFSBCController
{
    function get_disc_FSBC($year)
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
            fc.{REDACTED} 'chain',
            case when sum({REDACTED} - {REDACTED}) = 0 then 0 else sum({REDACTED} - {REDACTED}) end 'gallons',
            e.bulkGals 'bulkGallons',
            f.nonDiscGals 'nonDiscGals'

            FROM {REDACTED} ft

            join {REDACTED} dv on dv.{REDACTED} = substring({REDACTED}, 4, 3)
            join {REDACTED}.{REDACTED} dd on ft.date = dd.Date
            join {REDACTED}  td on ft.{REDACTED}  = td.{REDACTED} 
            left join {REDACTED}  fc on substring(ft.{REDACTED},1,2) = fc.{REDACTED}

            join (
                select 
                dd.{REDACTED} 'help',
                dd.{REDACTED} 'month',
                year(ft.date) 'year',
                case when sum({REDACTED} - {REDACTED}) = 0 then 0 else sum({REDACTED} - {REDACTED}) end 'bulkGals'

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
                and {REDACTED} = 'T'
                group by dd.{REDACTED}, {REDACTED}, {REDACTED}(ft.date)) e 
                on e.month = dd.{REDACTED}

            join (
                select 
                dd.{REDACTED} 'help',
                dd.{REDACTED} 'month',
                year(ft.date) 'year',
                case when sum({REDACTED} - {REDACTED}) = 0 then 0 else sum({REDACTED} - {REDACTED}) end 'nonDiscGals'

                FROM {REDACTED} ft

                join {REDACTED} dv on dv.{REDACTED} = substring({REDACTED}, 4, 3)
                join {REDACTED}.{REDACTED} dd on ft.date = dd.Date
                join {REDACTED}  td on ft.{REDACTED}  = td.{REDACTED} 
                left join {REDACTED}  fc on substring(ft.{REDACTED},1,2) = fc.{REDACTED}
                
                where {REDACTED}  != 'BUM'
                and {REDACTED}  != 'LMC'
                and {REDACTED}  != 'ENG'
                and {REDACTED}  != 'MTC'
                and td.{REDACTED}  = 102
                and year(ft.date) = " . $year . "
                and substring({REDACTED}, 4, 3) != '800'
                and ft.date <= GETDATE()
                and ((({REDACTED} != 'T' and fc.{REDACTED} is null) or fc.{REDACTED} != 'Y'))
                and {REDACTED} = 0
                group by dd.{REDACTED}, {REDACTED}, {REDACTED}(ft.date)) f 
                on f.{REDACTED} = dd.{REDACTED}
                
            where {REDACTED}  != 'BUM'
            and {REDACTED}  != 'LMC'
            and {REDACTED}  != 'ENG'
            and {REDACTED}  != 'MTC'
            and td.{REDACTED}  = 102
            and year(ft.date) = " . $year . "
            and substring({REDACTED}, 4, 3) != '800'
            and ft.date <= GETDATE()
            and ({REDACTED} = 'Pilot/FlyJ' or {REDACTED} = 'Loves' or {REDACTED} = 'Pilot/One9' or {REDACTED} = 'TA/Petro' or {REDACTED} = 'Speedway')
            group by dd.{REDACTED}, {REDACTED}, fc.{REDACTED}, {REDACTED}(ft.date), e.bulkGals, f.nonDiscGals
            order by dd.{REDACTED}";

            //Records from the query
            $results = sqlsrv_query($connection, $sql, $parameters);
            $gallons = array();

            if ($results) {
                while($gallon = sqlsrv_fetch_object($results)) {
                    array_push($gallons, array($gallon -> month, $gallon -> year, $gallon -> chain, $gallon -> gallons, $gallon -> bulkGallons, $gallon -> nonDiscGals));
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
    }
    
    function post()
    {

        if(!empty($_POST['year']))
        {
            try
            {
                $year = json_decode($_POST['year']);
                $gallons = $this -> get_disc_FSBC($year);
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
            $error = new error_response('500', 'Request could not be processed, please include a "year" JSON object');
            http_response_code(500);
            return json_encode($error);
        }

    }
}
