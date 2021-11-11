<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/OAuthSSO/Client/OAuthClient.php"); //Add the OAuthClient from the SSO system.
require_once('utility/utility.php');
require_once('dash_home/rss.php');

$OAuth = new OAuth();
$OAuth -> protect({REDACTED});

$utility = new utils();
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fleetmaster Express Dashboard Home</title>

    <!-- Bootstrap core CSS -->
    <link href="./vendor/bootstrap/css/bootstrap.min.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
    <link href="./vendor/open-iconic/font/css/open-iconic.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/sidebar.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
    <link href="./css/global.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
    <link href="dash_home/css/home.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
    <script src="dash_home/js/utilities/local-storage.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    <script src="./vendor/plotly-latest.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
  </head>

  <body>

    <div class="d-flex" id="wrapper">

      <!-- Sidebar -->
      <?php include "shared-components/sidebar.php"; ?>
      <!-- SideBar END -->

      <!-- Top Bar -->
      <?php include "shared-components/topbar.php"; ?>
      <!-- Top Bar END -->

      <!-- Page Content -->
      <div id="page-content-wrapper">

      <!-- Dashboard switch end -->


        <div class="row">
          <div class="col px-md-12">
            <div class="p-3 border bg-light">
                  <!-- TradingView Widget BEGIN -->
                  <div class="tradingview-widget-container">
                    <div class="tradingview-widget-container__widget"></div>
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                    {
                    "symbols": [
                      {
                        "proName":"CURRENCYCOM:US30",
                        "title":"DOW JONES 30"
                      },
                      {
                        "proName": "FOREXCOM:SPXUSD",
                        "title": "S&P 500"
                      },
                      {
                        "proName": "FOREXCOM:NSXUSD",
                        "title": "Nasdaq 100"
                      },
                      {
                        "proName": "FX_IDC:EURUSD",
                        "title": "EUR/USD"
                      },
                    
                      {
                        "proName": "BITSTAMP:ETHUSD",
                        "title": "ETH/USD"
                      }
                    ],
                    "colorTheme": "dark",
                    "isTransparent": false,
                    "displayMode": "adaptive",
                    "locale": "en"
                  }
                    </script>
                  </div>
                  <!-- TradingView Widget END -->
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col px-md-12">
            <div class="p-3 border bg-light">
              <div class="news-ribbon" id="news-ribbon"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col px-md-8">
            <div class="p-3 border bg-light" id="weather-div">
        
            </div>
          </div>
          <div class="col px-md-8">
            <div class="p-3 border bg-light">
              <div id="MyClockDisplay" class="clock" onload="showTime()"></div>
            </div>
          </div>
        </div>
        
        <!-- Graphs! -->
        <div class="row">
          <div class="col px-md-6">
            <div class="p-3 border bg-light">        
              <div id="chart1"></div>
            </div>
          </div>
          <div class="col px-md-6">
            <div class="p-3 border bg-light">        
              <div id="chart2"></div>
            </div>
          </div>
        </div>

        <div class="row">
        <div class="col px-md-6">
            <div class="p-3 border bg-light">        
              <div id="chart3"></div>
            </div>
          </div>   
          <div class="col px-md-6">
            <div class="p-3 border bg-light">        
              <div id="chart4"></div>
            </div>
          </div>  
        </div>

        <div class="row">
          
          <div class="col px-md-12">
            <div class="p-3 border bg-light">
              <div style="background-color: black; color: white; height:455px;width:100%;border:1px solid #ccc; overflow:auto; font-family: arial">
                <?php buildFuelPriceRSS(); ?>
              </div>
            </div>
          </div>
        </div>
        <br><br>
      </div>
      <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="./vendor/jquery/jquery.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    <script src="dash_home/js/graphs/home_line_graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

    <!-- Menu Toggle Script -->
    <script src="./shared-components/sidebar.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    <script src="shared-components/js/breadcrumb.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    <script src="dash_home/js/home.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    <script src="dash_home/js/utilities/utility.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
  </body>

</html>