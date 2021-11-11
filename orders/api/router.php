<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/OAuthSSO/Client/OAuthClient.php"); //Add the OAuthClient from the SSO system.
require_once("./config/database.php"); //Add the database.php file for database interaction
require_once("./objects/error_response.php"); //Error response object
require_once('./controllers/ordersCountController.php');
require_once('./controllers/weeklyRevenueController.php');
require_once('./controllers/dailyRevenueController.php');
require_once('./controllers/ratePerMileController.php');
require_once('./controllers/weeklyLoadedMilesController.php');
require_once('./controllers/revenueByState.php');
require_once('./controllers/commodityRevenueController.php');
require_once('./controllers/customerRevenueController.php');
require_once('./controllers/divisionListController.php');
require_once('./controllers/terminalListController.php');
require_once('./controllers/supplementalOrderController.php');


//Lets protect the router for now - Only signed in users may use it!
$OAuth = new OAuth();
$OAuth -> protect_forget({REDACTED});


if(!empty($_GET['controller']))
{

    $controller = explode("/", $_GET['controller'])[0]; //Get the first part of the api endpoint. This will be our controller.
    if($controller == "orders_count")
    {     
        $ordersCount = new ordersCountController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $ordersCount -> post();
        }
    }
    elseif($controller == "supplemental_revenue")
    {     
        $supplementalRevenue = new supplementalRevenueController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $supplementalRevenue -> post();
        }
    }
    elseif($controller == "weekly_revenue")
    {     
        $weeklyRevenue = new weeklyRevenueController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $weeklyRevenue -> post();
        }
    }
    elseif($controller == "daily_revenue")
    {     
        $dailyRevenue = new dailyRevenueController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $dailyRevenue -> post();
        }
    }
    elseif($controller == "hourly_revenue")
    {     
        $hourlyRevenue = new hourlyRevenueController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') 
        {  
            $hourlyRevenue -> get();
        }
    }
    elseif($controller == "rpm")
    {     
        $hourlyRevenue = new ratePerMileController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $hourlyRevenue -> post();
        }
    }
    elseif($controller == "loaded_miles")
    {     
        $hourlyRevenue = new weeklyLoadedMilesController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $hourlyRevenue -> post();
        }
    }
    elseif($controller == "state_revenue")
    {     
        $stateRevenue = new revenueByState();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $stateRevenue -> post();
        }
    }
    elseif($controller == "commodity_revenue")
    {     
        $commodityRevenue = new commodityRevenueController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $commodityRevenue -> post();
        }
    }
    elseif($controller == "customer_revenue")
    {     
        $customerRevenue = new customerRevenueController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $customerRevenue -> post();
        }
    }
    elseif($controller == "divisions")
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