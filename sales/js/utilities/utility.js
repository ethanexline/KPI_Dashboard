class utility{

  // sleep time expects milliseconds
  sleep (time) {
    return new Promise((resolve) => setTimeout(resolve, time));
  }

  //Fakes a resize
  fakeResize(){
      this. sleep(500).then(() => {
      window.dispatchEvent(new Event('resize'));
      });
  }

  //capitalize first letter of each word 
  capitalize_Words(str)
  {
    var work_string = str.toLowerCase();
    return work_string.replace(/\w\S*/g, function(txt){
          return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
  }

  toastLaunch(toastID, colorRGB, message) {
    var toast = document.getElementById(toastID);
    var blocks = document.getElementsByClassName("square");
    var alerts = document.getElementsByClassName("alertText");

    for (var i = 0; i < blocks.length; i++) {
      blocks.item(i).style.background = colorRGB;
    }
    
    for (var i = 0; i < alerts.length; i++) {
      alerts.item(i).innerText = message;
    }

    setTimeout(() => {
      $(toast).toast('show')
    }, 0);
  }

  //Formats a date variable in "YYYY-MM-DD"
   formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
      month = '0' + month;

    if (day.length < 2) 
      day = '0' + day;

    return [year, month, day].join('-');
  }

  //Gets the first and last business day from a year and week number. 
   getWeekDateRange(year, week){
    var firstDay = new Date(year, 0, 1).getDay();
    var d = new Date("Jan 01, " + year + " 01:00:00");
    var w = d.getTime() - (3600000 * 24 * (firstDay - 0)) + 604800000 * (week - 1)
    var n1 = new Date(w);
    var n2 = new Date(w + 518400000)
    
    return {"startDate": this.formatDate(n1), "endDate": this.formatDate(n2)};
  }

   getWeekNumber(d) {
    // Copy date so don't modify original
    d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
    // Set to nearest Thursday: current date + 4 - current day number
    // Make Sunday's day number 7
    d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay()||1));
    // Get first day of year
    var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
    // Calculate full weeks to nearest Thursday
    var weekNo = Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7);
    // Return array of year and week number
    return [d.getUTCFullYear(), weekNo];
  }

  //This function takes in a HTML element, extracts its value 
  //and returns elseValue if that value is null.
  getIfNullElse(element, elseValue)
  {
    console.log("GETIFELSE");
    console.log(element);
    if(element === null){
      return "";
    }
    else
    {
      return element.value;
    }
  }
} 

var util = new utility();