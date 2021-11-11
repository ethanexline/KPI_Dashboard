<?php
require_once("../objects/daily_revenue.php"); //Order Count  object

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class dailyRevenueController
{
    function get_order_count($company = FALSE, $division = FALSE)
    {
        $database = new database();
        $connection = $database -> connection();
        $daily_revenue = array();
        $daily_revenue2 = array();

        if($company and !$division)
        {
            $company_sql = "  and ddd.{REDACTED} = ?" ;
            $division_sql = " and substring({REDACTED},1,1) not in ('9', '3', '4') ";
        }
        elseif($company and $division)
        {
            $company_sql = " and ddd.{REDACTED} = ?" ;
            $division_sql = " and {REDACTED} = ? ";
        }
        else
        {
            $company_sql = "";
            $division_sql = " and {REDACTED} not in ('900')";
        }

        $sql = "DECLARE @currentWeek int, @startDate date;

                SET @currentWeek = (select {REDACTED} from {REDACTED}.{REDACTED} where {REDACTED} = cast(getdate() as date))
                SET @startDate = (select min(date) from {REDACTED}.{REDACTED} where {REDACTED} = @currentWeek and Year = (select {REDACTED} from {REDACTED}.{REDACTED} where {REDACTED} = cast(getdate() as date)))
                        
                select case when amount is null then 0 else amount end 'amount', {REDACTED}, date from
                (
                    select
                    (
                        select 
                            case when sum({REDACTED}) = 0 
                                then sum({REDACTED}) 
                            else sum({REDACTED}) 
                            end 
                        from {REDACTED} 
                        join {REDACTED} ddd on ddd.{REDACTED} = {REDACTED} " . $company_sql ."
                        where {REDACTED} >= @startDate and {REDACTED} <= {REDACTED}.date " . $division_sql . "
                    ) 'amount',
                    {REDACTED}.{REDACTED} 'DayName',
                    {REDACTED}.date 'date'
                    from {REDACTED}.{REDACTED} 
                    where date >= @startDate and date <= cast(getdate() as date)
                ) e
                order by date";

        $sql2 = "declare @lastWeek int;
                declare @year int;
                DECLARE @currentWeek int, @startDate date;

                SET @lastWeek = (select {REDACTED} from [{REDACTED}].{REDACTED}.{REDACTED} where {REDACTED} = cast(dateadd(day, -7, getdate()) as date))
                set @year = (SELECT {REDACTED} from [{REDACTED}].{REDACTED}.{REDACTED} where {REDACTED} = @lastWeek and {REDACTED} = cast(dateadd(day, -7, getdate()) as date))
                SET @startDate = (select min(date) from {REDACTED}.{REDACTED} where {REDACTED} = @lastWeek and {REDACTED} = Year(getdate()))

                select case when amount is null then 0 else amount end 'amount', {REDACTED}, date from
                (
                    select
                    (
                        select 
                            case when sum({REDACTED}) = 0 
                                then sum({REDACTED}) 
                            else sum({REDACTED}) 
                            end
                        from {REDACTED} 
                        join {REDACTED} ddd on ddd.{REDACTED} = division " . $company_sql ."
                        where {REDACTED} >= @startDate and {REDACTED} <= {REDACTED}.date " . $division_sql . "
                    ) 'amount',
                    {REDACTED}.{REDACTED} 'DayName',
                    {REDACTED}.date 'date'
                    from {REDACTED}.{REDACTED} 
                    where date >= @startDate and date <= cast(getdate() as date)
                    and {REDACTED} = @lastWeek 
                    and {REDACTED} = @year
                group by {REDACTED}, date
                ) e
                order by date";

        if($company and !$division)
        {
            $results = sqlsrv_query($connection, $sql, array($company));
            $results2 = sqlsrv_query($connection, $sql2, array($company));
        }
        elseif($company and $division)
        {
            $results = sqlsrv_query($connection, $sql, array($company, $division));
            $results2 = sqlsrv_query($connection, $sql2, array($company, $division));
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
                $daily_object = new daily_revenue($revenue -> amount, $revenue -> DayName, $revenue -> date -> format('Y-m-d'));
                array_push($daily_revenue, $daily_object);
            }
            while($revenue = sqlsrv_fetch_object($results2))
            {
                $daily_object2 = new daily_revenue($revenue -> amount, $revenue -> DayName, $revenue -> date -> format('Y-m-d'));
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