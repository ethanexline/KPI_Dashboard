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

        <title>Fleetmaster Express Order Dashboard</title>

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
        <link href="./vendor/open-iconic/font/css/open-iconic.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/sidebar.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
        <link href="css/global.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
        <link href="orders/css/dashboard.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">

        <script src="vendor/plotly-latest.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="orders/js/objects/sort-storage.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="orders/js/utilities/local-storage.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
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
                        <a class="nav-link active" data-toggle="tab" href="#" onClick="changeDashView('order_revenue');">Orders Charts</a>
                    </li>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sort-modal"><span class="oi" data-glyph="aperture"></span> Sorting Options</button>

                </ul>
                <!-- Dashboard switch end -->

                <!-- Main Dashboard Chart Area -->
                <div id="main-content" class="container-fluid main-container">
                    <!-- Order Revenue Dashboard -->
                    <div id="order_revenue" class="container-fluid graph-grids" style="display:block">
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
                                <div class="card text-center">
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

                            <div class="row full-row justify-content-center" style="display:none">
                                <div class="col-md-3 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="commodity-revenue" class="graph-container"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="trucking-revenue" class="graph-container" ></div>
                                    </div>
                                </div>
                                <div class="col-md-3 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="rate-per-mile" class="graph-container"></div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row full-row justify-content-center" style="display:none">
                            <div class="col-md-4 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="supplemental-revenue" class="graph-container" ></div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="customer-revenue" class="graph-container" ></div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="order-count" class="graph-container" ></div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row justify-content-center" style="display:none">
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="hourly-revenue-line" class="graph-container" ></div>
                                    </div>
                                </div>
                                <div class="col-md-6 px-md-3">
                                    <div class="p-3 border bg-light">
                                        <div id="state-revenue" class="graph-container" ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Order Revenue Dashboard End -->

                        <br><br>

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
                                        <button type="button" class="btn btn-danger" data-dismiss="modal" onClick="util.toastLaunch('sortToast', 'rgb(223,27,22)', 'No sort has been applied.')"><span class="oi" data-glyph="expand-up"></span> Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Sorting Modal End -->

                        <!-- Order Modal Fullscreen -->
                        <div class="modal fade order-modal-fullscreen" id="order-modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 id="order-week-detail-modal"></h1>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row full-row">
                                            <div class="col-md-3 col-flmr">
                                                <div id="detail-commodity-revenue" class="graph-container"></div>

                                            </div>
                                            <div class="col-md-6 col-flmr">
                                                <div id="daily-revenue" class="graph-container"></div>
                                            </div>

                                            <div class="col-md-3 col-flmr">
                                                <div id="detail-rate-per-mile" class="graph-container"></div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-3 col-flmr">
                                                <div id="detail-customer-revenue" class="graph-container"></div>
                                            </div>
                                            <div class="col-md-9 col-flmr">
                                                <div id="detail-state-revenue" class="graph-container"></div>
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
        </div>

        <script src="vendor/jquery/jquery.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

        <!-- Menu Toggle Script -->
        <script src="shared-components/sidebar.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

        <script src="orders/js/objects/order-line-graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="orders/js/objects/order-bar-graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="orders/js/objects/order-h-bar-graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="orders/js/objects/order-indicator-data-card.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="orders/js/objects/order-state-map.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="orders/js/objects/order-pie-graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script src="orders/js/objects/order-customer-pie-graph.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

        <script src="orders/js/utilities/utility.js"></script>
        <script src="orders/js/utilities/sorting.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

        <script src="shared-components/js/breadcrumb.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
        <script>
            breadcrumbs.addBreadCrumbs("Orders Dashboard", 'http://localhost/kpiGraphs/orderDashboard', true);    
        </script>

        <script src="orders/js/orderDashboard.js<?php echo $utility -> MakeUrlVersion() ?>"></script>
    </body>
</html>