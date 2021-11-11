<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class stateListController
{
    function get_state_list()
    {
        $database = new database();
        $connection = $database -> connection();
        $states = array();
        
        $sql = "
                SELECT [{REDACTED}] 'code', [{REDACTED}] 'state'
                FROM [{REDACTED}].[{REDACTED}].[{REDACTED}]
                where {REDACTED} != 'PR'
                ";
        $results = sqlsrv_query($connection, $sql);
        while($state = sqlsrv_fetch_object($results))
        {
            
            array_push($states, array($state -> code, $state -> state));
        }
        
        
        return $states;
    }

    //Implementation of the POST request
    function post()
    {
        
        echo json_encode($this -> get_state_list());
        
    }
}