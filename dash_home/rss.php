<?php
class gasPrice
{
  public $description;
  public $price;

  function __construct($description, $price)
  {
    $this -> description = $description;
    $this -> price = $price;  
  }
}

class returnObject
{
  public $title;
  public $date;
  public $transactions;
  
  function __construct($title, $date, $transactions)
  {
    $this -> title = $title;
    $this -> date = $date;  
    $this -> transactions = $transactions;  
  }
}

function getFuelPrices()
{
  $feed = implode(file('https://www.eia.gov/petroleum/gasdiesel/includes/gas_diesel_rss.xml'));
  $xml = simplexml_load_string($feed);

  $title = $xml -> channel -> description;
  $sub_title = $xml -> channel -> link;
  $date = $xml -> channel -> item -> title;
  $descriptions = explode("<br/>", $xml -> channel -> item -> description);
  $new = array_map('trim', $descriptions);

  $all_classes = array();
  $class = array();
  

  foreach($new as $description)
  {
    if($description != "" and $description != "Summary Excerpt:" and $description != "(Dollars per Gallon)")
    {
        if($description == "Regular Gasoline Retail Price" or $description == "Cities" or $description == "States" or $description == "On-Highway Diesel Fuel Retail Price")
        {
          if(count($class) > 0)
          {
            array_push($all_classes, $class);
          }
          $class = array();

        }
        else
        {
          $delimiter = "...";
          $valid = false;
          if(strpos($description, ' .. ') == true)
          {
            $delimiter = "..";
            $valid = true;
          }
          elseif(strpos($description, ' ... ') == true)
          {
            $delimiter = "...";
            $valid = true;
          }
          elseif(strpos($description, ' .... ') == true)
          {
            $delimiter = "....";
            $valid = true;
          }
          elseif(strpos($description, ' ..... ') == true)
          {
            $delimiter = ".....";
            $valid = true;
          }

          if ($valid) {
          $desc = explode($delimiter, $description)[1];
          $price = explode($delimiter, $description)[0];
          array_push($class, new gasPrice($desc, $price));
          
        }
      }

    }


  }
  array_push($all_classes, $class);

  return new returnObject($title, $date, $all_classes);
}

function buildFuelPriceRSS() 
{
  $details = getFuelPrices();
  
  echo "<br><h5>" . $details -> title . "</h5>";
  echo "<h5>*" . $details -> date . "*</h5>";


  echo '<div class="row">';

  //First gas price detail
  echo '<div class="col-md-3"><b>Regular Gasoline Retail Price</b>';
  echo '<table class="table table-dark">
          <thead>
          <tr>
            <th scope="col">Location</th>
            <th scope="col">Price</th>
          </tr>
          </thead>
        <tbody>
        ';
  foreach($details -> transactions[0] as $tran)
  {
    echo '<tr>';
    echo '<td>' . $tran -> description . '</td>';
    echo '<td>$' . $tran -> price . '</td>';
    echo '</tr>';
  }
  
  echo '</tbody>
        </table>
        </div>';

  //Next up
  echo'<div class="col-md-3"><b>On-Highway Diesel Fuel Retail Price</b>';
  echo '<table class="table table-dark">
          <thead>
          <tr>
            <th scope="col">Location</th>
            <th scope="col">Price</th>
          </tr>
          </thead>
        <tbody>
        ';
  
  foreach($details -> transactions[3] as $tran)
  {
    echo '<tr>';
    echo '<td>' . $tran -> description . '</td>';
    echo '<td>$' . $tran -> price . '</td>';
    echo '</tr>';
  }
  
  echo '</tbody>
        </table>
        </div>';
  


  //First gas price detail
  echo '<div class="col-md-3"><b>States</b>';
  echo '<table class="table table-dark">
          <thead>
          <tr>
            <th scope="col">Location</th>
            <th scope="col">Price</th>
          </tr>
          </thead>
        <tbody>
        ';
  foreach($details -> transactions[1] as $tran)
  {
    echo '<tr>';
    echo '<td>' . $tran -> description . '</td>';
    echo '<td>$' . $tran -> price . '</td>';
    echo '</tr>';
  }
  
  echo '</tbody>
        </table>
        </div>';

  //Next up
  echo'<div class="col-md-3"><b>Cities</b>';
  echo '<table class="table table-dark">
          <thead>
          <tr>
            <th scope="col">Location</th>
            <th scope="col">Price</th>
          </tr>
          </thead>
        <tbody>
        ';
  
  foreach($details -> transactions[2] as $tran)
  {
    echo '<tr>';
    echo '<td>' . $tran -> description . '</td>';
    echo '<td>$' . $tran -> price . '</td>';
    echo '</tr>';
  }
  
  echo '</tbody>
        </table>
        </div>';
  
  //end of row!
  echo '</div>';
}

?>