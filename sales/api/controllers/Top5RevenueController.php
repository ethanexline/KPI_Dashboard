<?php
//require_once("./objects/state_revenue.php"); //Order Count  object

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class Top5RevenueController
{
    function get_top_5_revenue($company = FALSE, $division = FALSE, $terminal = False, $start_date = False, $end_date = False)
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
            $company_sql = " and od.{REDACTED} in (" . $in_statement . ") and dd.{REDACTED} in (" . $in_statement2 . ") and {REDACTED}  in (" . $in_statement3 . ")" ;
        }

        else
        {
            $company_sql = " ";
        }
        
        $sql = "
        select cd.{REDACTED} 'topCustomer',
        (	
            select case when sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) is null then 0
            else sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) end
            from {REDACTED} od2
            join {REDACTED}.{REDACTED} d2 on od2.{REDACTED} = d2.Date
            where od2.{REDACTED} = e.{REDACTED}
            and d2{REDACTED} = d{REDACTED} and d2.{REDACTED} = d.{REDACTED}

        ) 'topRevenue',
        cast(year as varchar) + '-' + cast({REDACTED} as varchar) 'week'


        from {REDACTED}.{REDACTED} d
        cross join (
        select {REDACTED} from (
                            select top 5 {REDACTED}, sum(case when od.{REDACTED} = 0 then od.{REDACTED} else od.{REDACTED} end) 'rev'
                            from {REDACTED} od
                            join {REDACTED} dd on od.{REDACTED} = dd.{REDACTED}
                            where {REDACTED} between ". $startDate . " and " . $endDate . "
                            " . $company_sql . "
                            group by {REDACTED}
                            order by 2 desc
                            ) e
        ) e
        join {REDACTED} cd on e.{REDACTED} = cd.{REDACTED}
        where d.date between ". $startDate . " and " . $endDate . "
        group by cd.{REDACTED}, {REDACTED}, {REDACTED}, e.{REDACTED}
        order by {REDACTED}, {REDACTED}
                ";

        //echo $sql;
        if($company and !$division)
        {
            $results = sqlsrv_query($connection, $sql, array($company));
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
                $week_object = $revenue;
                array_push($weeks_revenue, $week_object);
            }
        }
        
        $returnObject = $weeks_revenue;
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
    
            echo json_encode($this -> get_top_5_revenue($companies, $divisions, $terminals));
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

            echo json_encode($this -> get_top_5_revenue($companies, $divisions, $terminals, $startDate));
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

            echo json_encode($this -> get_top_5_revenue($companies, $divisions, $terminals, $startDate, $endDate));
        }
    }
}