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
  changeDashView("order_revenue");
  load_order_revenue();
  date_initialize();
  pressButtons();
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

//Order Revenue reload function
function load_order_revenue(){
  var sortString = sort.getGlobalSortString();
  sort.setDateRange();
  console.log(sortString);

  createGraph("/kpigraphs/orders/api/orders_count",  sortString, order_bar_graph_callback, "order-count", "<b>Order Count by Week</b>");
  createGraph("/kpiGraphs/orders/api/weekly_revenue",  sortString, order_bar_graph_callback, "trucking-revenue", "<b>Revenue by Week</b>");
  createGraph("/kpiGraphs/orders/api/rpm",  sortString, order_indicator_data_card, "rate-per-mile", "<b>Rate Per Mile</b>");
  createGraph("/kpiGraphs/orders/api/loaded_miles",  sortString, order_line_callback, "hourly-revenue-line", "<b>Loaded Miles by Week</b>");
  createGraph("/kpigraphs/orders/api/state_revenue",  sortString, order_state_map_callback, "state-revenue", "<b>Revenue by State</b>");
  createGraph("/kpigraphs/orders/api/customer_revenue",  sortString, order_cust_pie_graph_callback, "customer-revenue", "<b>Top 10 Customer Revenue by Week</b>");
  createGraph("/kpigraphs/orders/api/commodity_revenue",  sortString, order_pie_graph_callback, "commodity-revenue", "<b>Top 10 Commodity by Revenue</b>");
  createGraph("/kpigraphs/orders/api/supplemental_revenue",  sortString, order_h_bar_graph_callback, "supplemental-revenue", "<b>Supplemental Revenue by Week</b>");
}

/*
This function changes the view of the dashboard
Params:
@elementID - the element to display
*/
function changeDashView(elementID) 
{
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
This function loads the data only for charts currently being displayed
Params:
@elementID - the element to display
*/

function reloadHandler() 
{
  var divsToReload = document.getElementsByClassName("graph-grids"); //divsToReload is an array
  for(var i = 0; i < divsToReload.length; i++) {
    if(divsToReload[i].id == "order_revenue" && divsToReload[i].style.display != "none")
    {
      load_order_revenue();
    }
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
      flmrButtons[i].style.background = 'rgb(0,0,0)';
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
      timeButtons[i].style.background = 'rgb(0,0,0)';
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
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
      callback(JSON.parse(xmlHttp.responseText), divID, title);
    }

  xmlHttp.open("POST", theUrl, true); // true for asynchronous 
  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttp.send("sort=" + sort);
}

function OrderGraphClickEvent(points){

  var header = document.getElementById("order-week-detail-modal");
  var sortOptions = sort.LocalStorageSortOptions();
  var year = points.x.split('-')[0];
  var week = points.x.split('-')[1];
  var dataRange = util.getWeekDateRange(year, week)
  
  sortOptions.startDate = dataRange.startDate;
  sortOptions.endDate = dataRange.endDate;

  header.innerText = "Week " + week + " of " + year;

  var sortString = sort.getGlobalSortString(sortOptions);

  createGraph("/kpiGraphs/orders/api/rpm", sortString, order_indicator_data_card, "detail-rate-per-mile", "<b>Rate Per Mile</b>");
  createGraph("/kpigraphs/orders/api/state_revenue", sortString, order_state_map_callback, "detail-state-revenue", "<b>Revenue by State</b>");
  createGraph("/kpigraphs/orders/api/commodity_revenue", sortString, order_pie_graph_callback, "detail-commodity-revenue", "<b>Top 10 Commodity by Revenue</b>");
  createGraph("/kpigraphs/orders/api/customer_revenue", sortString, order_cust_pie_graph_callback, "detail-customer-revenue", "<b>Top 10 Customer Revenue</b>");
  createGraph("/kpigraphs/orders/api/daily_revenue", sortString, order_line_callback, "daily-revenue", "<b>Daily Revenue</b>");

  $('#order-modal-fullscreen').modal('show');

  util.fakeResize();
}
 