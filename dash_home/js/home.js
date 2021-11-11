function sidebarInit() {
    document.getElementById("home").classList.add("active-option"); //Add the active option to the current page
}

function start_up() {
    load_home();
    showTime();
    sidebarInit();
    createWeather();
    getNews();
} 

function showTime() {
    var date = new Date();
    var h = date.getHours(); // 0 - 23
    var m = date.getMinutes(); // 0 - 59
    var s = date.getSeconds(); // 0 - 59
    var dd = date.getDate();
    var mm = date.getMonth() + 1;
    var yyyy = date.getFullYear();
    var session = "AM";

    if (h == 0) {
        h = 12;
    }

    if (h > 12) {
        h = h - 12;
        session = "PM";
    }
    
    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    h = (h < 10) ? "0" + h : h;
    m = (m < 10) ? "0" + m : m;
    s = (s < 10) ? "0" + s : s;

    today = yyyy + '/' + mm + '/' + dd;

    var time = h + ":" + m + ":" + s + " " + session;
    document.getElementById("MyClockDisplay").innerText = today + '\n';
    document.getElementById("MyClockDisplay").innerText += time;

    document.getElementById("MyClockDisplay").textContent = today + '\n';
    document.getElementById("MyClockDisplay").textContent += time;

    setTimeout(showTime, 1000);
}

function httpGetAsync(theUrl, sort, callback, title, div) {
    var xmlHttp = new XMLHttpRequest();

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            callback(JSON.parse(xmlHttp.responseText), title, div);
    }

    xmlHttp.open("POST", theUrl, true); // true for asynchronous 
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.send(sort);
}

function getNews() {
    var xmlHttp = new XMLHttpRequest();

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var news = JSON.parse(xmlHttp.responseText);

            if(news) {
                var marquee_html = '<div id="ribbon-marquee" class="news-ribbon-marquee"><div class="marquee-wrapper" id="marquee-wrapper" style="display: none">';
        
                news.forEach(function(item){
                    marquee_html += '<div class="news-ribbon-elements" style="color:white;">'
                                            + '<h5><a target="_blank" href="' + item.link +'">' + item.title +'</a></h5>'
                                            + '<p class="news-ribbon-element-body"><span class="badge badge-secondary">' + item.source + '</span> | ' + item.date + '</p>'
                                            + '</div>"';
                });

                news.forEach(function(item){
                    marquee_html += '<div class="news-ribbon-elements" style="color:white;">'
                                                + '<h5><a target="_blank" href="' + item.link +'">' + item.title +'</a></h5>'
                                                + '<p class="news-ribbon-element-body"><span class="badge badge-secondary">' + item.source + '</span> | ' + item.date + '</p>'
                                                + '</div>"';
                });

                marquee_html += marquee_html + '</div></div>';
                document.getElementById("news-ribbon").innerHTML += marquee_html;

                window.setTimeout(function(){
                    document.getElementById("marquee-wrapper").style.display = "inline-flex";
                }, 2000);
            }
        }
    }

    xmlHttp.open("POST", "dash_home/api/v1/news_rss", true); // true for asynchronous 
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.send("sort=");
}

function createWeather()
{
    var weatherDiv = document.getElementById('weather-div');

    var locations = 
    {
        'RKE':'<a class="weatherwidget-io" href="https://forecast7.com/en/37d27n79d94/roanoke/?unit=us" data-label_1="ROANOKE" data-label_2="WEATHER" data-icons="Climacons Animated" data-theme="dark" >ROANOKE WEATHER</a>',
        'FTW':'<a class="weatherwidget-io" href="https://forecast7.com/en/32d76n97d33/fort-worth/?unit=us" data-label_1="FORT WORTH" data-label_2="WEATHER" data-icons="Climacons Animated" data-theme="dark">FORT WORTH WEATHER</a>',
        'COL':'<a class="weatherwidget-io" href="https://forecast7.com/en/39d96n83d00/columbus/?unit=us" data-label_1="COLUMBUS" data-label_2="WEATHER"  data-icons="Climacons Animated" data-theme="dark" >ROANOKE WEATHER</a>',
        'ENM':'<a class="weatherwidget-io" href="https://forecast7.com/en/37d28n79d10/rustburg/?unit=us" data-label_1="RUSTBURG" data-label_2="WEATHER" data-icons="Climacons Animated" data-theme="dark" >ROANOKE WEATHER</a>',
        'MTC':'<a class="weatherwidget-io" href="https://forecast7.com/en/36d47n81d80/mountain-city/?unit=us" data-label_1="MOUNTAIN CITY" data-label_2="WEATHER"  data-icons="Climacons Animated" data-theme="dark" >ROANOKE WEATHER</a>',
        'ALB':'<a class="weatherwidget-io" href="https://forecast7.com/en/31d58n84d16/albany/?unit=us" data-label_1="ALBANY" data-label_2="WEATHER" data-icons="Climacons Animated" data-theme="dark" >ROANOKE WEATHER</a>',
        'CHT':'<a class="weatherwidget-io" href="https://forecast7.com/en/35d93n83d34/chestnut-hill/?unit=us" data-label_1="CHESTNUT HILL" data-label_2="WEATHER" data-icons="Climacons Animated" data-theme="dark" >ROANOKE WEATHER</a>',
        'DAN':'<a class="weatherwidget-io" href="https://forecast7.com/en/36d59n79d40/danville/?unit=us" data-label_1="DANVILLE" data-label_2="WEATHER" data-icons="Climacons Animated" data-theme="dark" >ROANOKE WEATHER</a>',
        'DEF':'<a class="weatherwidget-io" href="https://forecast7.com/en/43d25n89d34/deforest/?unit=us" data-label_1="DEFOREST" data-label_2="WEATHER" data-icons="Climacons Animated" data-theme="dark" >ROANOKE WEATHER</a>',
        'EDE':'<a class="weatherwidget-io" href="https://forecast7.com/en/36d49n79d77/eden/?unit=us" data-label_1="EDEN" data-label_2="WEATHER" data-icons="Climacons Animated" data-theme="dark" >ROANOKE WEATHER</a>',
        'FIN':'<a class="weatherwidget-io" href="https://forecast7.com/en/41d04n83d65/findlay/?unit=us" data-label_1="FINDLAY" data-label_2="WEATHER" data-icons="Climacons Animated" data-theme="dark" >ROANOKE WEATHER</a>',
    }

    var storageWeather = local_storage.GetLocalStorage('weather-location')
    console.log(storageWeather);
    if(storageWeather != null && storageWeather != "")
    {
        weatherDiv.innerHTML = locations[storageWeather];
        !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
    }
    else{
        weatherDiv.innerHTML = locations['RKE'];
        !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
    }
}

function load_home () {
    httpGetAsync("dash_home/api/v1/daily_revenue_dedicated", "company=102", callback_daily_revenue, 'FLMR Dedicated/Spotting Revenue', "chart2");
    httpGetAsync("dash_home/api/v1/daily_revenue", "company=102", callback_daily_revenue, 'FLMR Regional/OTR Revenue', "chart1");
    httpGetAsync("dash_home/api/v1/daily_revenue", "company=301", callback_daily_revenue, 'Englander Revenue', "chart3");
    httpGetAsync("dash_home/api/v1/daily_revenue", "company=102&division=900", callback_daily_revenue, 'Brokerage Revenue', "chart4");
}

start_up();