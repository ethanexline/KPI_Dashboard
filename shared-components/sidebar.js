$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
    $("#page-content-wrapper").toggleClass("toggled");
    $("#top-bar").toggleClass("toggled");
});

var path = window.location.pathname;
var page = path.split("/").pop();

if(page != "dashSettings")
{
    var current_element = document.getElementById(page).classList.add("active-option"); //Add the active option to the current page
}

if(page == "operationsKPI")
{
    breadcrumbs.init();
    breadcrumbs.addBreadCrumbs("Operations KPI Graphs", 'http://localhost/kpiGraphs/operationsGraphs', true);
}

else if (page == "admin")
{
    breadcrumbs.init();
    breadcrumbs.addBreadCrumbs("Administration", 'http://localhost/kpiGraphs/administration', true);
}

else if (page == "dashSettings")
{
    breadcrumbs.init();
    breadcrumbs.addBreadCrumbs("Settings", 'http://localhost/kpiGraphs/dashSettings', true);
}