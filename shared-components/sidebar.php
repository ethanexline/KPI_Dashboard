<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/OAuthSSO/Client/OAuthClient.php"); //Add the OAuthClient from the SSO system.

$OAuth = new OAuth();

$is_admin = $OAuth -> check_group_membership('IT');

?>

<div class="navbar-dark bg-dark border-right" id="sidebar-wrapper">
  <div class="sidebar-heading">Fleetmaster Express </div>
  <div class="list-group list-group-flush"> 
    <a id="home" href="./home" class="list-group-item list-group-item-action bg-dark text-white"><span class="oi" data-glyph="home"></span> Home</a>
    <a id="orderDashboard" href="./orderDashboard" class="list-group-item list-group-item-action bg-dark text-white"><span class="oi" data-glyph="dashboard"></span> Orders Dashboard</a>
    <a id="unitDashboard" href="./unitDashboard" class="list-group-item list-group-item-action bg-dark text-white"><span class="oi" data-glyph="dashboard"></span> Units Dashboard</a>
    <a id="fuelDashboard" href="./fuelDashboard" class="list-group-item list-group-item-action bg-dark text-white"><span class="oi" data-glyph="dashboard"></span> Fuel Dashboard</a>
    <a id="salesDashboard" href="./salesDashboard" class="list-group-item list-group-item-action bg-dark text-white"><span class="oi" data-glyph="dashboard"></span> Sales Dashboard</a>
    <a id="operationsKPI" href="./operationsKPI" class="list-group-item list-group-item-action bg-dark text-white"><span class="oi" data-glyph="graph"></span> Operations Center KPI Graphs</a>
    <?php
      if($is_admin == True)
      {
        echo '<a id="admin" href="./admin" class="list-group-item list-group-item-action bg-dark text-white"><span class="oi" data-glyph="key"></span> Administration</a>';
      }
    ?>
  </div>
  <br>
  <div class='sidebar-bottom'>
    
  </div>
</div>

