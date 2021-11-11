<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class chainListController
{
    function get_chain_list()
    {
        $database = new database();
        $connection = $database -> connection();
        $chains = array();
        
        $sql = "
            SELECT [{REDACTED}] 'code',
            [{REDACTED}] 'chain'
             FROM [{REDACTED}].[{REDACTED}].[{REDACTED}]
                ";
        $results = sqlsrv_query($connection, $sql);
        while($chain = sqlsrv_fetch_object($results))
        {
            
            array_push($chains, array($chain -> code, $chain -> chain));
        }
        
        
        return $chains;
    }

    //Implementation of the POST request
    function post()
    {
        
        echo json_encode($this -> get_chain_list());
        
    }
}