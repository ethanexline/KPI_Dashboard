<?php
require_once("./objects/daily_revenue.php"); //Order Count  object

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class dailyRevenueController
{
    function get_daily_revenue($company = FALSE, $division = FALSE, $terminal = False, $start_date = False, $end_date = False)
    {
        $database = new database();
        $connection = $database -> connection();
        $daily_revenue = array();
        
        $divisions = explode("-", $division);
        $companies = explode("-", $company);
        $terminals = explode("-", $terminal);
        $startDate = "@start_date";
        $endDate = "@end_date";

        if($start_date){
            $startDate = "'" . $start_date . "'";
        }
        if($end_date){
            $endDate =  "'" . $end_date . "'";;
        }

        
        $in_statement = str_repeat("?,", count($divisions) - 1) . "?";;
        
        $in_statement2 = str_repeat("?,", count($companies) - 1) . "?";;
        
        $in_statement3 = str_repeat("?,", count($terminals) - 1) . "?";;

        $parameters = array();

        if($division){
            foreach($divisions as $div){
                array_push($parameters, $div);
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
            $company_sql = " and {REDACTED} in (" . $in_statement . ") and ddd.{REDACTED} in (" . $in_statement2 . ") and {REDACTED}  in (" . $in_statement3 . ")" ;
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
      
                SELECT SUM(amount) over(order by date rows unbounded preceding) 'amount', {REDACTED}, date 
                from (
                        SELECT 
                        sum(case 
                        when {REDACTED} = 0  
                        then case 
                                when {REDACTED} = '900' 
                                    then {REDACTED} + {REDACTED} + {REDACTED} 
                                else {REDACTED}
                            end
                        else case 
                                when {REDACTED} = '900' 
                                    then {REDACTED} + {REDACTED} + {REDACTED} 
                                else {REDACTED}
                            end
                        end) 'amount', {REDACTED}, date
                        from {REDACTED} 
                        join {REDACTED}.{REDACTED} dd on {REDACTED} = dd.date
                        join {REDACTED} ddd on {REDACTED}.{REDACTED} = ddd.{REDACTED}
                        where {REDACTED} between " . $startDate . " and " . $endDate . "
                        " . $company_sql . "      
                        group by  {REDACTED}, date
                )e 
                
                order by date";

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
            while($revenue = sqlsrv_fetch_object($results))
            {
                $daily_object = new daily_revenue($revenue -> amount, $revenue -> DayName, $revenue -> date -> format('Y-m-d'));
                array_push($daily_revenue, $daily_object);
            }
        }
        
        return $daily_revenue;
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
    
            echo json_encode($this -> get_daily_revenue($companies, $divisions, $terminals));
            
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

            echo json_encode($this -> get_daily_revenue($companies, $divisions, $terminals, $startDate));
            
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

            echo json_encode($this -> get_daily_revenue($companies, $divisions, $terminals, $startDate, $endDate));
            
        }
        
    }
}

