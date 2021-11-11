 <?php
require_once("./objects/unit_repair_symptom.php"); //Order Count  object

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class unitRepairSymptomController
{
    function get_repair_order_symptom($company = FALSE, $division = FALSE, $terminal = False, $start_date = False, $end_date = False)
    {
        $database = new database();
        $connection = $database -> connection();
        $repair_symptoms = array();
        
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
        
                select top 10 rs.{REDACTED}, sum({REDACTED}) 'amount' 
                from {REDACTED} umd
                join {REDACTED}.{REDACTED} utr on umd.{REDACTED} = utr.{REDACTED} and umd.{REDACTED} = utr.{REDACTED}
                join {REDACTED}.{REDACTED} on umd.{REDACTED} = date
                join {REDACTED} dd on utr.{REDACTED} = dd.{REDACTED}
                join {REDACTED}.{REDACTED} rs on umd.{REDACTED} = rs.{REDACTED} and umd.{REDACTED} = rs.{REDACTED}
                where utr.{REDACTED} between " . $startDate . " and " . $endDate . "
                " . $company_sql . " and {REDACTED} = 'RO'
                group by rs.{REDACTED}
                order by 2 desc, 1 
                
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
                $repair_symptom_cost = new unit_repair_symptom($status -> amount, $status -> description);
                array_push($repair_symptoms, $repair_symptom_cost);
            }
        }
        
        return $repair_symptoms;
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
    
            echo json_encode($this -> get_repair_order_symptom($companies, $divisions, $terminals));
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

            echo json_encode($this -> get_repair_order_symptom($companies, $divisions, $terminals, $startDate));
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

            echo json_encode($this -> get_repair_order_symptom($companies, $divisions, $terminals, $startDate, $endDate));
        }
    }
}

