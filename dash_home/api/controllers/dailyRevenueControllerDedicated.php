<?php
require_once("../objects/daily_revenue.php"); //Order Count  object

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class dailyRevenueControllerDedicated
{
    function get_order_count($company = FALSE, $division = FALSE)
    {
        $database = new database();
        $connection = $database -> connection();
        $daily_revenue = array();
        $daily_revenue2 = array();

        if($company and !$division)
        {
            $company_sql = " and substring({REDACTED},1,1) in ('3', '4') and ddd.{REDACTED} = ?" ;
        }
        elseif($company and $division)
        {
            $company_sql = " and {REDACTED} = ? and ddd.{REDACTED} = ?" ;
        }
        else
        {
            $company_sql = " and {REDACTED} not in ('900')";
        }
        $sql = "DECLARE @currentWeek int;
                SET @currentWeek = (select {REDACTED} from [{REDACTED}].{REDACTED}.{REDACTED} where {REDACTED} = cast(getdate() as date))
                    
                SELECT SUM(amount) over(order by date rows unbounded preceding)  'amount', SUM(amount2) over(order by date rows unbounded preceding) 'spotting', {REDACTED}, date
                from (
                select 
                    (
                        Select sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) 
                        from {REDACTED} where {REDACTED} = date
                        and substring({REDACTED},1,1) in ('3', '4')
                    ) 'amount',
                    (
                        select case when sum({REDACTED}) is null then 0 else sum({REDACTED}) end from {REDACTED}
                        join {REDACTED} dd2 on {REDACTED} = dd2.{REDACTED}
                        where {REDACTED} = date
                        and {REDACTED} in ('XSPTBA' , 'XSPTMI', 'XSPTBB', 'XCOISH') 
                        and {REDACTED} = 102  
                    ) 'amount2',
                    {REDACTED}, {REDACTED}
                    
                from {REDACTED}.{REDACTED} 
                where {REDACTED} = @currentWeek 
                and {REDACTED} = (SELECT {REDACTED} from {REDACTED}.{REDACTED} where {REDACTED} = cast(getdate() as date)) 
                and date <= cast(getdate() as date)
                ) e
                order by date";

        $sql2 = "declare @lastWeek int;
                declare @year int;

                SET @lastWeek = (select {REDACTED} from [{REDACTED}].di{REDACTED}m.{REDACTED} where {REDACTED} = cast(dateadd(day, -7, getdate()) as date))
                set @year = (select {REDACTED} from [{REDACTED}].{REDACTED}.{REDACTED} where {REDACTED} = @lastWeek and {REDACTED} = cast(dateadd(day, -7, getdate()) as date))
                    
                SELECT SUM(amount) over(order by date rows unbounded preceding) 'amount', SUM(amount2) over(order by date rows unbounded preceding) 'spotting', {REDACTED}, date
                from (
                select 
                    (
                        Select sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) 
                        from {REDACTED} where {REDACTED} = date
                        and substring({REDACTED},1,1) in ('3', '4')
                    ) 'amount',
                    (
                        select case when sum({REDACTED}) is null then 0 else sum({REDACTED}) end from {REDACTED}
                        join {REDACTED} dd2 on {REDACTED} = dd2.{REDACTED}
                        where {REDACTED} = date
                        and {REDACTED} in ('XSPTBA' , 'XSPTMI', 'XSPTBB', 'XCOISH') 
                        and {REDACTED} = 102  
                    ) 'amount2',
                DayName, date
                    
                from {REDACTED}.{REDACTED} 
                where {REDACTED} = @lastWeek 
                and {REDACTED} = @year
                ) e
                order by date";

        if($company and !$division)
        {
            $results = sqlsrv_query($connection, $sql, array($company));
            $results2 = sqlsrv_query($connection, $sql2, array($company));
        }
        elseif($company and $division)
        {
            $results = sqlsrv_query($connection, $sql, array($division, $company));
            $results2 = sqlsrv_query($connection, $sql2, array($division, $company));
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
                $daily_object = new daily_revenue($revenue -> amount + $revenue -> spotting, $revenue -> DayName, $revenue -> date -> format('Y-m-d'));
                array_push($daily_revenue, $daily_object);
            }
            while($revenue = sqlsrv_fetch_object($results2))
            {
                $daily_object2 = new daily_revenue($revenue -> amount + $revenue -> spotting, $revenue -> DayName, $revenue -> date -> format('Y-m-d'));
                array_push($daily_revenue2, $daily_object2);
            }
        }

        $returnObject = array($daily_revenue, $daily_revenue2);
        
        return $returnObject;
    }

    //Implementation of the POST request
    function post()
    {
        
        if(!empty($_POST['company']) and empty($_POST['division']))
        {
            $company = (int)$_POST['company'];
            if($company === 102 or $company === 301)
            {
                echo json_encode($this -> get_order_count($company));
            }
            else
            {
                http_response_code(400);
                $error = new error_response(400, "Invalid Company Code");
                echo json_encode($error);
                die();   
            }
        }
        elseif(!empty($_POST['company']) and !empty($_POST['division']))
        {
            $company = $_POST['company'];
            $division = $_POST['division'];
            echo json_encode($this -> get_order_count($company, $division));
        }
        elseif(empty($_POST['company']))
        {
            echo json_encode($this -> get_order_count());
        }
        else
        {
            http_response_code(404);
            $error = new error_response(404, "Must include company: ./api/v1/orders_count/{company}");
            echo json_encode($error);
            die();
        }
    }
}