<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

class newsRSSController
{
    function get_news_rss()
    {
        $news = simplexml_load_file('https://www.ttnews.com/rss.xml');

        $feeds = array();
        
        $i = 0;
        
        foreach ($news->channel->item as $item) 
        {
            $feeds[$i]['title'] = (string) $item->title;
            $feeds[$i]['link'] = (string) $item->link;
            $feeds[$i]['date'] = (string) $item->pubDate;
            $feeds[$i]['description'] = (string)$item->description;
            $feeds[$i]['source'] = "Transport Topics";
        
            $i++;
        }
        
        return $feeds;
    }

    //Implementation of the POST request
    function post()
    {
        
        if($_POST['sort'] == "")
        {
            echo json_encode($this -> get_news_rss());
        }
        else
        {
            http_response_code(404);
            $error = new error_response(404, "Something ain't right here");
            echo json_encode($error);
            die();
        }
    }
}