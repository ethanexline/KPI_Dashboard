<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class terminalListController
{
    function get_terminals()
    {
        $database = new database();
        $connection = $database -> connection();
        $terminals = array();
        
        $sql = "
                SELECT [{REDACTED}] 'terminal', [{REDACTED}] 'description'
                FROM [{REDACTED}].[{REDACTED}].[{REDACTED}]
                ";
        $results = sqlsrv_query($connection, $sql);
        while($terminal = sqlsrv_fetch_object($results))
        {
            
            array_push($terminals, array($terminal -> terminal, $terminal -> description));
        }
        
        
        return $terminals;
    }

    //Implementation of the POST request
    function post()
    {
        
        echo json_encode($this -> get_terminals());
        
    }
}

