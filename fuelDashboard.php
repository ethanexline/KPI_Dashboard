<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/OAuthSSO/Client/OAuthClient.php"); //Add the OAuthClient from the SSO system.
require_once('utility/utility.php');

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

        <title>Fleetmaster Express Fuel Dashboard</title>

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
        <link href="./vendor/open-iconic/font/css/open-iconic.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/sidebar.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
        <link href="css/global.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
        <link href="fuel/css/fuel.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="vendor/datatables/datatables.min.css<?php echo $utility -> MakeUrlVersion() ?>"/>
 
        <script src="vendor/jquery/jquery-3.5.1.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        
        <script src="vendor/plotly-latest.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="fuel/js/objects/sort-storage.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="fuel/js/utilities/local-storage.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

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

              

                <!-- Dashboard switch -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#" onClick="changeDashView('inquiry');">Fuel Inquiry</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" onClick="changeDashView('fuel_charts');">Charts</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" onClick="changeDashView('fuel_summary');">Summary</a>
                    </li>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sort-modal"><span class="oi" data-glyph="aperture"></span> Sorting Options</button>

                </ul>
                <!-- Dashboard switch end -->

                <!-- Main Area -->
                <div id="fuel">
                    <div id="inquiry" class="container-fluid graph-grids" style="display:block">
                        <br>
                        <div id="main-content" class="container-fluid main-container">
                            <div class="row">
                                <div class="col-md-5">
                                </div>
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-2"></div>
                                <div class="col-md-3">
                                    <div id="fuelInqToast" class="toast hide" role="alert" data-delay="7000" data-animation="true">
                                        <div class="toast-header">
                                            <div id="square" class="square"></div>
                                            <strong class="mr-auto">System Message</strong>
                                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="toast-body">
                                            <p id="alertText" class="alertText"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button style="float: right;" class="btn btn-primary" id="download-tran-btn" onClick="getTransactionsDownload()"><span class="oi" data-glyph="cloud-download"></span> Download Transactions</button>
                            <div class="loader-small" id="fuel-download-loader"></div>

                            <div style="content-align: center;">
                                <table class="table table-striped table-bordered nowrap" id="table" style="width: 100%">
                                    <thead class="border-left-3 border-dark">
                                        <tr>
                                            <th colspan="11"></th>
                                            <th colspan="3">Tractor</th>
                                            <th colspan="3">Reefer</th>
                                        </tr>
                                        <tr>
                                            <th>Unit</th>
                                            <th>Term</th>
                                            <th>Driver</th>
                                            <th>Date</th>
                                            <th>Stop Name</th>
                                            <th>Stop City</th>
                                            <th>St.</th>
                                            <th>CA</th>
                                            <th>DEF Gals</th>
                                            <th>Misc $</th>
                                            <th>Fee</th>
                                            <th>Gals</th>
                                            <th>$/Gal.</th>
                                            <th>Cost</th>
                                            <th>Gals</th>
                                            <th>$/Gal.</th>
                                            <th>Cost</th>
                                        </tr>
                                    </thead>
                                </table>
                                <br><br>
                            </div>

                            <div id="summaryCard" class="col-md-12">
                                <div class="card text-center">
                                    <h5 class="card-header" style="font-size: x-large">Summary</h5>
                                    <div class="card-body" style="font-size: large">
                                        <div class="row d-flex justify-content-around">

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <b><u>Tractor:</u></b>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 d-flex justify-content-around">
                                                        <div><b>Gals:</b>
                                                            <div class="float-right" style="padding-left: 7px;" id="tractor_gallons"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 d-flex justify-content-around">
                                                        <div><b>Cost:</b>
                                                            <div class="float-right" style="padding-left: 7px;" id="tractor_cost"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <b><u>Reefer:</u></b>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 d-flex justify-content-around">
                                                        <div><b>Gals:</b>
                                                            <div class="float-right" style="padding-left: 7px;" id="reefer_gallons"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 d-flex justify-content-around">
                                                        <div><b>Cost:</b>
                                                            <div class="float-right" style="padding-left: 7px;" id="reefer_cost"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <b><u>Fuel Total:</u></b>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 d-flex justify-content-around">
                                                        <div><b>Gals:</b>
                                                            <div class="float-right" style="padding-left: 7px;" id="fuel_total_gallons"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">   
                                                    <div class="col-md-12 d-flex justify-content-around"> 
                                                        <div><b>Cost:</b>
                                                            <div class="float-right" style="padding-left: 7px;" id="fuel_total_cost"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2"><b><u>DEF:</u></b>
                                                <div class="row">
                                                    <div class="col-md-12 d-flex justify-content-around">
                                                        <div><b>Gals:</b>
                                                            <div class="float-right" style="padding-left: 7px;" id="DEF_gallons"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 d-flex justify-content-around">
                                                        <div><b>Cost:</b>
                                                            <div class="float-right" style="padding-left: 7px;" id="DEF_cost"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2"><b><u>Grand Total:</u></b>
                                                <div class="row">
                                                    <div class="col-md-12 d-flex justify-content-around">
                                                        <div><b>Gals:</b>
                                                            <div class="float-right" style="padding-left: 7px;" id="grand_total_gallons"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 d-flex justify-content-around">
                                                        <div><b>Cost:</b>
                                                            <div class="float-right" style="padding-left: 7px;" id="grand_total_cost"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <br>

                                        <div class="row d-flex justify-content-around">
                                            <div class="col-md-2"><b><u>Fees:</u></b>
                                                <div id="sum_fees"></div>
                                            </div>

                                            <div class="col-md-2"><b><u>Disc/Gal:</u></b>
                                                <div id="discPG"></div>
                                            </div>

                                            <div class="col-md-2"><b><u>Avg $/Gal:</u></b>
                                                <div id="avgPPG"></div>
                                            </div>

                                            <div class="col-md-3"><b><u>Total Rebate:</u></b>
                                                <div id="sum_rebate"></div>
                                            </div>

                                            <div class="col-md-3"><b><u>Total Stops:</u></b>
                                                <div id="sum_stops"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <br><br><br>
                            
                        </div>
                    </div>
                    </div>

                    <div id="fuel_charts" class="container-fluid graph-grids" style="display:none">
                        <br>
                        <div class="row">
                            <div class="col-md-5">
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-3">
                                <div id="fuelChartToast" class="toast" role="alert" data-delay="7000" data-animation="true">
                                    <div class="toast-header">
                                        <div id="square" class="square"></div>
                                        <strong class="mr-auto">System Message</strong>
                                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="toast-body">
                                        <p id="alertText" class="alertText"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row date-row d-flex justify-content-center">
                            <div class="col-md-3 center-block date">
                                <div class="card text-center">
                                    <h5 class="card-header" style=" height: 3rem; margin-top: -5px; margin-bottom: -21px;">Start Date:</h5>
                                    <div class="card-body" style="height: 1rem;">
                                        <h6 class="card-text" id="disp-start-date"></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 center-block date">
                                <div class="card text-center">
                                    <h5 class="card-header" style=" height: 3rem; margin-top: -5px; margin-bottom: -21px;">End Date:</h5>
                                    <div class="card-body" style="height: 1rem;">
                                        <h6 class="card-text" id="disp-end-date"></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                            <!-- loading icon -->
                        <!-- <div id="loader">
                            <div class="loader"></div>
                        </div> -->

                        <div id="fuel_chart_body" class="container-fluid" style="display:block">
                            <div class="row full-row">
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="t_d_cost" class="graph-container" style="display: none;"></div>
                                    </div>
                                </div>

                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="d_ppg" class="graph-container" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>

                            <br><br>

                            <div class="row full-row">
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="fc_by_state" class="graph-container" style="display: none;"></div>
                                    </div>
                                </div>

                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="fc_by_chain" class="graph-container" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>

                            <br><br>

                            <div class="row full-row">
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="fc_by_term" class="graph-container" style="display: none;"></div>
                                    </div>
                                </div>

                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="fc_by_type" class="graph-container" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>

                            <br><br>
                        </div>
                    </div>

                    <div id="fuel_summary" class="container-fluid graph-grids" style="display:none">
                        <br>
                        <div class="row">
                            <div class="col-md-5">
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-3">
                                <div id="fuelSummaryToast" class="toast" role="alert" data-delay="7000" data-animation="true">
                                    <div class="toast-header">
                                        <div id="square" class="square"></div>
                                        <strong class="mr-auto">System Message</strong>
                                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="toast-body">
                                        <p id="alertText" class="alertText"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>

                            <!-- loading icon -->
                        <div id="loader">
                            <div class="loader"></div>
                        </div>

                        <div id="fuel_summary_charts" class="container-fluid" style="display:block">
                            <div class="row full-row">
                                <div class="col-md-5">*Regular sort does not apply to these charts.</div>

                                <div class="col-md-2 text-center">
                                    <label for="yearSort"><b>Sort by Year:</b></label>
                                    <select name="yearSort" id="yearSort" class="clearfix" onchange="yearSortButton();">
                                        <!-- years to sort by go here -->
                                    </select>

                                    <br><br><br>
                                    
                                </div>

                                <div class="col-md-5"></div>
                            </div>
                            <div class="row full-row">
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="discFSBC" class="graph-container" style="display: none;"></div>
                                    </div>
                                </div>

                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="avgDPG" class="graph-container" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>

                            <br><br>

                            <div class="row full-row">
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="POS" class="graph-container" style="display: none;"></div>
                                    </div>
                                </div>

                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="avgGalPD" class="graph-container" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>

                            <br><br>

                            <div class="row full-row">
                                <div class="col-md-12 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="fuelVBulk" class="graph-container" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>

                            <br><br>
                        </div>
                    </div>

                    <!-- Sorting Modal -->
                    <div id="sort-modal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1>Sorting Options</h1>
                                        <div>
                                        <label style="" for="sortby"><b>Sort By:</b></label>   
                                        <select name="sortby" id="sortby">
                                            <option value="time_desc" selected>Tran Date/Time Desc</option>
                                            <option value="time_asc">Tran Date/Time Asc</option>
                                            <option value="term">Terminal</option>
                                            <option value="stop">Stop Name</option>
                                            <option value="driver">Driver</option>
                                            <option value="city">City</option>
                                            <option value="st">State</option>
                                            <option value="trac_gals_desc">Trac Gals Desc</option>
                                            <option value="reef_gals_desc">Reef Gals Desc</option>
                                            <option value="DEF_gals_desc">DEF Gals Desc</option>
                                            <option value="trac_gals_asc">Trac Gals Asc</option>
                                            <option value="reef_gals_asc">Reef Gals Asc</option>
                                            <option value="DEF_gals_asc">DEF Gals Asc</option>
                                            <option value="t_cost_desc">Total Cost Desc</option>
                                            <option value="t_cost_asc">Total Cost Asc</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row" id="fuel-top-row">
                                                <div class="col-md-3" >
                                                    <b>Company:</b>
                                                    <br>
                                                    <INPUT checked="checked" type="radio" id='select-all-companies' value="" name="company" />Select All
                                                    <div id="sort-company" class="check-box-container">
                                                        <input name="company" value="102" type="radio" />Fleetmaster
                                                        <br />
                                                        <input name="company" value="301" type="radio" />Englander
                                                        <br>
                                                        <input name="company" value="*" type="radio" />All Without Term BUM

                                                    </div>
                                                </div>
                                                <form class="col-md-3">
                                                    <b>Chain:</b>
                                                    <br>
                                                    <INPUT type="radio" id='select-all-chains' value="" name="chain" checked="checked" /> Select All

                                                    <div id="sort-chains" class="check-box-container">
                                                        <!-- Chains here! -->
                                                    </div>
                                                </form>
                                                <div class="col-md-3">
                                                    <b>Terminal:</b>
                                                    <br>
                                                    <INPUT checked="checked" type="checkbox" id='select-all-terminals' onchange="sort.togglecheckboxes('checkbox-terminal', 'select-all-terminals')" /> Select All
                                                    <div id='sort-terminals' class="check-box-container">
                                                        <!-- Terminals here!-->
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <b>Division:</b>
                                                    <br>
                                                    <INPUT checked="checked" type="checkbox" id='select-all-divisions' onchange="sort.togglecheckboxes('checkbox-division', 'select-all-divisions')" name="chk[]" /> Select All

                                                    <div id="sort-divisions" class="check-box-container">
                                                        <!-- Divisions here! -->
                                                    </div>
                                                </div>
                                            </div>
                                            <br><hr>
                                            <div class="row" id="fuel-middle-row">
                                                
                                                <div class="col-md-3">
                                                    <b>City:</b>
                                                    <br>
                                                    <input id="city" type="text" name="city">
                                                    <br>
                                                    <b>Stop ID:</b>
                                                    <br>
                                                    <input id="stop_id" type="text" name="stop_id">
                                                    <br>
                                                    <b>Stop Name:</b>
                                                    <br>
                                                    <input id="stop_name" type="text" name="stop_name">
                                                    <br>
                                                    <b>Unit:</b>
                                                    <br>
                                                    <input type="text" name="unit" id="fuel_unit">
                                                    <br>
                                                    <b>Driver Code:</b>
                                                    <br>
                                                    <input type="text" name="driver_code" id="driver_code">
                                                    <br>
                                                    <b>Driver Name:</b>
                                                    <br>
                                                    <input type="text" name="driver_name" id="driver_name">
                                                    
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <b>State:</b>
                                                    <br>
                                                    <INPUT checked="checked" type="checkbox" id='select-all-states' onchange="sort.togglecheckboxes('checkbox-state', 'select-all-states')" /> Select All
                                                    <div id='sort-states' class="check-box-container">
                                                        <!-- States here!-->
                                                    </div>
                                                    <br>
                                                    <b>Start Date:</b>
                                                    <br>
                                                    <input type="date" id="start-date" name="sort-start-date">
                                                    <br>
                                                    <b>End Date:</b>
                                                    <br>
                                                    <input type="date" id="end-date" name="sort-end-date">
                                                    <br>
                                                    <b>Start Week:</b>
                                                    <br>
                                                    <input onchange="sort.updateStartDate(this)" id="start-week" type="week" name="week" id="camp-week" required>
                                                    <br>
                                                    <b>End Week:</b>
                                                    <br>
                                                    <input onchange="sort.updateEndDate(this)" id="end-week" type="week" name="week" id="camp-week" required>
                                                </div>
                                            
                                                <div class="col-md-2">
                                                <b>Out-of-Network:</b>
                                                <br>
                                                <input id="oon_I" type="radio" value="" name="oon" checked="checked">
                                                <label for="oon_I">Include</label>
                                                <br>
                                                <input id="oon_E" type="radio" value="E" name="oon">
                                                <label for="oon_E">Exclude</label>
                                                <br>
                                                <input id="oon_O" type="radio" value="O" name="oon">
                                                <label for="oon_O">Only</label>
                                                <br>


                                                <b>DEF:</b>
                                                <br>
                                                <input id="def" type="radio" value="" name="def" checked="checked">
                                                <label for="def">No Selection</label>
                                                <br>
                                                <input id="def_I" type="radio" value="I" name="def">
                                                <label for="def_I">Included DEF</label>
                                                <br>
                                                <input id="def_O" type="radio" value="O" name="def">
                                                <label for="def_O">Only DEF</label>
                                                <br>
                                                <input id="def_E" type="radio" value="E" name="def">
                                                <label for="def_E">Exclude DEF-only</label>
                                                <br>
                                                <input id="def_B" type="radio" value="B" name="def">
                                                <label for="def_B">Both DEF and Fuel</label>
                                                </div>

                                                <div class="col-md-2">
                                                <b>Non-Fuel:</b>
                                                <br>
                                                <input id="nf_I" type="radio" value="" name="nf" checked="checked">
                                                <label for="nf_I">Include</label>
                                                <br>
                                                <input id="nf_E" type="radio" value="E" name="nf">
                                                <label for="nf_E">Exclude</label>
                                                <br>
                                                <input id="nf_O" type="radio" value="O" name="nf">
                                                <label for="nf_O">Only</label>
                                                <br>
                                                <b>Bulk:</b>
                                                <br>
                                                <input id="bulk_I" type="radio" value="" name="bulk" checked="checked">
                                                <label for="bulk_I">Include</label>
                                                <br>
                                                <input id="bulk_E" type="radio" value="E" name="bulk">
                                                <label for="bulk_E">Exclude</label>
                                                <br>
                                                <input id="bulk_O" type="radio" value="O" name="bulk">
                                                <label for="bulk_O">Only</label>
                                                </div>

                                                <div class="col-md-2">
                                                <b>Reefer:</b>
                                                <br>
                                                <input id="rf" type="radio" value="" name="rf" checked="checked">
                                                <label for="rf">No Selection</label>
                                                <br>
                                                <input id="rf_O" type="radio" value="true" name="rf">
                                                <label for="rf_O">Only w/Reefer</label>
                                                <br>
                                                <input id="rf_E" type="radio" value="false" name="rf">
                                                <label for="rf_E">Only w/o Reefer</label>
                                                <br>
                                                <b>Fees:</b>
                                                <br>
                                                <input id="fee" type="radio" value="" name="fee" checked="checked">
                                                <label for="fee">No Selection</label>
                                                <br>
                                                <input id="fee_O" type="radio" value="true" name="fee">
                                                <label for="fee_O">Only w/Fees</label>
                                                <br>
                                                <input id="fee_E" type="radio" value="false" name="fee">
                                                <label for="fee_E">Only w/o Fees</label>
                                                </div>
                                            
                                            </div>
                                            <br><br>
                                            
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-info mr-auto" data-dismiss="modal" onClick="downloadSort();"><span class="oi" data-glyph="external-link"></span> Download Sort</button>
                                        <button type="submit" class="btn btn-primary" data-dismiss="modal" onClick="sort.GlobalSortFuel();"><span class="oi" data-glyph="aperture"></span> Sort</button>
                                        <button type="submit" class="btn btn-warning" onClick="sort.GlobalSortDefaultFuelBtn();" data-dismiss="modal"><span class="oi" data-glyph="loop"></span> Reset to Default</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal" onClick="util.toastLaunch('fuelInqToast', 'rgb(223,27,22)', 'No sort has been applied.'); util.toastLaunch('fuelSummaryToast', 'rgb(223,27,22)', 'No sort has been applied.'); util.toastLaunch('fuelChartToast', 'rgb(223,27,22)', 'No sort has been applied.')"><span class="oi" data-glyph="expand-up"></span> Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Sorting Modal End -->
                    </div>

                    <div class="modal fade fuel-modal-fullscreen" id="fuel-modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 id="trans-detail-modal">Transaction Detail</h1>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body" style="font-size: x-large">
                                    <div class="row full-row">
                                        <div class="col-md-2"></div>

                                        <div class="col-md-3">
                                            <div id="out-of-network" style="color: red !important;"></div>
                                        </div>

                                        <div class="col-md-2">
                                           
                                        </div>

                                        <div class="col-md-5"></div>

                                    </div>

                                    <div class="row full-row">
                                        <div class="col-md-4">
                                          
                                            <div class="clearfix"><b>Trn#:</b>
                                                        <div class="float-right" id="trn_num"></div>
                                            </div>
                                            <div class="clearfix"><b>Invoice#:</b>
                                                        <div class="float-right" id="invoice"></div>
                                            </div>
                                            <div class="clearfix"><b>Fuel Stop:</b>
                                                <div class="float-right" id="stop"></div>
                                            </div>

                                            <div class="clearfix"><b>Service Center:</b>
                                                <div class="float-right" id="chain_id"></div>
                                            </div>

                                            <div class="clearfix"><b>Service Flag:</b>
                                                <div class="float-right" id="service_flag"></div>
                                            </div>
                                            <div class="clearfix"><b>Account Code:</b>
                                                <p class="float-right" id="act_cd"></p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="clearfix"><b>Transaction Date:</b>
                                                        <div class="float-right" id="date"></div>
                                            </div>
                                            <div class="clearfix"><b>Ind:</b>
                                                        <div class="float-right" id="ind"></div>
                                            </div>
                                            <div class="clearfix"><b>Day:</b>
                                                        <div class="float-right" id="day"></div>
                                            </div>
                                          
                                            <div class="clearfix"><b>City, State:</b>
                                                <div class="float-right" id="location"></div>
                                            </div>

                                            <div class="clearfix"><b>Chain:</b>
                                                <div class="float-right" id="chain_name"></div>
                                            </div>

                                            <div class="clearfix"><b>Division:</b>
                                                <div class="float-right" id="division"></div>
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="clearfix"><b>Driver:</b>
                                                <div class="float-right" id="drv"></div>
                                            </div>
                                            <div class="clearfix"><b>Unit#:</b>
                                                        <div class="float-right" id="unitNumber"></div>
                                            </div>
                                            <div class="clearfix"><b>Terminal:</b>
                                                        <div class="float-right" id="terminal"></div>
                                            </div>
                                            <div class="clearfix"><b>Comcheck Card#:</b>
                                                        <div class="float-right" id="card"></div>
                                            </div>
                                            <div class="clearfix"><b>Hub:</b>
                                                        <div class="float-right" id="hub"></div>
                                            </div>
                                            <div class="clearfix"><b>Prev Hub:</b>
                                                        <div class="float-right" id="prev_hub"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <br><hr>
                                
                                    <div class="row full-row">
                                        <div class="col-md-2 d-flex justify-content-around">
                                            <div><b><u>Additional Products:</u></b>
                                                <div class="clearfix"><b>1)</b>
                                                    <div class="float-right" id="prod1_cost"></div>
                                                </div>

                                                <div class="clearfix"><b>2)</b>
                                                    <div class="float-right" id="prod2_cost"></div>
                                                </div>

                                                <div class="clearfix"><b>3)</b>
                                                    <div class="float-right border-bottom border-dark" id="prod3_cost"></div>
                                                </div>

                                                <div class="clearfix">
                                                    <div class="float-right" id="tot_prod_cost"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex justify-content-around">
                                            <div class="mx-auto"><b><u>Cash Advance:</u></b>
                                                <div class="clearfix"><b>Amt:</b>
                                                    <div class="float-right center-text" id="amt"></div>
                                                </div>

                                                <div class="clearfix"><b>Fee:</b>
                                                    <div class="float-right" id="feeModal"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 d-flex justify-content-around">
                                            <div style="width: 60%;"><b><u><center>Gallons:</center></u></b>
                                                <div class="clearfix"><b>Tractor:</b>
                                                    <div class="float-right center-text"id="trac_gals"></div>
                                                </div>
                                                
                                                <div class="clearfix border-bottom border-dark"><b>Reefer:</b>
                                                    <div class="float-right center-text" id="rf_gals"></div>
                                                </div>
                                                
                                                <div class="clearfix"><b>Total:</b>
                                                    <div class="float-right center-text"id="tot_gals"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-1 d-flex justify-content-around">
                                            <div class="mx-auto"><b><u>Price/Gal:</u></b>
                                                <div class="clearfix">
                                                    <div style="text-align: center;" id="trac_ppg"></div>
                                                </div>
                                                
                                                <div class="clearfix">
                                                    <div style="text-align: center;" id="rf_ppg"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 d-flex justify-content-around">
                                            <div style="width: 50%"><b><u><center>Fuel_Cost:</center></u></b>
                                                <div class="clearfix">
                                                    <div style="text-align: center;" id="trac_cost"></div>
                                                </div>
                                                
                                                <div class="clearfix border-bottom border-dark">
                                                    <div style="text-align: center;" id="rf_cost"></div>
                                                </div>
                                                
                                                <div class="clearfix">
                                                    <div style="text-align: center;" id="tot_cost"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-1"><b><u><center>Qty:</center></u></b>
                                            <div class="clearfix"><b>Oil:</b>
                                                <div class="float-right" id="oil_qt"></div>
                                            </div>
                                            
                                            <div class="clearfix"><b>DEF:</b>
                                                <div class="float-right" id="def_gal"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-1"><b><u><center>$/Gal:</center></u></b>
                                            <div class="clearfix">
                                                <div style="text-align: center;" id="oil_ppg"></div>
                                            </div>
                                            
                                            <div class="clearfix">
                                                <div style="text-align: center;" id="def_ppg"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-1"><b><u><center>Cost:</center></u></b>
                                        <div class="clearfix">
                                                <div style="text-align: center;" id="oil_cost"></div>
                                            </div>
                                            
                                            <div class="clearfix">
                                                <div style="text-align: center;" id="def_cost"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row full-row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-4" style="text-align: center"><b><u>Billing Flags:</u></b>

                                            <div class="row">

                                            <div class="col-md-3 d-flex justify-content-around"></div>

                                                <div class="col-md-3 d-flex justify-content-around">
                                                    <div class="clearfix"><b>Tractor:</b>
                                                        <div class="float-right" style="padding-left: 7px;" id="tractor"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 d-flex justify-content-around">
                                                    <div class="clearfix"><b>Prod #1:</b>
                                                        <div class="float-right" style="padding-left: 7px;" id="prod1"></div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                            <div class="col-md-3 d-flex justify-content-around"></div>

                                                <div class="col-md-3 d-flex justify-content-around">
                                                    <div class="clearfix"><b>Reefer:</b>
                                                        <div class="float-right" style="padding-left: 7px;" id="reefer"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 d-flex justify-content-around">
                                                    <div class="clearfix"><b>Prod #2:</b>
                                                        <div class="float-right" style="padding-left: 7px;" id="prod2"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                            <div class="col-md-3 d-flex justify-content-around"></div>

                                                <div class="col-md-3 d-flex justify-content-around">
                                                    <div class="clearfix"><b>Oil:</b>
                                                        <div class="float-right" style="padding-left: 7px;" id="oil"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 d-flex justify-content-around">
                                                    <div class="clearfix"><b>Prod #3:</b>
                                                        <div class="float-right" style="padding-left: 7px;" id="prod3"></div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-md-3 d-flex justify-content-around"></div>

                                                <div class="col-md-3 d-flex justify-content-around">
                                                    <div class="clearfix"><b>Cash:</b>
                                                        <div class="float-right" style="padding-left: 7px;" id="cash"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 d-flex justify-content-around">
                                                    <div class="clearfix"><b>Rebate:</b>
                                                        <div class="float-right" style="padding-left: 7px;" id="rebate"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3"></div>
                                        <div class="col-md-3">
                                            <div class="clearfix"><b>Rebate Amt:</b>
                                                <div class="float-right" id="rebate_amt"></div>
                                            </div>

                                            <div class="clearfix"><b>Adjusted $/Gal:</b>
                                                <div class="float-right" id="adj_ppg"></div>
                                            </div>

                                            <div class="clearfix"><b>Total Due:</b>
                                                <div class="float-right" id="grand_total"></div>
                                            </div>

                                            <div class="clearfix"><b>Total Fees:</b>
                                                <div class="float-right" id="tot_fees"></div>
                                            </div>

                                            <div class="col-md-1"></div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- /#page-content-wrapper -->
            </div>
        </div>
        <!-- /#wrapper -->
        
        <script src="vendor/jquery/jquery.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script type="text/javascript" src="vendor/datatables/datatables.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="fuel/js/objects/fuel_stacked_bar_graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="fuel/js/objects/fuel_pie_graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="fuel/js/objects/fuel_bar_graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="fuel/js/objects/fuel_state_chart.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="fuel/js/objects/fuel_line_graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

        <!-- Menu Toggle Script -->
        <script src="shared-components/sidebar.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="shared-components/js/breadcrumb.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        
        <script src="fuel/js/utilities/utility.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="fuel/js/utilities/sorting.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

        <script src="fuel/js/fuelDashboard.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    </body>
</html>