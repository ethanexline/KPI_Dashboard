<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/OAuthSSO/Client/OAuthClient.php"); //Add the OAuthClient from the SSO system.
require_once("./config/database.php"); //Add the database.php file for database interaction
require_once("./objects/error_response.php"); //Error response object
require_once('./controllers/divisionListController.php');
require_once('./controllers/terminalListController.php');
require_once('./controllers/unitStatusController.php');
require_once('./controllers/unitIdlePercentController.php');
require_once('./controllers/unitEcmMpgController.php');
require_once('./controllers/unitActualMpgController.php');
require_once('./controllers/unitExpertFuelController.php');
require_once('./controllers/unitRepairSymptomController.php');
require_once('./controllers/unitRepairCostController.php');
require_once('./controllers/unitDetailController.php');
require_once('./controllers/unitTradeController.php');
require_once('./controllers/unitTradeDownloadController.php');

//Lets protect the router for now - Only signed in users may use it!
$OAuth = new OAuth();
#$OAuth -> protect_forget({REDACTED});


if(!empty($_GET['controller']))
{

    $controller = explode("/", $_GET['controller'])[0]; //Get the first part of the api endpoint. This will be our controller.
    if($controller == "divisions")
    {     
        $divList = new divisionListController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $divList -> post();
        }
    }
    elseif($controller == "terminals")
    {     
        $termList = new terminalListController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $termList -> post();
        }
    }
    elseif($controller == "unit_status")
    {     
        $unitStatus = new unitStatusController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $unitStatus -> post();
        }
    }
    elseif($controller == "unit_idle")
    {     
        $unitIdle = new unitIdlePercentController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $unitIdle -> post();
        }
    }
    elseif($controller == "unit_ecm_mpg")
    {     
        $unitEcmMpg = new unitEcmMpgController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $unitEcmMpg -> post();
        }
    }
    elseif($controller == "unit_actual_mpg")
    {     
        $unitActualMpg = new unitActualMpgController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $unitActualMpg -> post();
        }
    }
    elseif($controller == "unit_expert_fuel")
    {     
        $unitExpertFuel = new unitExpertFuelController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $unitExpertFuel -> post();
        }
    }
    elseif($controller == "unit_repair_symptom")
    {     
        $unitRepairSymptoms = new unitRepairSymptomController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $unitRepairSymptoms -> post();
        }
    }
    elseif($controller == "unit_repair_cost")
    {     
        $unitRepairCost = new unitRepairCostController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $unitRepairCost -> post();
        }
    }
    elseif($controller == "unit_detail_get") 
    {
        $unitDetail = new unitDetailController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $unitDetail -> post($_POST["unitNum"]);
        }
    }
    elseif($controller == "unit_detail_update") 
    {
        $unitDetail = new unitDetailController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $unitDetail -> update();
        }
    }
    elseif($controller == "unit_trade") 
    {
        $unitDetail = new unitTradeController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $unitDetail -> post();
        }
    }
    elseif($controller == "unit_trade_download") 
    {
        $unitDetail = new unitTradeDownloadController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $unitDetail -> post();
            $file = "C:/Apache24/htdocs/KPIGraphs/units/downloads/hello_world.xlsx";
            // Quick check to verify that the file exists
            if( !file_exists($file) ) die("File not found");
            // Force the download
            ob_end_clean();
            header( "Content-type: application/octet-stream" );
            header('Content-Disposition: attachment; filename="hello_world.xlsx"');
            header("Pragma: no-cache");
            header("Expires: 0");
            //ob_end_clean();
         
            readfile($file);
        }
    }
    else
    {
        http_response_code(404);
        $error = new error_response(404, "Not a valid endpoint.");
        echo json_encode($error);
        die();
    }
}
else
{
    http_response_code(404);
    $error = new error_response(404, "Not a valid request method or endpoint");
    echo json_encode($error);
    die(); 
}