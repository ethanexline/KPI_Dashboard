class sorting{
    
    /*
    This function loads the fuel dashboard. Should be called every time the dashboard
    needs to be reloaded.
    */

    LocalStorageSortOptionsFuel(){
        var sortOptions = local_storage.GetLocalStorage("sort-options-fuel");
        return sortOptions;
    }
    
    //Function to get the sort options based on dimensions in the warehouse
    getSortOptions(theUrl, callback, divID){
        var xmlHttp = new XMLHttpRequest();

        xmlHttp.onreadystatechange = function() { 
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                callback(JSON.parse(xmlHttp.responseText), divID);
        }

        xmlHttp.open("POST", theUrl, true); // true for asynchronous 
        xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlHttp.send("sort=" + "null");
    }
    
    // Pass the checkbox name to the function
    getCheckedBoxes(chkboxName) {
        var checkboxes = document.getElementsByName(chkboxName);
        var checkboxesChecked = [];

        // loop over them all
        for (var i=0; i<checkboxes.length; i++) {

            // And stick the checked ones onto an array...
            if (checkboxes[i].checked) {
                checkboxesChecked.push(checkboxes[i]);
            }
        }

        // Return the array if it is non-empty, or null
        return checkboxesChecked.length > 0 ? checkboxesChecked : null;
    }


    // Assembles the global sort string for use with the fuel dashboard
    getGlobalSortStringFuel(sortOptions = false) {
        var newSort = new sortStorageFuel();

        if(sortOptions == false) {
            var sortOptions = this.LocalStorageSortOptionsFuel();
        }

        if (sortOptions != null) {

            if(sortOptions.chain_id != "") {
                newSort.chain_id = sortOptions.chain_id;
            }

            if(sortOptions.stop_id != "") {
                newSort.stop_id = sortOptions.stop_id;
            }

            if(sortOptions.stop_name != "") {
                newSort.stop_name = sortOptions.stop_name;
            }

            if(sortOptions.city != "") {
                newSort.city = sortOptions.city;
            }

            sortOptions.divisions.forEach(element => {
                newSort.divisions.push(element);
            });

            sortOptions.states.forEach(element => {
                newSort.states.push(element);
            });

            if(sortOptions.unit_number != "") {
                newSort.unit_number = sortOptions.unit_number;
            }

            if(sortOptions.driver_code != "") {
                newSort.driver_code = sortOptions.driver_code;
            }

            if(sortOptions.driver_name != "") {
                newSort.driver_name = sortOptions.driver_name;
            }

            sortOptions.terminals.forEach(element => {
                newSort.terminals.push(element);
            });

            if(sortOptions.fees != "") {
                newSort.fees = sortOptions.fees;
            }

            if(sortOptions.reefer != "") {
                newSort.reefer = sortOptions.reefer;
            }

            if(sortOptions.def != "") {
                newSort.def = sortOptions.def;
            }

            if(sortOptions.non_network != "") {
                newSort.non_network = sortOptions.non_network;
            }

            if(sortOptions.non_fuel != "") {
                newSort.non_fuel = sortOptions.non_fuel;
            }

            if(sortOptions.bulk != "") {
                newSort.bulk = sortOptions.bulk;
            }

            if(sortOptions.company != "") {
                newSort.company = sortOptions.company;
            }

            if(sortOptions.start_date != ""){
                newSort.start_date = sortOptions.start_date;
            }
        
            if(sortOptions.end_date != ""){
                newSort.end_date = sortOptions.end_date;
            }

            return JSON.stringify(newSort);
        }

        else {
            return JSON.stringify(newSort);
        }
    }

    addSortYears(years, divID) {
        var yearHtml = "";

        years.forEach(element => {
            yearHtml += '<option value=' + element[0] + ' >' + element[0] + '</option><br>'
        });

        var yearSelect = document.getElementById(divID);
        yearSelect.innerHTML = yearHtml;
    }

    //Adds the sort divisions
    addSortDivisions(divisions, divID){      
        var divisionHtml = "";

        divisions.forEach(element => {
            divisionHtml += '<input name="checkbox-division" type="checkbox" value="' + element[0] +'" />' + "(" + element[0] + ") - " + util.capitalize_Words(element[1]) + '<br />';
        });

        var modalElement = document.getElementById(divID);
        modalElement.innerHTML = divisionHtml;
        sort.checkSortDivisions();
    }

    addSortChains(chains, divID) {
        var chainHtml = "";

        chains.forEach(element => {
            chainHtml += '<input name="chain" type="radio" id="' + element[0] +'" value="' + element[0] + '" />' + " " + element[0] + " - " + util.capitalize_Words(element[1]) + '<br />';
        });

        var modalElement = document.getElementById(divID);
        modalElement.innerHTML = chainHtml;
    }

    addSortStates(states, divID) {
        var stateHtml = "";

        states.forEach(element => {
            stateHtml += '<input name="checkbox-state" type="checkbox" value="' + element[0] + '" />' + " " + element[0] + " - " + util.capitalize_Words(element[1]) + '<br />';
        });

        var modalElement = document.getElementById(divID);
        modalElement.innerHTML = stateHtml;
        sort.checkSortStates();
    }

    addSortTerminalsFuel(terminals, divID){
    
        var terminalHtml = "";

        terminals.forEach(element => {
            terminalHtml += '<input name="checkbox-terminal" type="checkbox" value="' + element[0] +'" />' + "(" + element[0] + ") - "  + util.capitalize_Words(element[1]) + '<br />';
        });

        terminalHtml += '<input name="checkbox-terminal" type="checkbox" value="No Terminal" /> No Terminal <br />';
    
        var modalElement = document.getElementById(divID);
        modalElement.innerHTML = terminalHtml;
        sort.checkSortTerminalsFuel();
    }

    checkSortStates(){
        var sortOptions = this.LocalStorageSortOptionsFuel();
        var stateElements = document.getElementsByName("checkbox-state");

        if(sortOptions){
            stateElements.forEach(element => {
                if(sortOptions.states.includes(String(element.value))){
                    element.checked = true;
                }
                else{
                    element.checked = false;
                }
            });
        }
        else{
            stateElements.forEach(element => {
                element.checked = true;
            });
        }
    }

    checkSortChains(){
        var sortOptions = this.LocalStorageSortOptionsFuel();
        var chainElements = document.getElementsByName("chain");

        if(sortOptions){
            chainElements.forEach(element => {
                if(sortOptions.chain_id === (String(element.value))){
                    document.getElementById("select-all-chains").checked = false;
                    document.getElementById(element.id).checked = true;
                }
            });
        }
        else{
            var selectAll = document.getElementById("select-all-chains");
            selectAll.checked = true;
        }
    }

    //Checks the currently sorted divisions 
    checkSortDivisions(){
        var sortOptions = this.LocalStorageSortOptionsFuel();
        var divisionElements = document.getElementsByName("checkbox-division");

        if(sortOptions){
            divisionElements.forEach(element => {
                if(sortOptions.divisions.includes(String(element.value))){
                    element.checked = true;
                }
                else{
                    element.checked = false;
                }
            });
        }
        else{
            divisionElements.forEach(element => {
                element.checked = true;
            });
        }
    }

    checkSortTerminalsFuel(){
        var sortOptions = this.LocalStorageSortOptionsFuel();
        var terminalElements = document.getElementsByName("checkbox-terminal");

        if(sortOptions){
            terminalElements.forEach(element => {
                if(sortOptions.terminals.includes(String(element.value))){
                    element.checked = true;
                }
                else{
                    element.checked = false;
                }
            });
        }
        else{
            terminalElements.forEach(element => {
                element.checked = true;
            });
        }
    }

    setDateRangeFuel(){
        var sortOptions = this.LocalStorageSortOptionsFuel();
        var startDateElem = document.getElementById("start-date");
        var endDateElem = document.getElementById("end-date");
        var startWeekElem = document.getElementById("start-week");
        var endWeekElem = document.getElementById("end-week");

        startDateElem.value = sortOptions.start_date;
        endDateElem.value = sortOptions.end_date;

        startWeekElem.value = "";
        endWeekElem.value = "";
    }
  
    togglecheckboxes(cn, origin){
        var originElem = document.getElementById(origin)
        var cbarray = document.getElementsByName(cn);

        for(var i = 0; i < cbarray.length; i++){
            cbarray[i].checked = originElem.checked;
        }   
    }

    loadSortFromStorageFuel(){
        var sortOptions = this.LocalStorageSortOptionsFuel();
    
        //If sort options is null, set it to a default value. 
        //TODO - come up with a better solution!
        if(sortOptions === null){
            local_storage.SetLocalStorage("sort-options-fuel", 
            {"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS","No Terminal"],
            "divisions":["100","110","111","201","202","300","303","310","311","312","314","315","320","400","402","405","415","500","600","610","700","701","703","710","716","800","801","803","805","811","900","901","902","909","910","930","990","COR","TRM"],
            "company":"",
            "states":["AK", "AL", "AR", "AZ", "CA", "CO", "CT", "DC", "DE", "FL", "GA", "HI", "IA", "ID", "IL", "IN", "KS", "KY", "LA", "MA", "MD", "ME", "MI", "MN", "MO", "MS", "MT", "NC", "ND", "NE", "NH", "NJ", "NM", "NV", "NY", "OH", "OK", "ON", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VA", "VT", "WA", "WI", "WV", "WY"],
            "chains":["CH","CS","CV","EM","GO","MP","MU","PB","PE","PI","PM","PN","PO","QT","SE","SQ","TA","TO"],
            "start_date":"",
            "end_date":"",
            "chain_id":"",
            "stop_id":"",
            "stop_name":"",
            "city":"",
            "unit_number":"",
            "driver_code":"",
            "driver_name":"",
            "fees":"",
            "reefer":"",
            "def":"",
            "non_network":"",
            "non_fuel":"",
            "bulk":"",
            "company":"",
            "sortby":""});
        }
    
        this.getSortOptions("/kpigraphs/fuel/api/divisions", this.addSortDivisions, 'sort-divisions');
        this.getSortOptions("/kpigraphs/fuel/api/terminals", this.addSortTerminalsFuel, 'sort-terminals');
        this.getSortOptions("/kpigraphs/fuel/api/states", this.addSortStates, 'sort-states');
        this.getSortOptions("/kpigraphs/fuel/api/chains", this.addSortChains, 'sort-chains');
        this.getSortOptions("/kpigraphs/fuel/api/years", this.addSortYears, 'yearSort');
        setTimeout(function() {sort.checkSortChains(); }, 100);
        
        sort.setDateRangeFuel();
        return sortOptions
    }

    GlobalSortFuel() {
        var sortOptions = new sortStorageFuel();
    
        var terminals = this.getCheckedBoxes("checkbox-terminal");
        var company = this.getCheckedBoxes("company");
        var chain_id = this.getCheckedBoxes("chain");
        var stop_id = document.getElementById("stop_id");
        var stop_name = document.getElementById("stop_name");
        var city = document.getElementById("city");
        var divisions = this.getCheckedBoxes("checkbox-division");
        var states = this.getCheckedBoxes("checkbox-state");
        var unit_number = document.getElementById("fuel_unit");
        var driver_name = document.getElementById("driver_name");
        var driver_code = document.getElementById("driver_code");
        var fees = this.getCheckedBoxes("fee");
        var reefer = this.getCheckedBoxes("rf");
        var def = this.getCheckedBoxes("def");
        var non_network = this.getCheckedBoxes("oon");
        var non_fuel = this.getCheckedBoxes("nf");
        var bulk = this.getCheckedBoxes("bulk");
        var startDate = document.getElementById("start-date");
        var endDate = document.getElementById("end-date");
        var sortBy = document.getElementById("sortby");

        sortOptions.start_date = startDate.value;
        sortOptions.end_date = endDate.value;
    
        terminals.forEach(element => {
            sortOptions.terminals.push(element.value);
        });
        
        sortOptions.company = company[0].value;
        sortOptions.chain_id = chain_id[0].value;
        sortOptions.stop_id = stop_id.value;
        sortOptions.stop_name = stop_name.value;
        sortOptions.city = city.value;

        divisions.forEach(element => {
            sortOptions.divisions.push(element.value);
        });

        states.forEach(element => {
            sortOptions.states.push(element.value);
        });

        sortOptions.unit_number = unit_number.value;
        sortOptions.driver_code = driver_code.value;
        sortOptions.driver_name = driver_name.value;
        sortOptions.fees = fees[0].value;
        sortOptions.reefer = reefer[0].value;
        sortOptions.def = def[0].value;
        sortOptions.non_network = non_network[0].value;
        sortOptions.non_fuel = non_fuel[0].value;
        sortOptions.bulk = bulk[0].value;
        
        for (var i = 0, len = sortBy.options.length; i < len; i++) {
            var by = sortBy.options[i];
            if (by.selected === true) {
                sortOptions.sortby = by.value;
                break;
            }
        }
    
        local_storage.SetLocalStorage("sort-options-fuel", sortOptions);
               
        var displayStart = document.getElementById("disp-start-date");
        var displayEnd = document.getElementById("disp-end-date");
        
        displayStart.innerText = sortOptions.start_date;
        displayEnd.innerText = sortOptions.end_date;
        
        if (displayEnd.innerText == "") {
            displayEnd.innerText = util.formatDate(new Date());
        }

        if (displayStart.innerText == "" && displayEnd.innerText != "") {
            date_initialize();
        }

        util.toastLaunch("fuelInqToast", "#007bff", "Custom sort has been applied.");
        util.toastLaunch("fuelChartToast", "#007bff", "Custom sort has been applied.");
        util.toastLaunch("fuelSummaryToast", "#007bff", "Custom sort has been applied.");

        buildDatatable();
        updateSummary();

        setTimeout(function() {load_fuel();}, 200);
        setTimeout(function() {sort.checkSortChains();}, 600);
    }

    //Updates the start date according to the start week that is selected
    updateStartDate(){
        var startWeek = document.getElementById("start-week");
        var startDate = document.getElementById("start-date");
        
        startDate.value = util.getWeekDateRange(startWeek.value.split("-W")[0], startWeek.value.split("-W")[1]).startDate;
    }
    
    //Updates the end date according to the end week that is selected
    updateEndDate(week){
        var endWeek = document.getElementById("end-week");
        var endDate = document.getElementById("end-date");
        var startWeek = document.getElementById("start-week");
        var startDate = document.getElementById("start-date");

        endDate.value = util.getWeekDateRange(endWeek.value.split("-W")[0], endWeek.value.split("-W")[1]).endDate;

        if (startWeek.value == "") {
            startWeek.value = endWeek.value;
            startDate.value = util.getWeekDateRange(startWeek.value.split("-W")[0], startWeek.value.split("-W")[1]).startDate;
        }
    }

    GlobalSortDefaultFuel() {

        local_storage.SetLocalStorage("sort-options-fuel", 
            {"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS","No Terminal"],
            "divisions":["100","110","111","201","202","300","303","310","311","312","314","315","320","400","402","405","415","500","600","610","700","701","703","710","716","800","801","803","805","811","900","901","902","909","910","930","990","COR","TRM"],
            "company":"",
            "states":["AK", "AL", "AR", "AZ", "CA", "CO", "CT", "DC", "DE", "FL", "GA", "HI", "IA", "ID", "IL", "IN", "KS", "KY", "LA", "MA", "MD", "ME", "MI", "MN", "MO", "MS", "MT", "NC", "ND", "NE", "NH", "NJ", "NM", "NV", "NY", "OH", "OK", "ON", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VA", "VT", "WA", "WI", "WV", "WY"],
            "chains":["CH","CS","CV","EM","GO","MP","MU","PB","PE","PI","PM","PN","PO","QT","SE","SQ","TA","TO"],
            "start_date":"",
            "end_date":"",
            "chain_id":"",
            "stop_id":"",
            "stop_name":"",
            "city":"",
            "unit_number":"",
            "driver_code":"",
            "driver_name":"",
            "fees":"",
            "reefer":"",
            "def":"",
            "non_network":"",
            "non_fuel":"",
            "bulk":"",
            "company":"",
            "sortby":""});

        this.getSortOptions("/kpigraphs/fuel/api/divisions", this.addSortDivisions, 'sort-divisions');
        this.getSortOptions("/kpigraphs/fuel/api/terminals", this.addSortTerminalsFuel, 'sort-terminals');
        this.getSortOptions("/kpigraphs/fuel/api/states", this.addSortStates, 'sort-states');
        this.getSortOptions("/kpigraphs/fuel/api/chains", this.addSortChains, 'sort-chains');
        this.getSortOptions("/kpigraphs/fuel/api/years", this.addSortYears, 'yearSort');
        
        sort.checkSortDivisions();
        sort.checkSortStates();
        sort.checkSortTerminalsFuel();

        sort.setDateRangeFuel(); 
        
        date_initialize();
        buildDatatable();
        updateSummary();
        setTimeout(function() {sort.checkSortChains(); load_fuel();}, 100);
    }

    GlobalSortDefaultFuelBtn() {

        local_storage.SetLocalStorage("sort-options-fuel", 
            {"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS","No Terminal"],
            "divisions":["100","110","111","201","202","300","303","310","311","312","314","315","320","400","402","405","415","500","600","610","700","701","703","710","716","800","801","803","805","811","900","901","902","909","910","930","990","COR","TRM"],
            "company":"",
            "states":["AK", "AL", "AR", "AZ", "CA", "CO", "CT", "DC", "DE", "FL", "GA", "HI", "IA", "ID", "IL", "IN", "KS", "KY", "LA", "MA", "MD", "ME", "MI", "MN", "MO", "MS", "MT", "NC", "ND", "NE", "NH", "NJ", "NM", "NV", "NY", "OH", "OK", "ON", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VA", "VT", "WA", "WI", "WV", "WY"],
            "chains":["CH","CS","CV","EM","GO","MP","MU","PB","PE","PI","PM","PN","PO","QT","SE","SQ","TA","TO"],
            "start_date":"",
            "end_date":"",
            "chain_id":"",
            "stop_id":"",
            "stop_name":"",
            "city":"",
            "unit_number":"",
            "driver_code":"",
            "driver_name":"",
            "fees":"",
            "reefer":"",
            "def":"",
            "non_network":"",
            "non_fuel":"",
            "bulk":"",
            "company":"",
            "sortby":""});

        this.getSortOptions("/kpigraphs/fuel/api/divisions", this.addSortDivisions, 'sort-divisions');
        this.getSortOptions("/kpigraphs/fuel/api/terminals", this.addSortTerminalsFuel, 'sort-terminals');
        this.getSortOptions("/kpigraphs/fuel/api/states", this.addSortStates, 'sort-states');
        this.getSortOptions("/kpigraphs/fuel/api/chains", this.addSortChains, 'sort-chains');
        this.getSortOptions("/kpigraphs/fuel/api/years", this.addSortYears, 'yearSort');
        
        sort.checkSortDivisions();
        sort.checkSortStates();
        sort.checkSortTerminalsFuel();

        util.toastLaunch("fuelInqToast", "#ffc107", "Default sort has been applied.");
        util.toastLaunch("fuelChartToast", "#ffc107", "Default sort has been applied.");
        util.toastLaunch("fuelSummaryToast", "#ffc107", "Default sort has been applied.");

        sort.setDateRangeFuel(); 

        $('input[type=radio]').prop('checked', function () {
            return this.getAttribute('checked') == 'checked';
        });

        $('input[type=checkbox]').prop('checked', function () {
            return this.getAttribute('checked') == 'checked';
        });

        $('option').prop('selected', function () {
            return this.getAttribute('selected') == 'selected';
        });
        
        date_initialize();
        buildDatatable();
        updateSummary();
        setTimeout(function() {sort.checkSortChains(); load_fuel(); }, 100);
    }
}

var sort = new sorting();