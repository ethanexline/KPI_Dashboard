var loadedGraphs = 7;
// Graph watch looks for all graphs to be loaded before displaying them on the page. Only used when dashboard first loads
Object.defineProperty(this, 'graphwatch', {
  get: function () { return loadedGraphs; },
  set: function (v) {
    loadedGraphs = v;
    if(loadedGraphs === 7){
      var div = document.getElementsByClassName("graph-container"); //divsToHide is an array
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

graphwatch = 7;

/*
Main routine called during load of this javascript file.
*/
start_up();

//The startup function for the dashboard
function start_up(){

  var d = new Date();
  sort.GlobalSortDefaultFuel();
  changeDashView("inquiry");
  load_fuel();
  date_initialize();
  buildDatatable();
  // enterEventHandler();
  summaryCharts(d.getFullYear());
  var fuel_loader = document.getElementById('fuel-download-loader');
  fuel_loader.style.display = "none";
}

//Downloads the sort text file for use in debugging and support tickets
function downloadSort()
{
  var sorting = sort.getGlobalSortStringFuel();
  var myblob = new Blob([sorting], {
    type: 'text/plain'
  });
  
  a = document.createElement('a');
  a.href = window.URL.createObjectURL(myblob);
  a.download = 'sort.txt';
  a.style.display = 'none';
  document.body.appendChild(a);
  a.click();
}


//Downloads the current transaction list
//into excel format.
function getTransactionsDownload(){
  var sortString = sort.loadSortFromStorageFuel();
  var timestamp = (new Date()).toISOString().slice(0, 19).replace(/-/g, "/").replace("T", " ")
  let filename = "FuelReport" + timestamp + ".xlsx";
  let xmlHttpRequest = new XMLHttpRequest();

  xmlHttpRequest.onreadystatechange = function() {
      var a;
     
      if (xmlHttpRequest.readyState === 4 && xmlHttpRequest.status === 200) {
          
          var loader = document.getElementById("fuel-download-loader");
          var button = document.getElementById("download-tran-btn");
          button.innerHTML = '<span class="oi" data-glyph="cloud-download"></span> Download Transactions'
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

  var loader = document.getElementById("fuel-download-loader");
  var button = document.getElementById("download-tran-btn");
  
  button.innerHTML = '<span class="oi" data-glyph="circle-x"></span> Processing...'
  button.disabled = true;
  loader.style.display = 'block';

  xmlHttpRequest.open("POST", "/kpigraphs/fuel/api/transaction_download", true);
  xmlHttpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttpRequest.responseType = 'blob';
  xmlHttpRequest.send("sort=" + JSON.stringify(sortString));
}

function buildDatatable() {
  var sortOptions = sort.loadSortFromStorageFuel();
  console.log(sortOptions);
  var table = $('#table').DataTable( {
    "scrollY": 500,
    "scrollX": true,
    "ordering": false,
    "searching": false,
    "processing": true,
    "serverSide": true,
    "deferRender": true,
    "pageLength": 100,
    "bDestroy": true,

    "ajax": {
      "url": "/kpiGraphs/fuel/api/transaction_list",
      "data": {"sort": JSON.stringify(sortOptions)},
      "dataSrc": "data",
      "type": "POST",
      "filter": function(data){
        var json = jQuery.parseJSON( data );
        json.recordsTotal = json.total;
        json.recordsFiltered = json.total;
        json.data = json.list;
        return JSON.stringify( json ); // return JSON string
      }
    },

    "columns": [
      {"data": "unit_number"},
      {"data": "terminal"},
      {"data": "driver_name"},
      {"data": "date"},
      {"data": "stop_name"},
      {"data": "city"},
      {"data": "state"},
      {"data": "cash_advance_flag"},
      {"data": "other_gallons"},
      {"data": "misc_cost",
      "render": $.fn.dataTable.render.number(',', '.', 2, '$')},

      {"data": "fees",
      "render": $.fn.dataTable.render.number(',', '.', 2, '$')},

      {"data": null,
      "render": function (data, type, row) {
        if ((Number(row.total_gallons) - Number(row.other_gallons)) == 0) {
          return ".00"
        }
        else {
          return (Number(row.total_gallons) - Number(row.other_gallons)).toFixed(2);
        }
      }},

      {"data": null,
      "render": function (data, type, row) {
        var tracCost = 0;
        var rfCost = 0;
        var tracPct = 0;
        var rfPct = 0;
        var gals = (Number(row.total_gallons) - Number(row.other_gallons)) + Number(row.reefer_gallons);
        if (Number(row.total_rebate_amount) > 0) {

          if (gals > 0) {
            
            if ((Number(row.total_gallons) - Number(row.other_gallons)) > 0) {
              tracPct = (Number(row.total_gallons) - Number(row.other_gallons)) / gals;
            }
      
            rfPct = 1 - tracPct;
      
            var tracCost = (Number(row.total_fuel_cost) - Number(row.other_fuel_cost)) - (Number(row.total_rebate_amount) * tracPct);
            var rfCost = Number(row.total_reefer_cost) - (Number(row.total_rebate_amount) * rfPct); 
          }
        }
        else {
          var tracCost = (Number(row.total_fuel_cost) - Number(row.other_fuel_cost));
          var rfCost = Number(row.total_reefer_cost);
        } 
        if (tracCost != 0) {
          return (Number(tracCost) / (Number(row.total_gallons) - Number(row.other_gallons))).toFixed(3);
        }
        else {
          return ".000";
        }
      }},

      {"data": null,
      "render": function (data, type, row) {
        var tracCost = 0;
        var rfCost = 0;
        var tracPct = 0;
        var rfPct = 0;
        var gals = (Number(row.total_gallons) - Number(row.other_gallons)) + Number(row.reefer_gallons);
        if (Number(row.total_rebate_amount) > 0) {

          if (gals > 0) {
            
            if ((Number(row.total_gallons) - Number(row.other_gallons)) > 0) {
              tracPct = (Number(row.total_gallons) - Number(row.other_gallons)) / gals;
            }
      
            rfPct = 1 - tracPct;
      
            var tracCost = (Number(row.total_fuel_cost) - Number(row.other_fuel_cost)) - (Number(row.total_rebate_amount) * tracPct);
            var rfCost = Number(row.total_reefer_cost) - (Number(row.total_rebate_amount) * rfPct); 
          }
        }
        else {
          var tracCost = (Number(row.total_fuel_cost) - Number(row.other_fuel_cost));
          var rfCost = Number(row.total_reefer_cost);
        }

        return "$" + Number(tracCost).toFixed(2);

      }},

      {"data": "reefer_gallons"},

      {"data": null,
      "render": function (data, type, row) {
        var tracCost = 0;
        var rfCost = 0;
        var tracPct = 0;
        var rfPct = 0;
        var gals = (Number(row.total_gallons) - Number(row.other_gallons)) + Number(row.reefer_gallons);
        if (Number(row.total_rebate_amount) > 0) {

          if (gals > 0) {
            
            if ((Number(row.total_gallons) - Number(row.other_gallons)) > 0) {
              tracPct = (Number(row.total_gallons) - Number(row.other_gallons)) / gals;
            }
      
            rfPct = 1 - tracPct;
      
            var tracCost = (Number(row.total_fuel_cost) - Number(row.other_fuel_cost)) - (Number(row.total_rebate_amount) * tracPct);
            var rfCost = Number(row.total_reefer_cost) - (Number(row.total_rebate_amount) * rfPct); 
          }
        }
        else {
          var tracCost = (Number(row.total_fuel_cost) - Number(row.other_fuel_cost));
          var rfCost = Number(row.total_reefer_cost);
        } 
        if (rfCost != 0) {
          return (Number(rfCost) / (Number(row.reefer_gallons))).toFixed(3);
        }
        else {
          return ".000";
        }
      }},

      {"data": null,
      "render": function (data, type, row) {
        var tracCost = 0;
        var rfCost = 0;
        var tracPct = 0;
        var rfPct = 0;
        var gals = (Number(row.total_gallons) - Number(row.other_gallons)) + Number(row.reefer_gallons);
        if (Number(row.total_rebate_amount) > 0) {

          if (gals > 0) {
            
            if ((Number(row.total_gallons) - Number(row.other_gallons)) > 0) {
              tracPct = (Number(row.total_gallons) - Number(row.other_gallons)) / gals;
            }
      
            rfPct = 1 - tracPct;
      
            var tracCost = (Number(row.total_fuel_cost) - Number(row.other_fuel_cost)) - (Number(row.total_rebate_amount) * tracPct);
            var rfCost = Number(row.total_reefer_cost) - (Number(row.total_rebate_amount) * rfPct); 
          }
        }
        else {
          var tracCost = (Number(row.total_fuel_cost) - Number(row.other_fuel_cost));
          var rfCost = Number(row.total_reefer_cost);
        }

        return "$" + Number(rfCost).toFixed(2);
      }}
    ],

    "rowCallback": function(row, data, index) {
      if (data.in_network == 'N') {
        $(row).css('background-color', '#ff726f');
      }
    },

    select: {
      style: 'single',
      blurable: true
    },

    "language": {
      "info": "Showing _START_ to _END_ of _TOTAL_ transactions",
      "infoFiltered": "",
      "infoEmpty": "No transactions found for this sort."
    }

  });

  table.off('select');

  table.on( 'select', function ( e, dt, type, index) {
    tran = table.row(index).data().wh_transaction_id;
    console.log(tran);
    tranDetailLaunch(tran);
  });

  table.on( 'page.dt', function () {
    $('.dataTables_scrollBody').animate({
      scrollTop: 0
    }, 1);
  });
}

//Populates Date cards on startup

function date_initialize() {
  var sortOptions = sort.LocalStorageSortOptionsFuel();

  var d = new Date(); // today!
  d.setDate(d.getDate() - 7);

  sortOptions.start_date = util.formatDate(d);
  d.setDate(d.getDate() + 6);
  sortOptions.end_date = util.formatDate(d);
  local_storage.SetLocalStorage("sort-options-fuel", sortOptions);

  var displayStart = document.getElementById("disp-start-date");
  var displayEnd = document.getElementById("disp-end-date");

  displayStart.innerText = sortOptions.start_date;
  displayEnd.innerText = sortOptions.end_date;
}

function enterEventHandler() {
  // var search = document.getElementById("search");
  // var update = document.getElementById("trade_type");
  
  // search.addEventListener("keydown", function(event) {
  //   if (event.keyCode === 13) {
  //     event.preventDefault();
  //     document.getElementById("search_btn").click();
  //   }
  // });

  // update.addEventListener("keydown", function(event) {
  //   if (event.keyCode === 13) {
  //     event.preventDefault();
  //     document.getElementById("update_btn").click();
  //   }
  // });
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
    if (divsToReload[i].id == "fuel_charts" && divsToReload[i].style.display != "none") {
      load_fuel();
    }
  }
}

/*
This function changes the view of the dashboard
Params:
@elementID - the element to display
*/

function changeDashView(elementID) 
{
  if(elementID == 'inquiry')
  {
    breadcrumbs.init();
    breadcrumbs.addBreadCrumbs("Fuel Dashboard", 'http://localhost/kpiGraphs/fuelDashboard', false);
    breadcrumbs.addBreadCrumbs("Fuel Inquiry", 'http://localhost/kpiGraphs/fuelDashboard', true);
  }
  else if(elementID == "fuel_charts")
  {
    breadcrumbs.init();
    breadcrumbs.addBreadCrumbs("Fuel Dashboard", 'http://localhost/kpiGraphs/fuelDashboard', false);
    breadcrumbs.addBreadCrumbs("Fuel Charts", 'http://localhost/kpiGraphs/fuelDashboard', true);
  }
  else 
  {
    breadcrumbs.init();
    breadcrumbs.addBreadCrumbs("Fuel Dashboard", 'http://localhost/kpiGraphs/fuelDashboard', false);
    breadcrumbs.addBreadCrumbs("Fuel Summary", 'http://localhost/kpiGraphs/fuelDashboard', true);
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
This function takes creates a graph using callback functions
Params:
@theUrl - The API URL you wish to call
@callback - the call back function that builds the graph
@divID - the div to create the graph in.
*/

function createGraph(theUrl, sort, callback, divID, title) {
  var xmlHttp = new XMLHttpRequest();

  xmlHttp.onreadystatechange = function() { 
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
      callback(JSON.parse(xmlHttp.responseText), divID, title);
  }

  xmlHttp.open("POST", theUrl, true); // true for asynchronous 
  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttp.send("sort=" + sort);
}

function load_fuel() {
  var sortString = sort.getGlobalSortStringFuel();
  console.log(sortString);

  createGraph("/kpigraphs/fuel/api/fuel_daily_cost", sortString, fuel_stacked_bar_graph_callback, "t_d_cost", "<b>Fuel Purchases by Day</b>");
  createGraph("/kpigraphs/fuel/api/ppg_by_day", sortString, fuel_bar_graph_callback, "d_ppg", "<b>Fuel PPG by Day</b>");
  createGraph("/kpigraphs/fuel/api/cost_by_state", sortString, fuel_state_chart_callback, "fc_by_state", "<b>Fuel Purchases by State</b>");
  createGraph("/kpigraphs/fuel/api/cost_by_chain", sortString, fuel_pie_graph_callback, "fc_by_chain", "<b>Fuel Purchases by Chain</b>");
  createGraph("/kpigraphs/fuel/api/cost_by_terminal", sortString, fuel_pie_graph_callback, "fc_by_term", "<b>Fuel Purchases by Terminal</b>");
  createGraph("/kpigraphs/fuel/api/cost_by_fuel_type", sortString, fuel_pie_graph_callback, "fc_by_type", "<b>Fuel Purchases by Fuel Type</b>");
}

function tranDetailLaunch(id) {
  console.log(id);
  var xmlHttp = new XMLHttpRequest();

  xmlHttp.onreadystatechange = function() { 
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      var done = false;
      var transaction = JSON.parse(xmlHttp.responseText);

      if (transaction) {
        done = setTranDetails(transaction);
      }
      if (done) {
        $('#fuel-modal-fullscreen').modal('show');
        util.fakeResize();
      }
    }
  }

  xmlHttp.open("POST", "/kpigraphs/fuel/api/transaction_details", true);
  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttp.send("id=" + id);
}

function setTranDetails(transaction) {
  var oon = document.getElementById("out-of-network");
  var account = document.getElementById("act_cd");
  var stop = document.getElementById("stop");
  var center = document.getElementById("chain_id");
  var sFlag = document.getElementById("service_flag");
  var loc = document.getElementById("location");
  var chain = document.getElementById("chain_name");
  var date = document.getElementById("date");
  var ind = document.getElementById("ind");
  var day = document.getElementById("day");
  var trn = document.getElementById("trn_num");
  var driver = document.getElementById("drv");
  var unit = document.getElementById("unitNumber");
  var term = document.getElementById("terminal");
  var tFlag = document.getElementById("tractor");
  var prod1F = document.getElementById("prod1");
  var rFlag = document.getElementById("reefer");
  var prod2F = document.getElementById("prod2");
  var oFlag = document.getElementById("oil");
  var prod3F = document.getElementById("prod3");
  var cFlag = document.getElementById("cash");
  var rebFlag = document.getElementById("rebate");
  var card = document.getElementById("card");
  var hub = document.getElementById("hub");
  var inv = document.getElementById("invoice");
  var prevHub = document.getElementById("prev_hub");
  var prod1C = document.getElementById("prod1_cost");
  var prod2C = document.getElementById("prod2_cost");
  var prod3C = document.getElementById("prod3_cost");
  var prodTot = document.getElementById("tot_prod_cost");
  var CAA = document.getElementById("amt");
  var CAF = document.getElementById("feeModal");
  var tGal = document.getElementById("trac_gals");
  var rGal = document.getElementById("rf_gals");
  var totGal = document.getElementById("tot_gals");
  var tPPG = document.getElementById("trac_ppg");
  var rPPG = document.getElementById("rf_ppg");
  var tCost = document.getElementById("trac_cost");
  var rCost = document.getElementById("rf_cost");
  var totCost = document.getElementById("tot_cost");
  var oil = document.getElementById("oil_qt");
  var defGal = document.getElementById("def_gal");
  var oilPPG = document.getElementById("oil_ppg");
  var defPPG = document.getElementById("def_ppg");
  var oilCost = document.getElementById("oil_cost");
  var defCost = document.getElementById("def_cost");
  var rebAmt = document.getElementById("rebate_amt");
  var adjPPG = document.getElementById("adj_ppg");
  var totDue = document.getElementById("grand_total");
  var totFee = document.getElementById("tot_fees");
  var division = document.getElementById("division");

  var money = {
    style: "currency",
    currency: "USD"
  };

  var tracCost = 0;
  var rfCost = 0;
  var tracPct = 0;
  var rfPct = 0;

  if (transaction.in_network == "N") {
    oon.innerText = "Out Of Network";
  }
  else {
    oon.innerText = "";
  }

  account.innerText = transaction.account_code;
  stop.innerText = transaction.stop_id + " - " + transaction.stop_name;
  center.innerText = transaction.chain_id;
  sFlag.innerText = transaction.service_used;
  loc.innerText = transaction.city + ", " + transaction.state;
  chain.innerText = transaction.long_description;
  date.innerText = String(transaction.date.date).substring(0, 10) + "  " + String(transaction.time).substring(0, 2) + ":" + String(transaction.time).substring(2, 4);
  ind.innerText = transaction.transaction_ind;
  day.innerText = String(transaction.date.date).substring(8, 10);
  trn.innerText = transaction.com_transaction_id;
  driver.innerText = "(" + transaction.driver_code + ") - " + transaction.driver_name;
  unit.innerText = transaction.unit_number;
  term.innerText = transaction.terminal;
  tFlag.innerText = transaction.tractor_bill_flag;
  prod1F.innerText = transaction.product_1_bill_flag;
  rFlag.innerText = transaction.reefer_bill_flag;
  prod2F.innerText = transaction.product_2_bill_flag;
  oFlag.innerText = transaction.oil_bill_flag;
  prod3F.innerText = transaction.product_3_bill_flag;
  cFlag.innerText = transaction.cash_advance_flag;
  rebFlag.innerText = transaction.rebate_flag;
  card.innerText = transaction.comcheck_card_number;
  hub.innerText = Number(transaction.odom_entered).toLocaleString("en-US");
  inv.innerText = transaction.invoice_number;
  prevHub.innerText = Number(transaction.previous_odom).toLocaleString("en-US");
  prod1C.innerText = Number(transaction.product_1_cost).toLocaleString("en-US", money);
  prod2C.innerText = Number(transaction.product_2_cost).toLocaleString("en-US", money);
  prod3C.innerText = Number(transaction.product_3_cost).toLocaleString("en-US", money);
  prodTot.innerText = (Number(transaction.product_1_cost) + Number(transaction.product_2_cost) + Number(transaction.product_3_cost)).toLocaleString("en-US", money);
  CAA.innerText = Number(transaction.cash_advance_amount).toLocaleString("en-US", money);

  if (Number(transaction.cash_advance_fee) == 0 || transaction.cash_advance_fee == "") {
    CAF.innerText = "$0.00"
  }
  else {
    CAF.innerText =  Number(transaction.cash_advance_fee).toLocaleString("en-US", money);
  }

  if ((Number(transaction.total_gallons) - Number(transaction.other_gallons)) == 0) {
    tGal.innerText = ".00";
  }
  else {
    tGal.innerText = (Number(transaction.total_gallons) - Number(transaction.other_gallons)).toFixed(2);
  }

  if (Number(transaction.reefer_gallons) == 0) {
    rGal.innerText = ".00";
  }
  else {
    rGal.innerText = (Number(transaction.reefer_gallons)).toFixed(2);
  }

  if (((Number(transaction.total_gallons) - Number(transaction.other_gallons)) + Number(transaction.reefer_gallons)) == 0) {
    totGal.innerText = ".00";
  }
  else {
    totGal.innerText = ((Number(transaction.total_gallons) - Number(transaction.other_gallons)) + Number(transaction.reefer_gallons)).toFixed(2);
  }

  var gals = (Number(transaction.total_gallons) - Number(transaction.other_gallons)) + Number(transaction.reefer_gallons);
  if (Number(transaction.total_rebate_amount) > 0) {

    if (gals > 0) {
      
      if ((Number(transaction.total_gallons) - Number(transaction.other_gallons)) > 0) {
        tracPct = (Number(transaction.total_gallons) - Number(transaction.other_gallons)) / gals;
      }

      rfPct = 1 - tracPct;

      var tracCost = (Number(transaction.total_fuel_cost) - Number(transaction.other_fuel_cost)) - (Number(transaction.total_rebate_amount) * tracPct);
      var rfCost = Number(transaction.total_reefer_cost) - (Number(transaction.total_rebate_amount) * rfPct); 
    }
  }
  else {
    var tracCost = (Number(transaction.total_fuel_cost) - Number(transaction.other_fuel_cost));
    var rfCost = Number(transaction.total_reefer_cost);
  }

  if (tracCost != 0) {
    tPPG.innerText = (tracCost / (Number(transaction.total_gallons) - Number(transaction.other_gallons))).toFixed(3);
  }
  else {
    tPPG.innerText = ".000";
  }

  if (rfCost != 0) {
    rPPG.innerText = (rfCost / Number(transaction.reefer_gallons)).toFixed(3);
  }
  else {
    rPPG.innerText = ".000";
  }

  tCost.innerText = tracCost.toLocaleString("en-US", money);
  rCost.innerText = rfCost.toLocaleString("en-US", money);
  totCost.innerText = (tracCost + rfCost).toLocaleString("en-US", money);

  if (Number(transaction.oil_quarts) == 0) {
    oil.innerText = ".00";
  }
  else {
    oil.innerText = Number(transaction.oil_quarts);
  }

  defGal.innerText = transaction.other_gallons;

  if (Number(transaction.oil_cost) != 0) {
    oilPPG.innerText = ((Number(transaction.oil_cost) / Number(transaction.oil_quarts)) / 4).toFixed(3);
  }
  else {
    oilPPG.innerText = ".000";
  }

  if (Number(transaction.other_fuel_cost) != 0) {
    defPPG.innerText = (Number(transaction.other_fuel_cost) / Number(transaction.other_gallons)).toFixed(3);
  }
  else {
    defPPG.innerText = ".000"
  }

  oilCost.innerText = Number(transaction.oil_cost).toLocaleString("en-US", money);
  defCost.innerText = Number(transaction.other_fuel_cost).toLocaleString("en-US", money);
  rebAmt.innerText = Number(transaction.total_rebate_amount).toLocaleString("en-US", money);

  if (tracCost + rfCost + Number(transaction.other_gallons) != 0) {
    adjPPG.innerText = ((Number(transaction.reefer_gallons) + Number(transaction.total_gallons)) / (tracCost + rfCost)).toFixed(3);
  }
  else {
    adjPPG.innerText = ".000";
  }

  totDue.innerText = Number(transaction.total_amount_due).toLocaleString("en-US", money);
  totFee.innerText = Number(transaction.fees).toLocaleString("en-US", money);
  division.innerText = transaction.division;

  return true;
}

function updateSummary() {
  var sortString = sort.loadSortFromStorageFuel();
  var xmlHttp = new XMLHttpRequest();

  xmlHttp.onreadystatechange = function() { 
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      var summary = JSON.parse(xmlHttp.responseText);

      if (summary) {
        setSummary(summary);
      }
    }
  }

  xmlHttp.open("POST", "/kpigraphs/fuel/api/transaction_summary", true);
  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttp.send("sort=" + JSON.stringify(sortString));
}

function setSummary(summary) {
  var tractor_gallons = document.getElementById("tractor_gallons");
  var tractor_cost = document.getElementById("tractor_cost");
  var reefer_gallons = document.getElementById("reefer_gallons");
  var reefer_cost = document.getElementById("reefer_cost");
  var fuel_total_gallons = document.getElementById("fuel_total_gallons");
  var fuel_total_cost = document.getElementById("fuel_total_cost");
  var DEF_gallons = document.getElementById("DEF_gallons");
  var DEF_cost = document.getElementById("DEF_cost");
  var grand_total_gallons = document.getElementById("grand_total_gallons");
  var grand_total_cost = document.getElementById("grand_total_cost");
  var fees = document.getElementById("sum_fees");
  var discPG = document.getElementById("discPG");
  var avgPPG = document.getElementById("avgPPG");
  var rebate = document.getElementById("sum_rebate")
  var stops = document.getElementById("sum_stops");

  tractor_gallons.innerText = summary.tractor_gallons;
  tractor_cost.innerText = summary.tractor_cost;
  reefer_gallons.innerText = summary.reefer_gallons;
  reefer_cost.innerText = summary.reefer_cost;
  fuel_total_gallons.innerText = summary.fuel_total_gallons;
  fuel_total_cost.innerText = summary.fuel_total_cost;
  DEF_gallons.innerText = summary.def_gallons;
  DEF_cost.innerText = summary.def_cost;
  grand_total_gallons.innerText = summary.grand_total_gallons;
  grand_total_cost.innerText = summary.grand_total_cost;
  fees.innerText = summary.fees;
  discPG.innerText = summary.discPG;
  avgPPG.innerText = summary.avgPPG;
  rebate.innerText = summary.rebate;
  stops.innerText = summary.stops;
}

function createSummaryCharts(theUrl, year, callback, divID, title){
  var xmlHttp = new XMLHttpRequest();

  xmlHttp.onreadystatechange = function() { 
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
      callback(JSON.parse(xmlHttp.responseText), divID, title);
  }

  xmlHttp.open("POST", theUrl, true); // true for asynchronous 
  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttp.send("year=" + year);
}

function summaryCharts(year) {
  createSummaryCharts("/kpigraphs/fuel/api/POS", year, fuel_bar_graph_callback, "POS", "<b>POS Discount</b>");
  createSummaryCharts("/kpigraphs/fuel/api/fuel_v_bulk", year, fuel_line_callback, "fuelVBulk", "<b>Avg. Fuel $ vs. Bulk $</b>");
  createSummaryCharts("/kpigraphs/fuel/api/avg_gpd", year, fuel_bar_graph_callback, "avgGalPD", "<b>Avg. Gallons per Day</b>");
  createSummaryCharts("/kpigraphs/fuel/api/avg_dpg", year, fuel_bar_graph_callback, "avgDPG", "<b>Avg. Discount per Gallon</b>");
  createSummaryCharts("/kpigraphs/fuel/api/disc_FSBC", year, fuel_stacked_bar_graph_callback, "discFSBC", "<b>Volume of Discounted Fuel Stops by Chain</b>");
}

function yearSortButton() {
  var year;
  var yearSort = document.getElementById('yearSort');

  for (var i = 0, len = yearSort.options.length; i < len; i++) {
    year = yearSort.options[i];
    if (year.selected === true) {
      break;
    }
  }
  summaryCharts(year.value);

  util.toastLaunch('fuelInqToast', '#5bc0de', 'Summary charts sorted by year ' + year.value + '.'); 
  util.toastLaunch('fuelSummaryToast', '#5bc0de', 'Summary charts sorted by year ' + year.value + '.'); 
  util.toastLaunch('fuelChartToast', '#5bc0de', 'Summary charts sorted by year ' + year.value + '.');
}
