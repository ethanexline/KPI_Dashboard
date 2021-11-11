class sorting{
    
    /*
    This function loads the order revenue dashboard. Should be called every time the dashboard
    needs to be reloaded.
    */
    LocalStorageSortOptions(){
    var sortOptions = local_storage.GetLocalStorage("sort-options");
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

    //Assembles the global sort string for use in API Calls
    getGlobalSortString(sortOptions = false){
        var newSort = new sortStorage()
    
        if(sortOptions == false)
        {
            var sortOptions = this.LocalStorageSortOptions();
        }

        if(sortOptions != null){
            sortOptions.divisions.forEach(element => {
                newSort.divisions.push(element);
            });
        
            sortOptions.companies.forEach(element => {
                newSort.companies.push(element);
            });
        
            sortOptions.terminals.forEach(element => {
                newSort.terminals.push(element);
            });
        
            if(sortOptions.startDate != ""){
                newSort.startDate = sortOptions.startDate;
            }
        
            if(sortOptions.endDate != ""){
                newSort.endDate = sortOptions.endDate;
            }
        
            return JSON.stringify(newSort);
        }

        else{
            return JSON.stringify(newSort);
        }
    }
  
    //Adds the sort divisions
    addSortDivisions(divisions, divID){
        var divisionHtml = "";

        divisions.forEach(element => {
            divisionHtml += '<input name="checkbox-division" type="checkbox" value="' + element[0] +'" />' + "(" + element[0] + ") - " + util.capitalize_Words(element[1]) + '<br />';
        });
    
        var modalElement = document.getElementById(divID);
        modalElement.innerHTML += divisionHtml;
        sort.checkSortDivisions();
    }
    
    //Adds the sort terminals
    addSortTerminals(terminals, divID){
        var terminalHtml = "";

        terminals.forEach(element => {
            terminalHtml += '<input name="checkbox-terminal" type="checkbox" value="' + element[0] +'" />' + "(" + element[0] + ") - "  + util.capitalize_Words(element[1]) + '<br />';
        });
    
        var modalElement = document.getElementById(divID);
        modalElement.innerHTML = terminalHtml;
        sort.checkSortTerminals();
    }


    

    //Checks the currently sorted divisions 
    checkSortDivisions(){
        var sortOptions = this.LocalStorageSortOptions();
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
    
    checkSortTerminals(){
        var sortOptions = this.LocalStorageSortOptions();
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

    setDateRange(){
        var sortOptions = this.LocalStorageSortOptions();
        var startDateElem = document.getElementById("start-date");
        var endDateElem = document.getElementById("end-date");
        var startWeekElem = document.getElementById("start-week");
        var endWeekElem = document.getElementById("end-week");

        startDateElem.value = sortOptions.startDate;
        endDateElem.value = sortOptions.endDate;

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
  
    loadSortFromStorage(){
        var sortOptions = this.LocalStorageSortOptions();
    
        //If sort options is null, set it to a default value. 
        //TODO - come up with a better solution!
        if(sortOptions == null){
            local_storage.SetLocalStorage("sort-options", {"divisions":["100","110","111","201","202","300","303","310","311","312","314","315","320","400","402","405","415","500","600","610","700","701","703","710","716","800","801","803","805","811","900","901","902","909","910","930","990","COR","TRM"],"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS"],"companies":["102","301"],"startDate":"","endDate":""});
        }
    
        this.getSortOptions("/kpigraphs/sales/api/divisions", this.addSortDivisions, 'sort-divisions');
        this.getSortOptions("/kpigraphs/sales/api/terminals", this.addSortTerminals, 'sort-terminals');
        
        sort.setDateRange(); 
    }

  
  //Stores the global sort parameters in the browser's local storage 
    GlobalSort(){
        var sortOptions = new sortStorage();
        var divisions = this.getCheckedBoxes("checkbox-division");
        var terminals = this.getCheckedBoxes("checkbox-terminal");
        var companies = this.getCheckedBoxes("checkbox-company");
        var startDate = document.getElementById("start-date");
        var endDate = document.getElementById("end-date");

        sortOptions.startDate = startDate.value;
        sortOptions.endDate = endDate.value;
    
        divisions.forEach(element => {
            sortOptions.divisions.push(element.value);
        });
    
        terminals.forEach(element => {
            sortOptions.terminals.push(element.value);
        });
        
        companies.forEach(element => {
            sortOptions.companies.push(element.value);
        });
    
        local_storage.SetLocalStorage("sort-options", sortOptions);
               
        var displayStart = document.getElementById("disp-start-date");
        var displayEnd = document.getElementById("disp-end-date");
        
        displayStart.innerText = sortOptions.startDate;
        displayEnd.innerText = sortOptions.endDate;
        
        if (displayEnd.innerText == "") {
            displayEnd.innerText = util.formatDate(new Date());
        }

        if (displayStart.innerText == "" && displayEnd.innerText != "") {
            date_initialize();
        }

        util.toastLaunch("sortToast", "#007bff", "Custom sort has been applied.");

        unPressButtons();
        sliceButtonHandler();
        timeButtonHandler();
        reloadHandler();
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

    //Stores the global sort parameters in the browser's local storage 
    GlobalSortDefault() {
        local_storage.SetLocalStorage("sort-options", {"divisions":["100","110","111","201","202","300","303","310","311","312","314","315","320","400","402","405","415","500","600","610","700","701","703","710","716","800","801","803","805","811","900","901","902","909","910","930","990","COR","TRM"],"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS"],"companies":["102","301"],"startDate":"","endDate":""});

        this.getSortOptions("/kpigraphs/sales/api/divisions", this.addSortDivisions, 'sort-divisions');
        this.getSortOptions("/kpigraphs/sales/api/terminals", this.addSortTerminals, 'sort-terminals');

        sort.checkSortDivisions();
        sort.checkSortTerminals();
        sort.setDateRange();

        pressButtons();
        date_initialize();
        reloadHandler();
    }

    //(until I come up with a better solution) same as above, only launches a toast

    GlobalSortDefaultBtn() {
        local_storage.SetLocalStorage("sort-options", {"divisions":["100","110","111","201","202","300","303","310","311","312","314","315","320","400","402","405","415","500","600","610","700","701","703","710","716","800","801","803","805","811","900","901","902","909","910","930","990","COR","TRM"],"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS"],"companies":["102","301"],"startDate":"","endDate":""});

        sort.checkSortDivisions();
        sort.checkSortTerminals();
        sort.setDateRange();

        util.toastLaunch("sortToast", "#ffc107", "Default sort has been applied.");

        pressButtons();
        sliceButtonHandler();
        timeButtonHandler();
        date_initialize();
        reloadHandler();
    }

    PresetSort(presetID){
        var sortOptions = this.LocalStorageSortOptions();

        if (presetID == "default") 
        {
            local_storage.SetLocalStorage("sort-options", {"divisions":["100","110","111","201","202","300","303","310","311","312","314","315","320","400","402","405","415","500","600","610","700","701","703","710","716","800","801","803","805","811","900","901","902","909","910","930","990","COR","TRM"],"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS"],"companies":["102","301"],"startDate": sortOptions.startDate,"endDate": sortOptions.endDate});
            local_storage.SetLocalStorage("slice-btn", presetID);
            sliceButtonHandler();
        }

        else if(presetID == "flmr-all")
        {
            local_storage.SetLocalStorage("sort-options", {"divisions":["100","110","111","201","202","300","303","310","311","312","314","315","320","400","402","405","415","500","600","610","700","701","703","710","716","800","801","803","805","811","900","901","902","909","910","930","990","COR","TRM"],"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS"],"companies":["102"],"startDate": sortOptions.startDate,"endDate": sortOptions.endDate});
            local_storage.SetLocalStorage("slice-btn", presetID);
            sliceButtonHandler();
        }

        else if(presetID == "flmr-otr")
        {
            local_storage.SetLocalStorage("sort-options", {"divisions":["100","110"],"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS"],"companies":["102"],"startDate": sortOptions.startDate,"endDate": sortOptions.endDate});
            local_storage.SetLocalStorage("slice-btn", presetID);
            sliceButtonHandler();
        }

        else if(presetID == "flmr-spot")
        {
            local_storage.SetLocalStorage("sort-options", {"divisions":["300","303","310","311","312","314","315","320","400","402","405","415"],"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS"],"companies":["102"],"startDate": sortOptions.startDate,"endDate": sortOptions.endDate});
            local_storage.SetLocalStorage("slice-btn", presetID);
            sliceButtonHandler();
        }

        else if(presetID == "flmr-brokerage")
        {
            local_storage.SetLocalStorage("sort-options", {"divisions":["900"],"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS"],"companies":["102"],"startDate": sortOptions.startDate,"endDate": sortOptions.endDate});
            local_storage.SetLocalStorage("slice-btn", presetID);
            sliceButtonHandler();
        }

        else if(presetID == "eng-all")
        {
            local_storage.SetLocalStorage("sort-options", {"divisions":["100","110","111","201","202","300","303","310","311","312","314","315","320","400","402","405","415","500","600","610","700","701","703","710","716","800","801","803","805","811","900","901","902","909","910","930","990","COR","TRM"],"terminals":["ALB","BUM","CHT","COL","COR","DAN","DEF","EDE","ENM","FIN","FTW","HAR","LAT","LMC","MON","MTC","NCO","RKE","SPR","WEI","WMS"],"companies":["301"],"startDate": sortOptions.startDate,"endDate": sortOptions.endDate});
            local_storage.SetLocalStorage("slice-btn", presetID);
            sliceButtonHandler();
        }

        else if(presetID == "last-52-weeks")
        {
            var d = new Date(); // today!
            d.setDate(d.getDate() - 370);
            var yearWeek = util.getWeekNumber(d);

            var weekStartDate = util.getWeekDateRange(yearWeek[0], yearWeek[1]).startDate;

            sortOptions.startDate = weekStartDate;
            sortOptions.endDate = util.formatDate(new Date());
            local_storage.SetLocalStorage("sort-options", sortOptions);
            local_storage.SetLocalStorage("time-btn", presetID);
            timeButtonHandler();
        }

        else if(presetID == "last-90-days")
        {
            var d = new Date(); // today!
            d.setDate(d.getDate() - 90);
            var yearWeek = util.getWeekNumber(d);

            var weekStartDate = util.getWeekDateRange(yearWeek[0], yearWeek[1]).startDate;

            sortOptions.startDate = weekStartDate;
            sortOptions.endDate = util.formatDate(new Date());;
            local_storage.SetLocalStorage("sort-options", sortOptions);
            local_storage.SetLocalStorage("time-btn", presetID);
            timeButtonHandler();
        }

        else if(presetID == "last-4-weeks")
        {   
            var d = new Date(); // today!
            d.setDate(d.getDate() - 28);
            var yearWeek = util.getWeekNumber(d);

            var weekStartDate = util.getWeekDateRange(yearWeek[0], yearWeek[1]).startDate;
            
            sortOptions.startDate = weekStartDate;
            sortOptions.endDate = util.formatDate(new Date());;
            local_storage.SetLocalStorage("sort-options", sortOptions);
            local_storage.SetLocalStorage("time-btn", presetID);
            timeButtonHandler();
        }

        var displayStart = document.getElementById("disp-start-date");
        var displayEnd = document.getElementById("disp-end-date");

        displayStart.innerText = sortOptions.startDate;
        displayEnd.innerText = sortOptions.endDate;
        
        sort.checkSortDivisions();
        sort.checkSortTerminals();
        reloadHandler();
    }
}

var sort = new sorting();