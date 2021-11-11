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

        <title>Fleetmaster Express Tractors Dashboard</title>

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
        <link href="./vendor/open-iconic/font/css/open-iconic.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/sidebar.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
        <link href="css/global.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
        <link href="units/css/dashboard.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="vendor/datatables/datatables.min.css<?php echo $utility -> MakeUrlVersion() ?>"/>

        <script src="vendor/plotly-latest.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="units/js/objects/sort-storage.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="units/js/utilities/local-storage.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
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
                        <a class="nav-link active" data-toggle="tab" href="#" onClick="changeDashView('tractors')">Unit Charts</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" onClick="changeDashView('tractors_trade')">Unit Trade</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" id="formClick" onClick="changeDashView('tractor_forms')">Unit Lookup</a>
                    </li>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sort-modal"><span class="oi" data-glyph="aperture"></span> Sorting Options</button>
                </ul>

                <div id="tractors" class="container-fluid graph-grids" style="display:block">

                    <div id="main-content" class="container-fluid main-container">
                    <br>
                        <div class="row">
                            <div class="col-md-5">
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-3">
                            <div id="sortToast" class="toast" role="alert" data-delay="7000" data-animation="true">
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
                            <div class="col-md-3 center-block">
                                <div class="card text-center">
                                    <h5 class="card-header" style=" height: 3rem; margin-top: -5px; margin-bottom: -21px;">Start Date:</h5>
                                    <div class="card-body" style="height: 1rem;">
                                        <h6 class="card-text" id="disp-start-date"></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 center-block">
                                <div class="card text-center" style="height: 4rem; min-width: 140px">
                                    <h5 class="card-header" style=" height: 3rem; margin-top: -5px; margin-bottom: -21px;">End Date:</h5>
                                    <div class="card-body" style="height: 1rem;">
                                        <h6 class="card-text" id="disp-end-date"></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                                                <!-- Preset Button area -->
                        <div class="sort-presets">

                            <button id="default" class="btn btn-dark preset-button company-slice" onClick="sort.PresetSort(this.id)">All Companies</button>
                            <button id="flmr-all" class="btn btn-dark preset-button company-slice" onClick="sort.PresetSort(this.id)">Fleetmaster All</button>
                            <button id="flmr-otr" class="btn btn-dark preset-button company-slice" onClick="sort.PresetSort(this.id)">Fleetmaster OTR Trucking</button>
                            <button id="flmr-spot" class="btn btn-dark preset-button company-slice" onClick="sort.PresetSort(this.id)">Fleetmaster Dedicated/Spotting</button>
                            <button id="flmr-brokerage" class="btn btn-dark preset-button company-slice" onClick="sort.PresetSort(this.id)">Fleetmaster Brokerage</button>
                            <button id="eng-all" class="btn btn-dark preset-button company-slice" onClick="sort.PresetSort(this.id)">Englander All</button>
                        </div>
                            <!-- End button preset area -->
                        <div style="margin-top: 5px;" class="sort-presets">
                            <button id="last-52-weeks" class="btn btn-dark preset-button time-elapsed" onClick="sort.PresetSort(this.id)">Last 52 Weeks</button>
                            <button id="last-90-days" class="btn btn-dark preset-button time-elapsed" onClick="sort.PresetSort(this.id)">Last 90 Days</button>
                            <button id="last-4-weeks" class="btn btn-dark preset-button time-elapsed" onClick="sort.PresetSort(this.id)">Last 4 Weeks</button>

                        </div>
                            <br>
                            <!-- loading icon -->
                            <div id="loader">
                            <div class="loader"></div>
                            </div>
                            <!-- End loading icon -->
                            <div class="row justify-content-center" style="display:none">
                                <div class="col-md-12 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="unit-status" class="graph-container" ></div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row justify-content-center" style="display:none">
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="ecm-mpg" class="graph-container" ></div>
                                    </div>
                                </div>
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="actual-mpg" class="graph-container" ></div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row justify-content-center" style="display:none">
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="idle-percent" class="graph-container" ></div>
                                    </div>
                                </div>
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="expert-fuel" class="graph-container" ></div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row justify-content-center" style="display:none">
                                <div class="col-md-8 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="unit-repair-bar" class="graph-container" ></div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="unit-repair-pie" class="graph-container" ></div>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                        </div>
                    </div>

                    <!-- Tractor Graphs End -->

                    <div id="tractors_trade" class="container-fluid graph-grids" style="display:block">
                    <br>
                        <div class="row">
                            <div class="col-md-5">
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-3">
                                <div id="tradeToast" class="toast" role="alert" data-delay="7000" data-animation="true">
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
                        <div class="row date-row">

                            <div class="col-md-3"></div>

                            <div class="col-md-6 center-block">
                                <button id="BTH" class="btn btn-dark preset-button comp-button" onClick="sort.PresetSort(this.id)">All</button>

                                <button id="FLT" class="btn btn-dark preset-button comp-button" onClick="sort.PresetSort(this.id)">Fleetmaster</button>

                                <button id="ENG" class="btn btn-dark preset-button comp-button" onClick="sort.PresetSort(this.id)">Englander</button>
                            </div>
                        </div>

                        <div class="row date-row">

                            <div class="col-md-3"></div>

                            <div class="col-md-6 center-block">
                                <button id="stat-all" class="btn btn-dark preset-button stat-button" onClick="sort.PresetSort(this.id)">All Trade Statuses</button>

                                <button id="green" class="btn btn-dark preset-button stat-button" onClick="sort.PresetSort(this.id)">Not Approaching Trade</button>

                                <button id="yellow" class="btn btn-dark preset-button stat-button" onClick="sort.PresetSort(this.id)">Approaching Trade</button>

                                <button id="red" class="btn btn-dark preset-button stat-button" onClick="sort.PresetSort(this.id)">Needs to be Traded</button>
                            </div>

                        </div>
                        
                        <div class="row justify-content-around align-items-center">

                            <div class="card" style="flex-direction: row; width: 90%">
                                <div class="card-header text-center align-items-center d-flex">
                                    <div class="row align-items-center">
                                        <div class="col-md-12">
                                            <h5>Key:</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body align-items-center d-flex" style="padding: 0">
                                    <div class="row justify-content-around d-flex" style="width: 100%">
                                        <div class="col-md-3 pad-center align-items-center d-flex text-center"><div class="greenCircle float-left" style="margin-right: 3px; min-width: 20px"></div><p style="white-space: nowrap"> - Unlikely to pass 500k miles in next 26 weeks</p></div>
                                        <div class="col-md-3 pad-center align-items-center d-flex text-center"><div class="yellowCircle float-left" style="margin-right: 3px; min-width: 20px"></div><p style="white-space: nowrap"> - Likely to pass 500k miles in next 26 weeks</p></div>
                                        <div class="col-md-2 pad-center align-items-center d-flex text-center"><div class="redCircle float-left" style="margin-right: 3px; min-width: 20px"></div><p style="white-space: nowrap"> - Over 500k miles</p></div>
                                        <div class="col-md-2 pad-center align-items-center d-flex text-center"><span class="oi float-left" data-glyph="dollar"></span><p style="white-space: nowrap"> - In division 990</p></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <br>

                            <button style="float: right;" class="btn btn-primary" id="download-tran-btn" onClick="getUnitsDownload()"><span class="oi" data-glyph="cloud-download"></span> Export to Excel</button>
                            <div class="loader-small" id="unit-download-loader" style="display: none"></div>
                        <div id="main-content" class="container-fluid main-container">

                            <!-- End loading icon -->
                            <div style="content-align: center;">
                                <table class="table table-striped table-bordered nowrap" id="tradeTable" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Unit</th>
                                            <th>Model Year</th>
                                            <th>Make</th>
                                            <th>VIN</th>
                                            <th>Odometer</th>
                                            <th>Odometer Read On</th>
                                            <th>Last Week Miles</th>
                                            <th>Past 4 Weeks Avg. Mi.</th>
                                            <th>Lifetime Avg. Mi.</th>
                                            <th>Est. 500k Mi. Date</th>
                                            <th>Stat</th>
                                        </tr>
                                    </thead>
                                </table>
                                <br>
                            </div>
                        </div>
                        <br><br>
                    </div>

                    <div id="tractor_forms" class="container graph-grids" style="display:none">
                        <br><br>
                        <div class="row">
                            <div class="col-md-5">
                            </div>
                            <div class="col-md-2">
                                <h3 class="centered-text"><u>Unit Lookup</u></h3>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-3">
                                <div id="lookupToast" class="toast" role="alert" data-delay="7000" data-animation="true">
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

                        <br><br><br>

                        <form id="tractor_search" action="" class="bg-light" method="post" style="box-shadow: 0 9px 15px 0 rgba(0, 0, 0, 0.3), 0 6px 20px 0 rgba(0, 0, 0, 0.19); border: 1px solid white; padding: 30px;">
                            <div class="row">
                                <div class="form-group col-md-2 align-middle text-center">
                                    <h5>See the details for a specific tractor:</h5>
                                    <br>
                                    <input id="search" class="form-control" type="text" placeholder="(unit number)" maxlength="6">
                                    <br>
                                    <button id="search_btn" class="btn btn-warning" type="button" onClick="unit_detail_get(units_detail_callback);">Search</button>
                                </div>
                                <div id="lookupLoader" class="loader" style="display:none;"></div>
                                <div class="form-group col-md-3 search_divs" style="display:none; opacity: 1;animation-name: fadeInOpacity;animation-iteration-count: 1;animation-timing-function: ease-in;animation-duration: .5s;margin: auto;">
                                    <div class="clearfix"><b>Unit Number:</b>
                                        <div class="float-right" id="unit_num"></div>
                                    </div>
                                    <div class="clearfix"><b>Year:</b>
                                        <div class="float-right" id="unit_year"></div>
                                    </div>
                                    <div class="clearfix"><b>Make:</b>
                                        <div class="float-right" id="unit_make"></div>
                                    </div>
                                    <div class="clearfix"><b>Model:</b>
                                        <div class="float-right" id="unit_model"></div>
                                    </div>
                                    <div class="clearfix"><b>Unit Start Miles:</b>
                                        <div class="float-right" id="start_miles"></div>
                                    </div>
                                    <div class="clearfix"><b>Unit End Miles:</b>
                                        <div class="float-right" id="end_miles"></div>
                                    </div>
                                    <div class="clearfix"><b>First Load Date:</b>
                                        <div class="float-right" id="first_load_date"></div>
                                    </div>
                                    <div class="clearfix"><b>Termination Date:</b>
                                        <div class="float-right" id="termination_date"></div>
                                    </div>
                                    <div class="clearfix"><b>Delete Status:</b>
                                        <div class="float-right" id="delete_status"></div>
                                    </div>
                                    <div class="clearfix bottom"><b>Serial #:</b>
                                        <div class="float-right" id="serial_num"></div>
                                    </div>
                                </div>

                                <div class="form-group col-md-3 search_divs" style="display:none;opacity: 1;animation-name: fadeInOpacity;animation-iteration-count: 1;animation-timing-function: ease-in;animation-duration: .5s;margin: auto;">
                                    <div class="clearfix"><b>Lender:</b>
                                        <div class="float-right" id="lender"></div>
                                    </div>
                                    <div class="clearfix"><b>Depreciation Per Mile:</b>
                                        <div class="float-right" id="depreciate_per_mile"></div>
                                    </div>
                                    <div class="clearfix"><b>Projected Trade Date:</b>
                                        <div class="float-right" id="proj_trade_date"></div>
                                    </div>
                                    <div class="clearfix"><b>Acq. Price:</b>
                                        <div class="float-right" id="acquisition_price"></div>
                                    </div>
                                    <div class="clearfix"><b>Acq. Date:</b>
                                        <div class="float-right" id="acquisition_date"></div>
                                    </div>
                                    <div class="clearfix"><b>Loan Number:</b>
                                        <div class="float-right" id="loan_num"></div>
                                    </div>
                                    <div class="clearfix"><b>Interest Rate:</b>
                                        <div class="float-right" id="interest_rate"></div>
                                    </div>
                                    <div class="clearfix"><b>Loan Term:</b>
                                        <div class="float-right" id="loan_term"></div>
                                    </div>
                                    <div class="clearfix"><b>Sell Price:</b>
                                        <div class="float-right" id="sell_price"></div>
                                    </div>
                                    <div class="clearfix bottom"><b>Trade Type:</b>
                                        <div class="float-right" id="trade_type"></div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <br><br>

                        <form id="tractor_update" action="" class="bg-light" method="post" style="box-shadow: 0 9px 15px 0 rgba(0, 0, 0, 0.3), 0 6px 20px 0 rgba(0, 0, 0, 0.19); border: 1px solid white; padding: 30px;">
                            <h4 style="text-align: center;">Update unit details:</h4>
                            <br>
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label for="unit_number">Unit Number:</label>
                                    <input id="unit_number" class="form-control" type="text" name="unit_number" maxlength="6" required>
                                </div>
                                <div class="col-md-6"></div>
                                <div class="col-md-3">
                                    <div id="updateToast" class="toast" role="alert" data-delay="7000" data-animation="true">
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
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="lnd">Lender:</label>
                                    <input id="lnd" name="lnd" class="form-control" type="text">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="acq_pr">Acquisition Price:</label>
                                    <input id="acq_pr" class="form-control" type="text" name="aquisition_price">
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <p>Depreciate per Mile?</p>
                                    <div style="width: 100%; text-align: center;">
                                        <div class="form-check form-check-inline">
                                            <input name="depr" id="yes" class="form-check-input" type="radio" value="Y">
                                            <label class="form-check-label" for="yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input name="depr" id="no" class="form-check-input" type="radio" value="N">
                                            <label class="form-check-label" for="no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="proj">Projected Trade Date:</label>
                                    <input id="proj" class="form-control" type="date" name="projected_trade_date">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="acq_d">Acquisition Date:</label>
                                    <input id="acq_d" class="form-control" type="date" name="acquisition_date">
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="loan_no">Loan Number:</label>
                                    <input id="loan_no" class="form-control" type="number" name="loan_num">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="intr">Interest Rate:</label>
                                    <input id="intr" class="form-control" type="text" name="interest_rate" placeholder="(as decimal)">
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="loan_t">Term of Loan:</label>
                                    <input id="loan_t" class="form-control" type="number" name="loan_term" placeholder="(in months)">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="sell_pr">Sell Price:</label>
                                    <input id="sell_pr" class="form-control" type="text" name="sell_price">
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <label for="trade">Trade Type:</label>
                                <input id="trade" class="form-control" type="text" name="trade_type">
                            </div>
                            <br>

                            <button type="button" id="update_btn" class="btn btn-primary" onClick="unit_detail_update();">Submit</button>
                            <button type="button" id="reset_btn" class="btn btn-danger float-right" onClick="clearUpdate();">Reset</button>
                            <br><br><br>
                        </form>
                        <br><br>
                    </div>

                    <!-- Sorting Modal -->
                    <div id="sort-modal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1>Sorting Options</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <b>Company:</b>
                                                <br>
                                                <INPUT checked type="checkbox" id='select-all-companies' onchange="sort.togglecheckboxes('checkbox-company', 'select-all-companies')" name="chk[]" /> Select All
                                                <div id="sort-company" class="check-box-container">
                                                    <input checked name="checkbox-company" value="102" type="checkbox" />Fleetmaster
                                                    <br />
                                                    <input checked name="checkbox-company" value="301" type="checkbox" />Englander
                                                    <br />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <b>Division:</b>
                                                <br>
                                                <INPUT checked type="checkbox" id='select-all-divisions' onchange="sort.togglecheckboxes('checkbox-division', 'select-all-divisions')" name="chk[]" /> Select All

                                                <div id="sort-divisions" class="check-box-container">
                                                    <!-- Divisions here! -->
                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <b>Terminal:</b>
                                                <br>
                                                <INPUT checked type="checkbox" id='select-all-terminals' onchange="sort.togglecheckboxes('checkbox-terminal', 'select-all-terminals')" /> Select All
                                                <div id='sort-terminals' class="check-box-container">

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <b>Start Week:</b>
                                                <br>
                                                <input onchange="sort.updateStartDate(this)" id="start-week" type="week" name="week" min="2015-W01" id="camp-week" required>
                                                <br>
                                                <b>End Week:</b>
                                                <br>
                                                <input onchange="sort.updateEndDate(this)" id="end-week" type="week" name="week" min="2015-W01" id="camp-week" required>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <b>Start Date:</b>
                                                <br>
                                                <input type="date" id="start-date" min="2014-12-27" name="sort-start-date">
                                                <br>
                                                <b>End Date:</b>
                                                <br>
                                                <input type="date" id="end-date" min="2014-12-27" name="sort-end-date">
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" data-dismiss="modal" onClick="sort.GlobalSort();"><span class="oi" data-glyph="aperture"></span> Sort</button>
                                    <button type="submit" class="btn btn-warning" onClick="sort.GlobalSortDefaultBtn();" data-dismiss="modal"><span class="oi" data-glyph="loop"></span> Reset to Default</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" onClick="util.toastLaunch('sortToast', 'rgb(223,27,22)', 'No sort has been applied.'); util.toastLaunch('tradeToast', 'rgb(223,27,22)', 'No sort has been applied.'); util.toastLaunch('lookupToast', 'rgb(223,27,22)', 'No sort has been applied.')"><span class="oi" data-glyph="expand-up"></span> Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sorting Modal End -->

                    <!-- Unit Modal Fullscreen -->
                    <div class="modal fade unit-modal-fullscreen" id="unit-modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 id="unit-week-detail-modal"></h1>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row full-row">
                                        <div class="col-md-3 col-flmr">
                                            <div id="" class="graph-container"></div>

                                        </div>
                                        <div class="col-md-6 col-flmr">
                                            <div id="" class="graph-container"></div>
                                        </div>

                                        <div class="col-md-3 col-flmr">
                                            <div id="" class="graph-container"></div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-3 col-flmr">
                                            <div id="" class="graph-container"></div>
                                        </div>
                                        <div class="col-md-6 col-flmr">
                                            <div id="" class="graph-container"></div>
                                        </div>
                                        <div class="col-md-3 col-flmr">
                                            <div id="" class="graph-container"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
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

        <!-- Menu Toggle Script -->
        <script src="shared-components/sidebar.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

        <script src="units/js/objects/units-stacked-bar-graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="units/js/objects/units-line-graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="units/js/objects/units-pie-graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="units/js/objects/units-bar-graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="units/js/objects/units-detail.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

        <script src="units/js/utilities/utility.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="units/js/utilities/sorting.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="shared-components/js/breadcrumb.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="units/js/unitDashboard.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    </body>
</html>