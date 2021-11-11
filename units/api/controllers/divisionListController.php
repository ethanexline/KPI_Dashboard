<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class divisionListController
{
    function get_division_list()
    {
        $database = new database();
        $connection = $database -> connection();
        $divisions = array();
        
        $sql = "
                SELECT [{REDACTED}], {REDACTED}
                FROM [{REDACTED}].[{REDACTED}].[{REDACTED}]
                ";

        $results = sqlsrv_query($connection, $sql);

        while($division = sqlsrv_fetch_object($results))
        {
            array_push($divisions, array($division -> {REDACTED}, $division -> name));
        }
        
        return $divisions;
    }

    //Implementation of the POST request
    function post()
    {
        echo json_encode($this -> get_division_list());
    }
}

