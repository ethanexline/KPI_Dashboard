<?php
//require_once("./objects/state_revenue.php"); //Order Count  object

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class RpmByWeekController
{
    function get_RPM_by_week($company = FALSE, $division = FALSE, $terminal = False, $start_date = False, $end_date = False)
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
        
        $sql = "
                SELECT {REDACTED}, {REDACTED},  
                cast(sum(case when {REDACTED} = 0 then {REDACTED} else {REDACTED} end) / 
                case when sum({REDACTED}) = 0 then 1 else sum({REDACTED}) end as decimal(7,2)) 'rpm'
                FROM [{REDACTED}].[{REDACTED}].[{REDACTED}] 
                join {REDACTED}.{REDACTED} on {REDACTED} = date
                join {REDACTED} dd on {REDACTED}.{REDACTED} = dd.{REDACTED}
                where {REDACTED} between " . $startDate . " and " . $endDate . "
                " . $company_sql . "
                group by {REDACTED}, {REDACTED}
                order by {REDACTED}, {REDACTED}
                ";

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

            $returnObject = $weeks_revenue;
            return $returnObject;
        }

        else
        {
            if( $results === false ) {
                if( ($errors = sqlsrv_errors() ) != null) {
                    foreach( $errors as $error ) {
                        echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                        echo "code: ".$error[ 'code']."<br />";
                        echo "message: ".$error[ 'message']."<br />";
                    }
                }
            }
        }
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
    
            echo json_encode($this -> get_RPM_by_week($companies, $divisions, $terminals));
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

            echo json_encode($this -> get_RPM_by_week($companies, $divisions, $terminals, $startDate));
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

            echo json_encode($this -> get_RPM_by_week($companies, $divisions, $terminals, $startDate, $endDate));
        }
    }
}

