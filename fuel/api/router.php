<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/OAuthSSO/Client/OAuthClient.php"); //Add the OAuthClient from the SSO system.
require_once("./config/database.php"); //Add the database.php file for database interaction
require_once("./objects/error_response.php"); //Error response object
require_once("./objects/transactionSummary.php");
require_once('./controllers/TransactionDetailsController.php');
require_once('./controllers/TransactionSummaryController.php');
require_once('./controllers/TransactionListController.php');
require_once('./controllers/TransactionDownloadController.php');
require_once('./controllers/FuelDailyCostController.php');
require_once('./controllers/PPGByDayController.php');
require_once('./controllers/CostByStateController.php');
require_once('./controllers/CostByChainController.php');
require_once('./controllers/CostByTerminalController.php');
require_once('./controllers/CostByFuelTypeController.php');
require_once('./controllers/POSController.php');
require_once('./controllers/FuelVBulkController.php');
require_once('./controllers/AvgGPDController.php');
require_once('./controllers/AvgDPGController.php');
require_once('./controllers/DiscFSBCController.php');
require_once('./controllers/divisionListController.php');
require_once('./controllers/terminalListController.php');
require_once('./controllers/chainListController.php');
require_once('./controllers/stateListController.php');
require_once('./controllers/yearListController.php');



//Lets protect the router for now - Only signed in users may use it!
$OAuth = new OAuth();
$OAuth -> protect_forget({REDACTED});


if(!empty($_GET['controller']))
{

    $controller = explode("/", $_GET['controller'])[0]; //Get the first part of the api endpoint. This will be our controller.
    //Check what controller to use.
    if($controller == "transaction_details")
    {     
        $TransactionDetails = new TransactionDetailsController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $TransactionDetails -> post();
        }
    }
    elseif($controller == "transaction_list")
    {     
        $TransactionList = new TransactionListController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            echo $TransactionList -> post();
        }
    }
    elseif($controller == "transaction_summary")
    {     
        $TransactionSummary = new TransactionSummaryController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            echo $TransactionSummary -> post();
        }
    }
    elseif($controller == "transaction_download")
    {     
        $TransactionDownload = new TransactionDownloadController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            echo $TransactionDownload -> post();
        }
    }
    elseif($controller == "fuel_daily_cost")
    {
        $fuelDailyCost = new FuelDailyCostController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $fuelDailyCost -> post();
        }
    }
    elseif($controller == "ppg_by_day")
    {
        $PPGByDay = new PPGByDayController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $PPGByDay -> post();
        }
    }
    elseif($controller == "cost_by_state")
    {
        $CostByState = new CostByStateController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $CostByState -> post();
        }
    }
    elseif($controller == "cost_by_chain")
    {
        $CostByChain = new CostByChainController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $CostByChain -> post();
        }
    }
    elseif($controller == "cost_by_terminal")
    {
        $CostByTerminal = new CostByTerminalController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $CostByTerminal -> post();
        }
    }
    elseif($controller == "cost_by_fuel_type")
    {
        $CostByFuelType = new CostByFuelTypeController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $CostByFuelType -> post();
        }
    }
    elseif($controller == "POS")
    {
        $POS = new POSController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $POS -> post();
        }
    }
    elseif($controller == "fuel_v_bulk")
    {
        $FuelVBulk = new FuelVBulkController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $FuelVBulk -> post();
        }
    }
    elseif($controller == "avg_gpd")
    {
        $AvgGPD = new AvgGPDController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $AvgGPD -> post();
        }
    }
    elseif($controller == "avg_dpg")
    {
        $AvgDPG = new AvgDPGController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $AvgDPG -> post();
        }
    }
    elseif($controller == "disc_FSBC")
    {
        $discFSBC = new DiscFSBCController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $discFSBC -> post();
        }
    }
    elseif($controller == "divisions")
    {     
        $divList = new DivisionListController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $divList -> post();
        }
    }
    elseif($controller == "terminals")
    {     
        $termList = new TerminalListController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $termList -> post();
        }
    }
    elseif($controller == "states")
    {
        $stateList = new StateListController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $stateList -> post();
        }
    }
    elseif($controller == "chains")
    {
        $chainList = new ChainListController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $chainList -> post();
        }
    }
    elseif($controller == "years")
    {
        $yearList = new YearListController();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            echo $yearList -> post();
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