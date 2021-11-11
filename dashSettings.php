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
    <link href="./vendor/open-iconic/font/css/open-iconic.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/sidebar.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
    <link href="./css/global.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
    <link href="dash_home/css/home.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
    <script src="dash_home/js/utilities/local-storage.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

    <script src="./vendor/plotly-latest.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

    <style>

    </style>
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
            <b><h2 style="text-align: center;">User Settings</h2></b>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Weather
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Weather Location Setting</h5>
                            <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Location</span>
                            </div>
                            <select class="custom-select mr-sm-2" id="inlineFormCustomSelect">
                                <option value="NONE" selected>Terminal</option>
                                <option value="RKE">Roanoke, VA</option>
                                <option value="FTW">Fort Worth, TX</option>
                                <option value="COL">Columbus, OH</option>
                                <option value="ENM">Rustburg, VA</option>
                                <option value="MTC">Mountain City, TN</option>
                                <option value="ALB">Albany, GA</option>
                                <option value="CHT">Chestnut Hill, VA</option>
                                <option value="DAN">Danville, VA</option>
                                <option value="DEF">Deforest, VA</option>
                                <option value="EDE">Eden, NC</option>
                                <option value="FIN">Findlay, OH</option>
                                <option value="WMS">Williamsburg, VA</option>
                        </select>
                            </div style="layout:inline-block">
                            <div>
                                <a href="#" onClick="saveWeatherLocation()" id="location-msg" class="btn btn-primary">Save</a>
                            </div>
                        </div>
                    </div>
                </div>              
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Stocks
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Stock Ribbon Entries</h5>
                            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Other
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Special title treatment</h5>
                            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
            </div>

    
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="./vendor/jquery/jquery.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

    <!-- Menu Toggle Script -->
    <script src="shared-components/js/breadcrumb.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    <script src="./shared-components/sidebar.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    <script src="dash_home/js/utilities/utility.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    <script src="userSettings/js/settings.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

    <script>
        document.getElementById("admin").classList.add("active-option"); //Add the active option to the current page

    </script>
    </body>
</html>