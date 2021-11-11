<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/OAuthSSO/Client/OAuthClient.php"); //Add the OAuthClient from the SSO system.
require_once("./config/database.php"); //Add the database.php file for database interaction
require_once("./objects/error_response.php"); //Error response object

//Controllers
require_once('./controllers/origDestRevenueController.php');
require_once('./controllers/origDestRpmController.php');
require_once('./controllers/customerRevenueController.php');
require_once('./controllers/brokerRevenueController.php');
require_once('./controllers/RpmByWeekController.php');
require_once('./controllers/Top5RevenueController.php');
require_once('./controllers/divisionListController.php');
require_once('./controllers/terminalListController.php');
require_once('./controllers/origDestBrokerRevenueController.php');

//Lets protect the router for now - Only signed in users may use it!
// $OAuth = new OAuth();
// $OAuth -> protect_forget({REDACTED});
if(!empty($_GET['controller']))
{

    $controller = explode("/", $_GET['controller'])[0]; //Get the first part of the api endpoint. This will be our controller.
    if($controller == "state_revenue_orig_dest")
    {     
        $origDestController = new origDestRevenueController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $origDestController -> post();
        }
    }
    elseif($controller == "state_rpm_orig_dest")
    {     
        $origDestRpmController = new origDestRpmController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $origDestRpmController -> post();
        }
    }
    elseif($controller == "state_broker_orig_dest")
    {     
        $origDestBrokerController = new origDestBrokerController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $origDestBrokerController -> post();
        }
    }
    elseif($controller == "customer_revenue")
    {     
        $customerRevenueController = new customerRevenueController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $customerRevenueController -> post();
        }
    }
    elseif($controller == "broker_revenue")
    {     
        $brokerRevenueController = new brokerRevenueController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $brokerRevenueController -> post();
        }
    }
    elseif($controller == "rpm_by_week")
    {     
        $RpmByWeekController = new RpmByWeekController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $RpmByWeekController -> post();
        }
    }
    elseif($controller == "top_5_rev")
    {     
        $Top5RevenueController = new Top5RevenueController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $Top5RevenueController -> post();
        }
    }
    elseif($controller == "divisions")
    {     
        $divisonListController = new divisionListController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $divisonListController -> post();
        }
    }
    elseif($controller == "terminals")
    {     
        $terminalListController = new terminalListController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $terminalListController -> post();
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