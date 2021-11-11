function units_detail_callback(returnString) {

    var unnum = document.getElementById("unit_num");
    var unyear = document.getElementById("unit_year");
    var unmake = document.getElementById("unit_make");
    var unmodel = document.getElementById("unit_model");
    var unstrmiles = document.getElementById("start_miles");
    var unendmiles = document.getElementById("end_miles");
    var unfstloaddte = document.getElementById("first_load_date");
    var untermdte = document.getElementById("termination_date");
    var unit_delete_status = document.getElementById("delete_status");
    var serial_number = document.getElementById("serial_num");
    var lender = document.getElementById("lender");
    var depreciate_per_mile = document.getElementById("depreciate_per_mile");
    var projected_trade_date = document.getElementById("proj_trade_date");
    var acquisition_price = document.getElementById("acquisition_price");
    var acquisition_date = document.getElementById("acquisition_date");
    var loan_no = document.getElementById("loan_num");
    var interest_rate = document.getElementById("interest_rate");
    var term_of_loan = document.getElementById("loan_term");
    var sell_price = document.getElementById("sell_price");
    var trade_type = document.getElementById("trade_type");

    unnum.innerText = "";
    unyear.innerText = "";
    unmake.innerText = "";
    unmodel.innerText = "";
    unstrmiles.innerText = "";
    unendmiles.innerText = "";
    unfstloaddte.innerText = "";
    untermdte.innerText = "";
    unit_delete_status.innerText = "";
    serial_number.innerText = "";
    lender.innerText = "";
    depreciate_per_mile.innerText = "";
    projected_trade_date.innerText = "";
    acquisition_price.innerText = "";
    acquisition_date.innerText = "";
    loan_no.innerText = "";
    interest_rate.innerText = "";
    term_of_loan.innerText = "";
    sell_price.innerText = "";
    trade_type.innerText = "";

    if (returnString != null && returnString != "") {

        unnum.innerText = returnString.unnum;
        if (returnString.unyear == null) {
            
        } else {
            unyear.innerText = returnString.unyear;
        }
        if (returnString.unmake == null) {
            
        } else {
            unmake.innerText = returnString.unmake;
        }
        if (returnString.unmodel == null) {
            
        } else {
            unmodel.innerText = returnString.unmodel;
        }
        if (returnString.unstrmiles == null) {
            
        } else {
            unstrmiles.innerText = returnString.unstrmiles;
        }
        if (returnString.unendmiles == null) {
            
        } else {
            unendmiles.innerText = returnString.unendmiles;
        }
        if (returnString.unfstloaddte == null) {
            
        } else {
            unfstloaddte.innerText = returnString.unfstloaddte.date.substr(0, 10);
        }
        if (returnString.untermdte == null) {
            
        } else {
            untermdte.innerText = returnString.untermdte.date.substr(0, 10);
        }
        if (returnString.unit_delete_status == 'D') {
            unit_delete_status.innerText = "Deleted";
        } else if (returnString.unit_delete_status == "") {
            unit_delete_status.innerText = "Not Deleted";
        } else {

        }

        if (returnString.serial_number == null) {
           
        } else {
            serial_number.innerText = returnString.serial_number;
        }
        if (returnString.lender == null) {
            
        } else {
            lender.innerText = returnString.lender;
        }
        if (returnString.depreciate_per_mile == null) {
          
        } else {
            depreciate_per_mile.innerText = returnString.depreciate_per_mile;
        }
        if (returnString.projected_trade_date == null) {
           
        } else {
            projected_trade_date.innerText = returnString.projected_trade_date.date.substr(0, 10);
        }
        if (returnString.acquisition_price == null) {
           
        } else {
            acquisition_price.innerText = returnString.acquisition_price;
        }
        if (returnString.acquisition_date == null) {
           
        } else {
            acquisition_date.innerText = returnString.acquisition_date.date.substr(0, 10);
        }
        if (returnString.loan_no == null) {
           
        } else {
            loan_no.innerText = returnString.loan_no;
        }
        if (returnString.interest_rate == null) {
           
        } else {
            interest_rate.innerText = returnString.interest_rate;
        }
        if (returnString.term_of_loan == null) {
           
        } else {
            term_of_loan.innerText = returnString.term_of_loan;
        }
        if (returnString.sell_price == null) {
          
        } else {
            sell_price.innerText = returnString.sell_price;
        }
        if (returnString.trade_type == null) {
           
        } else {
            trade_type.innerText = returnString.trade_type;
        }
    }
    else {
        unnum.innerText = "No information found for that unit number.";
    }
    return unnum.innerText;
}