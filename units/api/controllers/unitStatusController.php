 <?php
require_once("./objects/unit_status.php"); //Order Count  object

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class unitStatusController
{
    function get_weekly_unit_status($company = FALSE, $division = FALSE, $terminal = False, $start_date = False, $end_date = False)
    {
        $database = new database();
        $connection = $database -> connection();
        $weeks_revenue = array();
        
        $divisions = explode("-", $division);
        $companies = explode("-", $company);
        $terminals = explode("-", $terminal);
        $startDate = "@start_date";
        $endDate = "@end_date";

        if($start_date){
            $startDate = "'" . $start_date . "'";
        }
        if($end_date){
            $endDate =  "'" . $end_date . "'";
        }
        
        $in_statement = str_repeat("?,", count($divisions) - 1) . "?";
        
        $in_statement2 = str_repeat("?,", count($companies) - 1) . "?";
        
        $in_statement3 = str_repeat("?,", count($terminals) - 1) . "?";

        $parameters = array();

        if($division){
            foreach($divisions as $div){
                array_push($parameters,  $div);
            }
        }
        
        if($company){
            foreach($companies as $company){
                array_push($parameters, $company);
            }
        }
        
        if($terminals){
            foreach($terminals as $terminal){
                array_push($parameters, $terminal);
            }
        }

        if($company and $division)
        {
            $company_sql = " and {REDACTED} in (" . $in_statement . ") and dd.{REDACTED} in (" . $in_statement2 . ") and substring(unterm,1,3) in (" . $in_statement3 . ")" ;
        }
        else
        {
            $company_sql = " ";
        }
        
        $sql = "DECLARE @start_year int, @start_week int, @end_year int, @end_week int, @start_date date, @end_date date
                SET @end_year = (SELECT {REDACTED} from {REDACTED}.{REDACTED} dd where dd.date = cast(dateadd(day, 0, getdate()) as date))
                SET @end_week = (SELECT {REDACTED} from {REDACTED}.{REDACTED} dd where dd.date = cast(dateadd(day, 0, getdate()) as date))

                SET @start_year = (SELECT {REDACTED} from {REDACTED}.{REDACTED} dd where dd.date = cast(dateadd(day, -370, getdate()) as date))
                SET @start_week = (SELECT {REDACTED} from {REDACTED}.{REDACTED} dd where dd.date = cast(dateadd(day, -370, getdate()) as date))

                SET @start_date = (SELECT CAST(MIN({REDACTED}) as date) from {REDACTED}.{REDACTED} dd where {REDACTED} = @start_year and {REDACTED} = @start_week)
                SET @end_date = (SELECT CAST(MAX({REDACTED}) as date) from {REDACTED}.{REDACTED} dd where {REDACTED} = @end_year and {REDACTED} = @end_week)
        
                SELECT {REDACTED}, {REDACTED}, {REDACTED}, sum({REDACTED}) 'count' 
                from 
                (
                    SELECT {REDACTED}, {REDACTED}, case when ({REDACTED} = 'U' and {REDACTED} = '990') then 'UO' else {REDACTED} end 'status', count(distinct{REDACTED}) 'count' 
                    from {REDACTED}.{REDACTED} utr join {REDACTED}.{REDACTED} dd2 on utr.{REDACTED} = dd2.Date
                    join {REDACTED} dd on utr.{REDACTED} = dd.{REDACTED}
                    where utr.{REDACTED} between " . $startDate . " and " . $endDate . " and dd2.DayOfWeek = 7
                    " . $company_sql . "
                    group by {REDACTED}, {REDACTED}, {REDACTED}, {REDACTED}
                ) e 
                group by {REDACTED}, {REDACTED}, {REDACTED}
                order by {REDACTED}, {REDACTED}
                
                ";
        
        if($company and !$division)
        {
            $results = sqlsrv_query($connection, $sql, $parameters);
        }
        elseif($company and $division)
        {
            $results = sqlsrv_query($connection, $sql, $parameters);
        }
        else
        {
            $results = sqlsrv_query($connection, $sql);
            
        }
        if($results)
        {
            while($status = sqlsrv_fetch_object($results))
            {
                $unit_status_object = new unit_status($status -> WeekOfYear, $status -> year, $status -> status, $status -> count);
                array_push($weeks_revenue, $unit_status_object);
            }
        }
        
        return $weeks_revenue;
    }

    //Implementation of the POST request
    function post()
    {
        $sort = json_decode($_POST['sort']);

        if(!empty($sort->companies) and !empty($sort->divisions) and !empty($sort->terminals) and empty($sort->startDate) and empty($sort->endDate))
        {
            $companies = implode("-", $sort->companies);
            $divisions = implode("-", $sort->divisions);       
            $terminals = implode("-", $sort->terminals);
    
            echo json_encode($this -> get_weekly_unit_status($companies, $divisions, $terminals));
        }

        elseif(!empty($sort->companies) and !empty($sort->divisions) and !empty($sort->terminals) and !empty($sort->startDate) and empty($sort->endDate))
        {
            $companies = implode("-", $sort->companies);
            $divisions = implode("-", $sort->divisions);       
            $terminals = implode("-", $sort->terminals);
            $startDate =  $sort->startDate;      

            if($startDate == ""){
                $startDate = False;
            }

            echo json_encode($this -> get_weekly_unit_status($companies, $divisions, $terminals, $startDate));
        }
        
        elseif(!empty($sort->companies) and !empty($sort->divisions) and !empty($sort->terminals) and !empty($sort->startDate) and !empty($sort->endDate))
        {
            $companies = implode("-", $sort->companies);
            $divisions = implode("-", $sort->divisions);       
            $terminals = implode("-", $sort->terminals);
            $startDate = $sort->startDate;    
            $endDate = $sort->endDate;      
            
            if($startDate == ""){
                $startDate = False;
            }
            if($endDate == ""){
                $startDate = False;
            }

            echo json_encode($this -> get_weekly_unit_status($companies, $divisions, $terminals, $startDate, $endDate));
        }
    }
}

