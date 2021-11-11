<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class yearListController
{
    function get_year_list()
    {
        $database = new database();
        $connection = $database -> connection();
        $years = array();
        
        $sql = "
            SELECT 
            distinct year(date) 'year'
  
            FROM [{REDACTED}].[{REDACTED}].[{REDACTED}]
            order by year(date) desc
                ";
        $results = sqlsrv_query($connection, $sql);
        while($year = sqlsrv_fetch_object($results))
        {
            
            array_push($years, array($year -> year));
        }
        
        
        return $years;
    }

    //Implementation of the POST request
    function post()
    {
        
        echo json_encode($this -> get_year_list());
        
    }
}