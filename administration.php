<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/OAuthSSO/Client/OAuthClient.php"); //Add the OAuthClient from the SSO system.
require_once('utility/utility.php');
require_once("utility/config/database.php"); //Add the database.php file for database interaction

$OAuth = new OAuth();
$utility = new utils();

$OAuth -> protect({REDACTED});
$OAuth -> protect({REDACTED});

$database = new database();
$connection = $database -> connection();

$avg_sql = "
    SELECT avg({REDACTED}) '{REDACTED}', avg({REDACTED}) 'avg_mb_read' FROM
    (
        SELECT 
            ({REDACTED} - coalesce(lag({REDACTED}) over (order by date), 0)) / 1000000 as 'mb_written',
            ({REDACTED} - coalesce(lag({REDACTED}) over (order by date), 0)) / 1000000 as  'mb_read',
            date
        FROM [{REDACTED}.[{REDACTED}].[{REDACTED}]
        WHERE [{REDACTED}] = '{REDACTED}'
    ) e 
    WHERE e.date != '2020-07-15'
    ";

//Fo the chart
$usage_chart_sql = "
    SELECT {REDACTED} 'avg_mb_written', {REDACTED} 'avg_mb_read', cast([date] as date) 'date' FROM
    (
        SELECT 
            ({REDACTED} - coalesce(lag({REDACTED}) over (order by date), 0)) / 1000000 as 'mb_written',
            ({REDACTED} - coalesce(lag({REDACTED}) over (order by date), 0)) / 1000000 as  'mb_read',
            date
        FROM [{REDACTED}].[{REDACTED}].[{REDACTED}]
        WHERE [{REDACTED}] = '{REDACTED}'
    ) e 
    WHERE e.date != '2020-07-15' and e.date > cast(dateadd(day, -7, getdate()) as date)
    ";

$warehouse_size_sql = "
    SELECT      
                CONVERT(VARCHAR,SUM({REDACTED})*8/1024) AS 'mb_size'
    FROM        {REDACTED}  
    JOIN        {REDACTED}  
    ON          {REDACTED} = {REDACTED}  
    WHERE {REDACTED}   = '{REDACTED}'
    GROUP BY    {REDACTED} 
    ORDER BY    {REDACTED}    
";

$lake_size_sql = "
    SELECT      
                CONVERT(VARCHAR,SUM({REDACTED})*8/1024) AS 'mb_size'
    FROM        {REDACTED}   
    JOIN        {REDACTED}  
    ON          {REDACTED} = {REDACTED}
    WHERE {REDACTED}  = '{REDACTED}'
    GROUP BY    {REDACTED}  
    ORDER BY    {REDACTED}   
";

$error_sql = "
    SELECT {REDACTED} 'job_name', {REDACTED}, CONVERT(date, cast(date as varchar)) 'date'
    FROM {REDACTED} 
    JOIN [{REDACTED}].[{REDACTED}].[{REDACTED}] ON [{REDACTED}].{REDACTED} = {REDACTED} 
    WHERE CONVERT(date, cast(date as varchar)) >= cast(dateadd(day, -7, getdate()) as date)
	group by {REDACTED}, {REDACTED}, {REDACTED}
    ";

$avg_write_read = sqlsrv_fetch_object(sqlsrv_query($connection, $avg_sql));
$warehouse_database_size = sqlsrv_fetch_object(sqlsrv_query($connection, $warehouse_size_sql));
$lake_database_size = sqlsrv_fetch_object(sqlsrv_query($connection, $lake_size_sql));

$database_errors = sqlsrv_query($connection, $error_sql);
$chart_sql_return = sqlsrv_query($connection, $usage_chart_sql);

$chart_data = array();
$error_data = array();

while($result = sqlsrv_fetch_object($chart_sql_return))
{
    array_push($chart_data, $result);
}

while($result = sqlsrv_fetch_object($database_errors))
{
    array_push($error_data, $result);
}

?>

<!DOCTYPE html>
<html lang="en">

    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fleetmaster Express Dashboard Home</title>
    
    <link href="./vendor/bootstrap/css/bootstrap.min.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
    <link href="./vendor/open-iconic/font/css/open-iconic.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/sidebar.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
    <link href="./css/global.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">
    <link href="dash_home/css/home.css<?php echo $utility -> MakeUrlVersion() ?>" rel="stylesheet">

    <script src="./vendor/plotly-latest.min.js<?php echo $utility -> MakeUrlVersion() ?>"></script>

    <style>
        .card{
            box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
        }
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
            <b><h2 style="text-align: center;">Fleetmaster Warehouse Administration</h2></b>
            <br>
            <div class="row justify-content-center">
                <div style="min-width: 475px;" class="col-md-6">
                    <div class="card text-white bg-dark">
                        <div class="card-header">
                            <h5 class="card-title"><span class="oi" data-glyph="loop" title="icon name" aria-hidden="true"></span> Usage Statistic</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card-body">
                                    <h3 class="card-title"><span class="oi" data-glyph="transfer" title="icon name" aria-hidden="true"></span>  Read/Write Averages</h3>
                                    <h5><b>Write: </b> <?php echo number_format($avg_write_read -> avg_mb_written) . 'MB'; ?> </h5>
                                    <h5><b>Read: </b> <?php echo number_format($avg_write_read -> avg_mb_read) . 'MB'; ?> </h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card-body">
                                    <h3 class="card-title"><span class="oi" data-glyph="hard-drive" title="icon name" aria-hidden="true"></span>Disk Usage</h3>
                                    <h5><b>Data Warehouse:</b>  <?php echo number_format($warehouse_database_size -> mb_size) . 'MB'; ?> </h5>
                                    <h5><b>Data Lake:</b>  <?php echo number_format($lake_database_size -> mb_size) . 'MB'; ?> </h5>
                                    <h5><b>Total:</b>  <?php echo number_format($warehouse_database_size -> mb_size + $lake_database_size -> mb_size) . 'MB'; ?> </h5>
                                </div>
                            </div>
                        </div>
                        <div id='myDiv'><!-- Plotly chart will be drawn inside this DIV --></div>

                    </div>
                </div>              
                <div style="min-width: 475px;" class="col-md-6">
                <div class="card text-white bg-dark">
                        <div class="card-header">
                            <h5 class="card-title"> <span class="oi" data-glyph="heart" title="icon name" aria-hidden="true"></span> Warehouse Health</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body" style="overflow: auto; max-height: 480px">
                                    <h5 class="card-title">Jobs ending in error in the last 7 days</h5>
                                    <table class="table table-dark">
                                        <thead>
                                            <tr>
                                            <th scope="col">Job Name</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        foreach($error_data as $error)
                                        {

                                            echo '<tr>
                                                    <th scope="row">' . $error -> job_name . '</th>
                                                    <td>' . $error -> description .'</td>
                                                    <td>' . $error -> date ->format('Y-m-d') .'</td>
                                                </tr>
                                                ';
                                        }
                                        ?>
                                        </tbody>
                                        </table>
                                
                                </div>
                            </div>
                            
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

    <script>
        document.getElementById("admin").classList.add("active-option"); //Add the active option to the current page

        var chartData = <?php echo json_encode($chart_data); ?> ;
        console.log(chartData);
        
        var date = []
        var written = []
        var read = []

        chartData.forEach(element => date.push(element.date['date'].slice(0,10)));
        chartData.forEach(element => written.push(element.avg_mb_written));
        chartData.forEach(element => read.push(element.avg_mb_read));

        var trace1 = {
        x: date,
        y: read,
        type: 'scatter',
        name:'Read',
        marker: {
        color: '#007bff',
        size: 8
        },
        line: {
            color: '#007bff',
            width: 1
        }
        };

        var trace2 = {
        x: date,
        y: written,
        type: 'scatter',
        name: 'Write',
        marker: {
        color: '#28a745',
        size: 8
        },
        line: {
            color: '#28a745',
            width: 1
        }
        };
        var layout = {
        title: '<b>7 Day Read/Write MB</b>',
        height: 300,
        plot_bgcolor: "#343a40",
            paper_bgcolor: "#343a40",
            xaxis: {
                type:'category',
                automargin: true,
            },
            yaxis: {
                tickprefix:'',
                automargin: true,

            }, 
            margin: { t: 70, b: 10, l: 30, r: 30 },
            font: {
                color: 'White'
            }
        };

        var data = [trace1, trace2];

        Plotly.newPlot('myDiv', data, layout, {responsive: true});
    </script>
    </body>
</html>