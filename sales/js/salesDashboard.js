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
  load_sales();
  changeDashView("sales_charts");
  date_initialize();
  sliceButtonHandler();
  timeButtonHandler();
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

// Unit reload function 

function load_sales() {
  var sortString = sort.getGlobalSortString();
  sort.setDateRange();
  console.log(sortString);

  createGraph("/kpigraphs/sales/api/state_revenue_orig_dest",  sortString, sales_bar_graph_callback, "origin-destination-rev", "<b>Revenue by Origin to Destination <br> State (Top 25 Revenue)</b>");
  createGraph("/kpigraphs/sales/api/state_broker_orig_dest",  sortString, sales_bar_graph_broker_callback, "origin-destination-broker-rev", "<b>Revenue by Origin to Destination <br> State (Top 25 Broker Revenue)</b>");
  createGraph("/kpigraphs/sales/api/state_rpm_orig_dest",  sortString, sales_bar_graph_rpm_callback, "rate-per-mile", "<b>RPM by Origin to Destination <br> State (Top 25 Revenue)</b>");
  createGraph("/kpigraphs/sales/api/customer_revenue",  sortString, sales_pie_graph_callback, "customer-revenue-broker", "<b>Commodity \"BROKER\" Revenue by <br> Customer (Top 10 Revenue)</b>");
  createGraph("/kpigraphs/sales/api/broker_revenue",  sortString, sales_line_graph_callback, "broker-revenue", "<b>Commodity Code BROKER <br> vs All Others Revenue by Week</b>");
  createGraph("/kpigraphs/sales/api/rpm_by_week",  sortString, sales_line_graph_single_callback, "rpm-by-week", "<b>RPM By Week</b>");
  createGraph("/kpigraphs/sales/api/top_5_rev",  sortString, sales_line_graph_callback, "top5-revenue", "<b>Top 5 Customer Revenue by Week</b>");
}

/*
This function changes the view of the dashboard
Params:
@elementID - the element to display
*/
function changeDashView(elementID) 
{
  breadcrumbs.init();
  breadcrumbs.addBreadCrumbs("Sales Dashboard", 'http://localhost/kpiGraphs/salesDashboard', true);

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
}

function unPressButtons() {
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

/*
This function loads the data only for charts currently being displayed
Params:
@elementID - the element to display
*/

function reloadHandler() 
{
  var divsToReload = document.getElementsByClassName("graph-grids"); //divsToReload is an array
  for(var i = 0; i < divsToReload.length; i++){
    if (divsToReload[i].id == "sales_charts" && divsToReload[i].style.display != "none")
    {
      load_sales();
    }
  }
}

/*
This function takes creates a graph using callback functions
Params:
@theUrl - The API URL you wish to call
@callback - the call back function that builds the graph
@divID - the div to create the graph in.
*/

function createGraph(theUrl, sort, callback, divID, title){
    var xmlHttp = new XMLHttpRequest();

    xmlHttp.onreadystatechange = function() { 
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        callback(JSON.parse(xmlHttp.responseText), divID, title);
      }
    }

    xmlHttp.open("POST", theUrl, true); // true for asynchronous 
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.send("sort=" + sort);
}