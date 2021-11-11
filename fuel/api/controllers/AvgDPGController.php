<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class AvgDPGController
{
    function get_avg_DPG($year)
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
            (sum({REDACTED}) / (e.gallons - (f.bulkGal + g.noDiscGal))) 'discount'

            FROM {REDACTED} ft

            join {REDACTED} dv on dv.{REDACTED} = substring({REDACTED}, 4, 3)
            join {REDACTED}.{REDACTED} dd on ft.date = dd.Date
            join {REDACTED} td on ft.{REDACTED} = td.{REDACTED}

            join (
                select {REDACTED} 'help',
                {REDACTED} 'month',
                year(ft.date) 'year',
                case when sum({REDACTED} - {REDACTED}) = 0 then 0 else sum({REDACTED} - {REDACTED}) end 'gallons'

                FROM {REDACTED} ft
                join {REDACTED} dv on dv.{REDACTED} = substring({REDACTED}, 4, 3)
                join {REDACTED}.{REDACTED} dd on ft.date = dd.Date
                join {REDACTED} td on ft.{REDACTED} = td.{REDACTED}
                where {REDACTED} != 'BUM'
                and {REDACTED} != 'LMC'
                and {REDACTED} != 'ENG'
                and {REDACTED} != 'MTC'
                and td.{REDACTED} = 102
                and year(ft.date) = " . $year . "
                and substring({REDACTED}, 4, 3) != '800'
                and ft.date <= GETDATE()
                group by {REDACTED}, {REDACTED}, {REDACTED}(ft.date)) e 
                on e.{REDACTED} = dd.{REDACTED}

			join (
                select {REDACTED} 'help',
                {REDACTED} 'month',
                year(ft.date) 'year',
                case when sum({REDACTED} - {REDACTED}) = 0 then 0 else sum({REDACTED} - {REDACTED}) end 'bulkGal'

                FROM {REDACTED} ft
                join {REDACTED} dv on dv.{REDACTED} = substring({REDACTED}, 4, 3)
                join {REDACTED}.{REDACTED} dd on ft.date = dd.Date
                join {REDACTED}  td on ft.{REDACTED}  = td.{REDACTED} 
                where {REDACTED} != 'BUM'
                and {REDACTED} != 'LMC'
                and {REDACTED} != 'ENG'
                and {REDACTED} != 'MTC'
                and td.{REDACTED} = 102
				and {REDACTED} = 'T'
                and year(ft.date) = " . $year . "
                and substring({REDACTED}, 4, 3) != '800'
                and ft.date <= GETDATE()
                group by {REDACTED}, {REDACTED}, {REDACTED}(ft.date)) f 
                on f.{REDACTED} = dd.{REDACTED}

			join (
                select {REDACTED} 'help',
                {REDACTED} 'month',
                year(ft.date) 'year',
                case when sum({REDACTED} - {REDACTED}) = 0 then 0 else sum({REDACTED} - {REDACTED}) end 'noDiscGal'

                FROM {REDACTED} ft
                join {REDACTED} dv on dv.{REDACTED} = substring({REDACTED}, 4, 3)
                join {REDACTED}.{REDACTED} dd on ft.date = dd.Date
                join {REDACTED}  td on ft.{REDACTED} = td.{REDACTED} 
				left join {REDACTED}  fc on substring(ft.{REDACTED},1,2) = fc.{REDACTED}
                where {REDACTED} != 'BUM'
                and {REDACTED} != 'LMC'
                and {REDACTED} != 'ENG'
                and {REDACTED} != 'MTC'
                and td.{REDACTED} = 102
                and year(ft.date) = " . $year . "
                and substring({REDACTED}, 4, 3) != '800'
				and ((({REDACTED} != 'T' and fc.{REDACTED} is null) or fc.{REDACTED} != 'Y'))
				and {REDACTED} = 0
                and ft.date <= GETDATE()
                group by {REDACTED}, {REDACTED}, {REDACTED}(ft.date)) g 
                on g.{REDACTED} = dd.{REDACTED}
                
            where {REDACTED} != 'BUM'
            and {REDACTED} != 'LMC'
            and {REDACTED} != 'ENG'
            and {REDACTED} != 'MTC'
            and td.{REDACTED} = 102
            and year(ft.date) = " . $year . "
            and substring({REDACTED}, 4, 3) != '800'
            and ft.date <= GETDATE()
            group by dd.{REDACTED}, {REDACTED}, {REDACTED}(ft.date), e.gallons, f.bulkGal, g.noDiscGal
            order by dd.{REDACTED}";

            //Records from the query
            $results = sqlsrv_query($connection, $sql, $parameters);
            $discounts = array();

            if ($results) {
                while($discount = sqlsrv_fetch_object($results))
                    array_push($discounts, array($discount -> month, $discount -> year, $discount -> discount));
                }

            return json_encode($discounts);
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
                $discounts = $this -> get_avg_dpg($year);
                return $discounts;
                
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