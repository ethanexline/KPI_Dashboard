<?php
//require_once("./objects/state_revenue.php"); //Order Count  object

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class brokerRevenueController
{
    function get_broker_revenue($company = FALSE, $division = FALSE, $terminal = False, $start_date = False, $end_date = False)
    {
        $database = new database();
        $connection = $database -> connection();
        $weeks_revenue = array();
        $weeks_revenue2 = array();
        $weeks_revenue3 = array();

        
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
        
        
        if($terminals){
            foreach($terminals as $terminal){
                array_push($parameters, $terminal);
            }
        }
        if($company){
            foreach($companies as $company){
                array_push($parameters, $company);
            }
        }

        if($company and $division)
        {
            $company_sql = " and ddd.{REDACTED} in (" . $in_statement2 . ") " ;
            $division_sql = "and od.{REDACTED} in (" . $in_statement . ") and od.{REDACTED} in (" . $in_statement3 . ") "; 
        }
        else
        {
            $company_sql = " ";
        }
        
        $sql = "
                SELECT {REDACTED}, {REDACTED} 'week', 
                case when sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) is null then 0 else
                sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) end'revenue'
                from {REDACTED}.{REDACTED} dd
                left join {REDACTED} od on dd.date = od.{REDACTED} and od.{REDACTED} = 'BROKER' " . $division_sql ."
                join {REDACTED} ddd on od.{REDACTED} = ddd.{REDACTED} " . $company_sql ."
                where dd.date between " . $startDate . " and " . $endDate . "
                group by {REDACTED}, {REDACTED}
                order by {REDACTED} asc, {REDACTED} asc
                ";

        $sql2 = "    
                SELECT {REDACTED}, {REDACTED} 'week', 
                case when sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) is null then 0 else
                sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) end'revenue'
                from {REDACTED}.{REDACTED} dd
                left join {REDACTED} od on dd.date = od.{REDACTED} and od.{REDACTED} != 'BROKER' " . $division_sql ."
                join {REDACTED} ddd on od.{REDACTED} = ddd.{REDACTED} " . $company_sql ."
                where dd.date between " . $startDate . " and " . $endDate . "
                group by {REDACTED}, {REDACTED}
                order by {REDACTED} asc, {REDACTED} asc
            ";
        $sql3 = "
            SELECT {REDACTED}, {REDACTED} 'week', 
            case when sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) is null then 0 else
            sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) end'revenue'
            from {REDACTED}.{REDACTED} dd
            left join {REDACTED} od on dd.date = od.{REDACTED} and od.{REDACTED} != '' " . $division_sql ."
            join {REDACTED} ddd on od.{REDACTED} = ddd.{REDACTED} " . $company_sql ."
            where dd.date between " . $startDate . " and " . $endDate . "
            group by {REDACTED}, {REDACTED}
            order by {REDACTED} asc, {REDACTED} asc
        ";

        if($company and !$division)
        {
            $results = sqlsrv_query($connection, $sql, array($company));
            $results2 = sqlsrv_query($connection, $sql2, array($company));
            $results3 = sqlsrv_query($connection, $sql3, array($company));

        }
        elseif($company and $division)
        {
            $results = sqlsrv_query($connection, $sql, $parameters);
            $results2 = sqlsrv_query($connection, $sql2, $parameters);
            $results3 = sqlsrv_query($connection, $sql3, $parameters);

        }
        else
        {
            $results = sqlsrv_query($connection, $sql);
            $results2 = sqlsrv_query($connection, $sql2);
            $results3 = sqlsrv_query($connection, $sql3);

        }
        if($results)
        {
            while($revenue = sqlsrv_fetch_object($results))
            {
                $week_object = $revenue;
                array_push($weeks_revenue, $week_object);
            }
            while($revenue = sqlsrv_fetch_object($results2))
            {
                $week_object2 = $revenue;
                array_push($weeks_revenue2, $week_object2);
            }
            while($revenue = sqlsrv_fetch_object($results3))
            {
                $week_object3 = $revenue;
                array_push($weeks_revenue3, $week_object3);
            }

        }
        
        $returnObject = [$weeks_revenue, $weeks_revenue2, $weeks_revenue3];
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
    
            echo json_encode($this -> get_broker_revenue($companies, $divisions, $terminals));
            
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

            echo json_encode($this -> get_broker_revenue($companies, $divisions, $terminals, $startDate));
            
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

            echo json_encode($this -> get_broker_revenue($companies, $divisions, $terminals, $startDate, $endDate));
            
        }
        
    }
}

