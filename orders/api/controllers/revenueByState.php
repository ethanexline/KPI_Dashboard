<?php
require_once("./objects/state_revenue.php"); //Order Count  object

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class revenueByState
{
    function get_revenue_state($company = FALSE, $division = FALSE, $terminal = False, $start_date = False, $end_date = False)
    {
        $database = new database();
        $connection = $database -> connection();
        $weeks_revenue = array();
        $weeks_revenue2 = array();

        
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
            $company_sql = " and {REDACTED} in (" . $in_statement . ") and dd.{REDACTED} in (" . $in_statement2 . ") and {REDACTED}  in (" . $in_statement3 . ")" ;
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
        
                select {REDACTED} 'state', sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) 'amount'
                from {REDACTED} join {REDACTED}.{REDACTED} on {REDACTED} = date
                join {REDACTED} dd on {REDACTED}.{REDACTED} = dd.{REDACTED}
                where {REDACTED} between " . $startDate . " and " . $endDate . "
                " . $company_sql . "
                group by {REDACTED}
                order by {REDACTED}
                
                ";

        $sql2 = "
                DECLARE @start_year int, @start_week int, @end_year int, @end_week int, @start_date date, @end_date date
                SET @end_year = (SELECT {REDACTED} from {REDACTED}.{REDACTED} dd where dd.date = cast(dateadd(day, 0, getdate()) as date))
                SET @end_week = (SELECT {REDACTED} from {REDACTED}.{REDACTED} dd where dd.date = cast(dateadd(day, 0, getdate()) as date))

                SET @start_year = (SELECT {REDACTED} from {REDACTED}.{REDACTED} dd where dd.date = cast(dateadd(day, -370, getdate()) as date))
                SET @start_week = (SELECT {REDACTED} from {REDACTED}.{REDACTED} dd where dd.date = cast(dateadd(day, -370, getdate()) as date))

                SET @start_date = (SELECT CAST(MIN({REDACTED}) as date) from {REDACTED}.{REDACTED} dd where {REDACTED} = @start_year and {REDACTED} = @start_week)
                SET @end_date = (SELECT CAST(MAX({REDACTED}) as date) from {REDACTED}.{REDACTED} dd where {REDACTED} = @end_year and {REDACTED} = @end_week)
                
                select {REDACTED} 'state', sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) 'amount'
                from {REDACTED} join {REDACTED}.{REDACTED} on {REDACTED} = date
                join {REDACTED} dd on {REDACTED}.{REDACTED} = dd.{REDACTED}
                where {REDACTED} between " . $startDate . " and " . $endDate . "
                " . $company_sql . "
                group by {REDACTED}
                order by {REDACTED}
                ";
        
        if($company and !$division)
        {
            $results = sqlsrv_query($connection, $sql, array($company));
            $results2 = sqlsrv_query($connection, $sql2, array($company));

        }
        elseif($company and $division)
        {
            $results = sqlsrv_query($connection, $sql, $parameters);
            $results2 = sqlsrv_query($connection, $sql2, $parameters);

        }
        else
        {
            $results = sqlsrv_query($connection, $sql);
            $results2 = sqlsrv_query($connection, $sql2);

        }
        if($results)
        {
            while($revenue = sqlsrv_fetch_object($results))
            {
                $week_object = new state_revenue($revenue -> state, $revenue -> amount);
                array_push($weeks_revenue, $week_object);
            }
            while($revenue = sqlsrv_fetch_object($results2))
            {
                $week_object2 = new state_revenue($revenue -> state, $revenue -> amount);
                array_push($weeks_revenue2, $week_object2);
            }
        }
        
        $returnObject = array($weeks_revenue, $weeks_revenue2);
        return $returnObject;
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
    
            echo json_encode($this -> get_revenue_state($companies, $divisions, $terminals));
            
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

            echo json_encode($this -> get_revenue_state($companies, $divisions, $terminals, $startDate));
            
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

            echo json_encode($this -> get_revenue_state($companies, $divisions, $terminals, $startDate, $endDate));
            
        }
        
    }
}

