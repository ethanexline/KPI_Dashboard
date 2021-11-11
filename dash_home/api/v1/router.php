<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/OAuthSSO/Client/OAuthClient.php"); //Add the OAuthClient from the SSO system.
require_once("../config/database.php"); //Add the database.php file for database interaction
require_once("../objects/error_response.php"); //Error response object
require_once('../controllers/dailyRevenueController.php');
require_once('../controllers/dailyRevenueControllerDedicated.php');
require_once('../controllers/newsRSSController.php');

// //Lets protect the router for now - Only signed in users may use it!
// $OAuth = new OAuth();
// $OAuth -> protect_forget({REDACTED});


if(!empty($_GET['controller']))
{

    $controller = explode("/", $_GET['controller'])[0]; //Get the first part of the api endpoint. This will be our controller.
    if($controller == "daily_revenue")
    {     
        $dailyRevenue = new dailyRevenueController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $dailyRevenue -> post();
        }
    }
    elseif($controller == "daily_revenue_dedicated")
    {     
        $dailyRevenue = new dailyRevenueControllerDedicated();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {  
            $dailyRevenue -> post();
        }
    }
    elseif($controller == "news_rss")
    {
        $news = new newsRSSController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $news -> post();
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