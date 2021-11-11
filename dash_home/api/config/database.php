<?php
class database
{
    private $serverName;
    private $connectionOptions;
    
    function __construct()
    {
        $settings = parse_ini_file("../../../../../database.ini");
        $this -> serverName = $settings['ip'];
        $this -> connectionOptions = array(
            "Database" => "{REDACTED}",
            "Uid" => $settings['username'],
            "PWD" => $settings['password']);
    }

    function connection()
    {
        $conn = sqlsrv_connect($this -> serverName, $this -> connectionOptions);
        if($conn)
        {
            return $conn;
        }
        else
        {
            return false;
        }
    }
}

?>