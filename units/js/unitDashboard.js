var loadedGraphs = 0;
// Graph watch looks for all graphs to be loaded before displaying them on the page. Only used when dashboard first loads
Object.defineProperty(this, 'graphwatch', {
  get: function () { return loadedGraphs; },
  set: function (v) {
    loadedGraphs = v;
    if(loadedGraphs === 7){
      var div = document.getElementsByClassName("row"); //divsToHide is an array
      var loader = document.getElementById("loader");
      loader.style.display = "none";
      for(var i = 0; i < div.length; i++){
        div[i].style.display = "";
        graphwatch = 0;
      }
      util.fakeResize();

    }
    else{
      
    }
  }
});

graphwatch = 0;

/*
Main routine called during load of this javascript file.
*/
start_up();

//The startup function for the dashboard
function start_up(){
  sort.GlobalSortDefault();

  changeDashView("tractors");
  load_units();
  date_initialize();
  pressButtons();
  sliceButtonHandler();
  timeButtonHandler();
  compButtonHandler();
  statButtonHandler();
  enterEventHandler();
}

//Populates Date cards on startup

function date_initialize() {
  var sortOptions = sort.LocalStorageSortOptions();

  var d = new Date(); // today!
  d.setDate(d.getDate() - 370);
  var yearWeek = util.getWeekNumber(d);

  var weekStartDate = util.getWeekDateRange(yearWeek[0], yearWeek[1]).startDate;

  sortOptions.startDate = weekStartDate;
  sortOptions.endDate = util.formatDate(new Date());
  local_storage.SetLocalStorage("sort-options", sortOptions);

  var displayStart = document.getElementById("disp-start-date");
  var displayEnd = document.getElementById("disp-end-date");

  displayStart.innerText = sortOptions.startDate;
  displayEnd.innerText = sortOptions.endDate;
}

//Downloads the current transaction list
//into excel format.
function getUnitsDownload(){
  var sortString = sort.LocalStorageSortOptions();
  var timestamp = (new Date()).toISOString().slice(0, 19).replace(/-/g, "/").replace("T", " ")
  let filename = "UnitReport_" + timestamp + ".xlsx";
  let xmlHttpRequest = new XMLHttpRequest();

  xmlHttpRequest.onreadystatechange = function() {
    var a;
    
    if (xmlHttpRequest.readyState === 4 && xmlHttpRequest.status === 200) {
        
      var loader = document.getElementById("unit-download-loader");
      var button = document.getElementById("download-tran-btn");
      button.innerHTML = '<span class="oi" data-glyph="cloud-download"></span> Export to Excel'
      button.disabled = false;
      loader.style.display = 'none';

      a = document.createElement('a');
      a.href = window.URL.createObjectURL(xmlHttpRequest.response);
      a.download = filename;
      a.style.display = 'none';
      document.body.appendChild(a);
      a.click();
    }
  }

  var loader = document.getElementById("unit-download-loader");
  var button = document.getElementById("download-tran-btn");
  
  button.innerHTML = '<span class="oi" data-glyph="circle-x"></span> Processing...'
  button.disabled = true;
  loader.style.display = 'block';

  xmlHttpRequest.open("POST", "/kpigraphs/units/api/unit_trade_download", true);
  xmlHttpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttpRequest.responseType = 'blob';
  xmlHttpRequest.send("sort=" + JSON.stringify(sortString));
}

// Unit reload function 

function load_units() {
  var sortString = sort.getGlobalSortString();
  sort.setDateRange();
  console.log(sortString);

  buildDatatable(sortString);

  createGraph("/kpigraphs/units/api/unit_status", sortString, units_stacked_bar_graph_callback, "unit-status", "<b>Unit Status by Week</b>");
  createGraph("/kpigraphs/units/api/unit_ecm_mpg", sortString, units_line_callback, "ecm-mpg", "<b>ECM MPG by Week</b>");
  createGraph("/kpigraphs/units/api/unit_actual_mpg", sortString, units_line_callback, "actual-mpg", "<b>Actual MPG by Week</b>");
  createGraph("/kpigraphs/units/api/unit_expert_fuel", sortString, units_line_callback, "expert-fuel", "<b>Expert Fuel Compliance % by Week</b>");
  createGraph("/kpigraphs/units/api/unit_idle", sortString, units_line_callback, "idle-percent", "<b>Idle Percent by Week</b>");
  createGraph("/kpigraphs/units/api/unit_repair_symptom", sortString, units_pie_graph_callback, "unit-repair-pie", "<b>Top 10 Repair Costs</b>");
  createGraph("/kpiGraphs/units/api/unit_repair_cost", sortString, units_bar_graph_callback, "unit-repair-bar", "<b>Repair Order Parts & Labor Cost by Week</b>");
}

// this function retrieves and displays the unit details for the unit number typed into the "search" text box

function unit_detail_get(callback) {
  var unitNum = document.getElementById("search");
  var loading = document.getElementById("lookupLoader");
  var reveal = document.getElementsByClassName("search_divs");

  for (var i = 0; i < reveal.length; i++) {
    if (reveal[i].style.display === "block") {
      reveal[i].style.display = "none";
    } 
  }

  if (loading.style.display === "none") {
    loading.style.display = "block";
  }

  var xmlHttp = new XMLHttpRequest();

  xmlHttp.onreadystatechange = function() { 
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      var found;
      found = callback(JSON.parse(xmlHttp.responseText));
    }

    if (found) {
      loading.style.display = "none";
      for (var i = 0; i < reveal.length; i++) {
        if (reveal[i].style.display === "none") {
          reveal[i].style.display = "block";
        } 
      }
    }

    if (unitNum.value == "" || unitNum.value == null) {
      util.toastLaunch("lookupToast", "rgb(223,27,22)", "No unit number entered.");
      clearUpdate();
    }

    else if (found == "No information found for that unit number.") {
      util.toastLaunch("lookupToast", "rgb(223,27,22)", "Unit " + unitNum.value + " not located.");
      clearUpdate();
    }
  
    else {
      util.toastLaunch("lookupToast", "#ffc107", "Unit " + unitNum.value + " located.");
      populateUpdate(JSON.parse(xmlHttp.responseText));
    }
  }

  xmlHttp.open("POST", "/kpigraphs/units/api/unit_detail_get", true); // true for asynchronous 
  xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xmlHttp.send("unitNum=" + JSON.stringify(unitNum.value));
}

// clears the inputs for the unit update form

function clearUpdate() {
  document.getElementById("unit_number").value = "";
  document.getElementById("lnd").value = "";

  var depr = document.getElementsByName("depr");
  for (var i = 0; i < depr.length; i++) {
    depr[i].checked = false;
  }

  document.getElementById("proj").value = "";
  document.getElementById("acq_pr").value = "";
  document.getElementById("acq_d").value = "";
  document.getElementById("loan_no").value = "";
  document.getElementById("intr").value = "";
  document.getElementById("loan_t").value = "";
  document.getElementById("sell_pr").value = "";
  document.getElementById("trade").value = "";
}

// use results of detail_get to populate update form 

function populateUpdate(response) {
  if (response.unnum !== null) {
    document.getElementById("unit_number").value = response.unnum;
  }
  else {
    document.getElementById("unit_number").value = "";
  }

  if (response.lender !== null) {
    document.getElementById("lnd").value = response.lender;
  }
  else {
    document.getElementById("lnd").value = "";
  }

  var depr = document.getElementsByName("depr");
  if (response.depreciate_per_mile == 'Y') {
    depr[0].checked = true;
  }
  else if (response.depreciate_per_mile == 'N') {
    depr[1].checked = true;
  }
  else {
    depr[0].checked = false;
    depr[1].checked = false;
  }
  
  if (response.projected_trade_date !== null) {
    document.getElementById("proj").value = response.projected_trade_date.date.substring(0, 10);
  }
  else {
    document.getElementById("proj").value = "";
  }

  if (response.acquisition_price !== null) {
    document.getElementById("acq_pr").value = response.acquisition_price;
  }
  else {
    document.getElementById("acq_pr").value = "";
  }

  if (response.acquisition_date !== null) {
    document.getElementById("acq_d").value = response.acquisition_date.date.substring(0, 10);
  }
  else {
    document.getElementById("acq_d").value = "";
  }

  if (response.loan_no !== null) {
    document.getElementById("loan_no").value = response.loan_no;
  }
  else {
    document.getElementById("loan_no").value = "";
  }

  if (response.interest_rate !== null) {
    document.getElementById("intr").value = response.interest_rate;
  }
  else {
    document.getElementById("intr").value = "";
  }

  if (response.term_of_loan !== null) {
    document.getElementById("loan_t").value = response.term_of_loan;
  }
  else {
    document.getElementById("loan_t").value = "";
  }

  if (response.sell_price !== null) {
    document.getElementById("sell_pr").value = response.sell_price;
  }
  else {
    document.getElementById("sell_pr").value = "";
  }

  if (response.trade_type !== null) {
    document.getElementById("trade").value = response.trade_type;
  }
  else {
    document.getElementById("trade").value = "";
  }
}

// same as unit_detail_get, but doesn't launch the toasts and takes the unit as an argument

function justUpdated(unit) {
  var loading = document.getElementById("lookupLoader");
  var reveal = document.getElementsByClassName("search_divs");

  for (var i = 0; i < reveal.length; i++) {
    if (reveal[i].style.display === "block") {
      reveal[i].style.display = "none";
    } 
  }

  if (loading.style.display === "none") {
    loading.style.display = "block";
  }

  var xmlHttp = new XMLHttpRequest();

  xmlHttp.onreadystatechange = function() { 
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      var found;
      found = units_detail_callback(JSON.parse(xmlHttp.responseText));

      if (found) {
        loading.style.display = "none";
        for (var i = 0; i < reveal.length; i++) {
          if (reveal[i].style.display === "none") {
            reveal[i].style.display = "block";
          } 
        }
      }
    }
  }

  xmlHttp.open("POST", "/kpigraphs/units/api/unit_detail_get", true); // true for asynchronous 
  xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xmlHttp.send("unitNum=" + JSON.stringify(unit));
}

// this function collects all the data from the update form and tries to update the unit master table

function unit_detail_update() {

  if (document.getElementById("unit_number").value == "") {
    util.toastLaunch("updateToast", "rgb(223,27,22)", "Unit number is required.");
  }

  else {
    var depreciate_per_mile = "";
    var unnum = document.getElementById("unit_number").value;
    var lender = document.getElementById("lnd").value;

    var depr = document.getElementsByName("depr");
    for (var i = 0; i < depr.length; i++) {
      if (depr[i].checked == true) {
        depreciate_per_mile = depr[i].value;
      } 
    }

    var projected_trade_date = document.getElementById("proj").value;
    var acquisition_price = document.getElementById("acq_pr").value;
    var acquisition_date = document.getElementById("acq_d").value;
    var loan_no = document.getElementById("loan_no").value;
    var interest_rate = document.getElementById("intr").value;
    var term_of_loan = document.getElementById("loan_t").value;
    var sell_price = document.getElementById("sell_pr").value;
    var trade_type = document.getElementById("trade").value;

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "/kpigraphs/units/api/unit_detail_update", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xmlHttp.send("unnum=" + unnum + "&lnd=" + lender + "&depr=" + depreciate_per_mile + "&projected_trade_date=" + projected_trade_date
    + "&aquisition_price=" + acquisition_price + "&acquisition_date=" + acquisition_date + "&loan_num=" + loan_no + "&interest_rate="
    + interest_rate + "&loan_term=" + term_of_loan + "&sell_price=" + sell_price + "&trade_type=" + trade_type);

    xmlHttp.onreadystatechange = function() { 
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        if (JSON.parse(xmlHttp.responseText) == "worked") {
          util.toastLaunch("updateToast", "#007bff", "Unit " + unnum + " updated successfully.");
          clearUpdate();
          justUpdated(unnum);
        }

        else if (JSON.parse(xmlHttp.responseText) == "error" ) {
          util.toastLaunch("updateToast", "rgb(223,27,22)", "An error occurred. Check your inputs and try again.");
        }

        else if (JSON.parse(xmlHttp.responseText) == "no such unit") {
          util.toastLaunch("updateToast", "#ffc107", "Unit " + unnum + " not found.");
        }
      }
    }
  }
}

function buildDatatable(sort) {
  var table = $('#tradeTable').DataTable( {
    "scrollY": 500,
    "scrollX": true,
    "deferRender": true,
    "bDestroy": true,
    "pageLength": -1,
    "lengthMenu": [[-1, 10, 50, 100], ["All", 10, 50, 100]],
    "order": [[4, "desc"]],

    "ajax": {
      "url": "/kpiGraphs/units/api/unit_trade",
      "data": {"sort": sort},      //where the API junk goes
      "dataSrc": "",
      "type": "POST",
      "filter": function(data){
        var json = jQuery.parseJSON( data );
        json.data = json.list;
        return JSON.stringify( json ); // return JSON string
      }
    },

    "columns": [
      {"data": "unnum"},
      {"data": "unyear"},
      {"data": "unmake"},
      {"data": "serial_number"},
      {"data": "current_odom",
      "render": $.fn.dataTable.render.number(',', '.', 2)},
      {"data": "odom_timestamp"},
      {"data": "last_bus_week",
      "render": $.fn.dataTable.render.number(',', '.', 2)},
      {"data": "average_4_week_miles",
      "render": $.fn.dataTable.render.number(',', '.', 2)},
      {"data": "average_lifetime_miles",
      "render": $.fn.dataTable.render.number(',', '.', 2)},
      {"data": "projected_trade_date"},
      {"data": null}
    ],

    "rowCallback": function(row, data) {
      if (data.status == 3) {
        $('td:eq(10)', row).html('<div class="redCircle"></div>');
      }

      else if (data.status == 1) {
        $('td:eq(10)', row).html('<div class="greenCircle"></div>');
      }

      else if (data.status == 2) {
        $('td:eq(10)', row).html('<div class="yellowCircle"></div>');
      }

      if (data.division == 990) {
        $('td:eq(0)', row).html(data.unnum + '<span class="oi float-right" data-glyph="dollar"></span>');
      }
    },

    select: {
      style: 'single',
      blurable: true
    },

    "language": {
      "info": "Showing _START_ to _END_ of _TOTAL_ units",
      "infoFiltered": "",
      "infoEmpty": "No units found for this sort."
    }
  });

  table.off('select');

  table.on( 'select', function ( e, dt, type, index) {
    unit = table.row(index).data().unnum;
    console.log(unit);
    goDetail(unit);
  });

  table.on( 'page.dt', function () {
    $('.dataTables_scrollBody').animate({
      scrollTop: 0
    }, 1);
  });
}

function goDetail(unit) {
  var forms = document.getElementById('formClick');
  var search = document.getElementById('search');

  search.value = unit;
  justUpdated(unit);
  forms.click();
}

function enterEventHandler() {
  var search = document.getElementById("search");
  var update = document.getElementById("trade_type");
  
  search.addEventListener("keydown", function(event) {
    if (event.keyCode === 13) {
      event.preventDefault();
      document.getElementById("search_btn").click();
    }
  });

  update.addEventListener("keydown", function(event) {
    if (event.keyCode === 13) {
      event.preventDefault();
      document.getElementById("update_btn").click();
    }
  });
}

/*
This function changes the view of the dashboard
Params:
@elementID - the element to display
*/
function changeDashView(elementID) 
{
  if(elementID == 'tractors')
  {
    breadcrumbs.init();
    breadcrumbs.addBreadCrumbs("Units Dashboard", 'http://localhost/kpiGraphs/unitDashboard', false);
    breadcrumbs.addBreadCrumbs("Unit Graphs", 'http://localhost/kpiGraphs/unitDashboard', true);
  }

  else if(elementID == 'tractor_forms')
  {
    breadcrumbs.init();
    breadcrumbs.addBreadCrumbs("Units Dashboard", 'http://localhost/kpiGraphs/unitDashboard', false);
    breadcrumbs.addBreadCrumbs("Unit Lookup", 'http://localhost/kpiGraphs/unitDashboard', true);
  }

  else if(elementID == 'tractors_trade')
  {
    breadcrumbs.init();
    breadcrumbs.addBreadCrumbs("Units Dashboard", 'http://localhost/kpiGraphs/unitDashboard', false);
    breadcrumbs.addBreadCrumbs("Unit Trade", 'http://localhost/kpiGraphs/unitDashboard', true);
  }

  var divsToHide = document.getElementsByClassName("graph-grids"); //divsToHide is an array
  for(var i = 0; i < divsToHide.length; i++){
    if(divsToHide[i].id != elementID)
    {
      divsToHide[i].style.display = "none"; // depending on what you're doing
    }
  }
  
  var x = document.getElementById(elementID);
  if (x.style.display === "none") {
    x.style.display = "block";
    util.fakeResize();
  } 
}

/*
This function "presses" the buttons of the default sort
*/

function pressButtons() {
  local_storage.SetLocalStorage("slice-btn", "default");
  local_storage.SetLocalStorage("time-btn", "last-52-weeks");
  local_storage.SetLocalStorage("comp-buttons", "BTH");
  local_storage.SetLocalStorage("stat-buttons", "stat-all");
}

function unPressButtons() {
  local_storage.SetLocalStorage("slice-btn", "");
  local_storage.SetLocalStorage("time-btn", "");
  local_storage.SetLocalStorage("comp-buttons", "");
  local_storage.SetLocalStorage("stat-buttons", "");
}

// in the case of pressing a button that would change the contents of the units datatable, so the user doesn't get confused

function unPressTrade() {
  local_storage.SetLocalStorage("comp-buttons", "");
  local_storage.SetLocalStorage("stat-buttons", "");
}

// in the case of pressing buttons on trade datatable that change contents of unit dash

function unPressDash() {
  local_storage.SetLocalStorage("slice-btn", "");
  local_storage.SetLocalStorage("time-btn", "");
}

/*
These functions handle the sorting buttons at the top of the dashboard
*/

function sliceButtonHandler() {
  var flmrButtons = document.getElementsByClassName("company-slice");
  var flmrPressed = local_storage.GetLocalStorage("slice-btn");

  for (var i = 0; i < flmrButtons.length; i++) {
    if (flmrButtons[i].id == flmrPressed) {
      flmrButtons[i].style.background = 'darkgrey';
    }
    else if (flmrButtons[i].id != flmrPressed) {
      flmrButtons[i].style.background = 'black';
    }
  }
}

function timeButtonHandler() {
  var timeButtons = document.getElementsByClassName("time-elapsed");
  var timePressed = local_storage.GetLocalStorage("time-btn");

  for (var i = 0; i < timeButtons.length; i++) {
    if (timeButtons[i].id == timePressed) {
      timeButtons[i].style.background = 'darkgrey';
    }
    else if (timeButtons[i].id != timePressed) {
      timeButtons[i].style.background = 'black';
    }
  }
}

function compButtonHandler() {
  var compButtons = document.getElementsByClassName("comp-button");
  var compPressed = local_storage.GetLocalStorage("comp-buttons");

  for (var i = 0; i < compButtons.length; i++) {
    if (compButtons[i].id == compPressed) {
      compButtons[i].style.background = 'darkgrey';
    }
    else if (compButtons[i].id != compPressed) {
      compButtons[i].style.background = 'black';
    }
  }
}

function statButtonHandler() {
  var statButtons = document.getElementsByClassName("stat-button");
  var statPressed = local_storage.GetLocalStorage("stat-buttons");

  for (var i = 0; i < statButtons.length; i++) {
    if (statButtons[i].id == statPressed) {
      statButtons[i].style.background = 'darkgrey';
    }
    else if (statButtons[i].id != statPressed) {
      statButtons[i].style.background = 'black';
    }
  }
}

/*
This function takes creates a graph using callback functions
Params:
@theUrl - The API URL you wish to call
@sort - The current sort object for this dashboard (sort.getGlobalSortString())
@callback - the call back function that builds the graph
@divID - the div to create the graph in
@title - title of the graph
*/

function createGraph(theUrl, sort, callback, divID, title){
    var xmlHttp = new XMLHttpRequest();

    xmlHttp.onreadystatechange = function() { 
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
      callback(JSON.parse(xmlHttp.responseText), divID, title);
    }
    
    xmlHttp.open("POST", theUrl, true); // true for asynchronous 
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.send("sort=" + sort);
}

// function UnitGraphClickEvent(points){

//   var header = document.getElementById("unit-week-detail-modal");
//   var sortOptions = sort.LocalStorageSortOptions();
//   var year = points.x.split('-')[0];
//   var week = points.x.split('-')[1];
//   var dataRange = util.getWeekDateRange(year, week)
  
//   sortOptions.startDate = dataRange.startDate;
//   sortOptions.endDate = dataRange.endDate;

//   header.innerText = "Week " + week + " of " + year;

//   var sortString = sort.getGlobalSortString(sortOptions);

//   $('#unit-modal-fullscreen').modal('show');
//   util.fakeResize();

// }
 







