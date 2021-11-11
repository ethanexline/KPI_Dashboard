class breadcrumb
{   
    init()
    {
        var breadcrumbContainer = document.getElementById("breadcrumb-container");
        breadcrumbContainer.innerHTML = '<li class="breadcrumb-item"><a href="http://localhost/KPIGraphs">Home</a></li>';
    }

    addBreadCrumbs(location, url, active)
    {
        var breadcrumbContainer = document.getElementById("breadcrumb-container");
        var class_ = "breadcrumb-item";
        var aria = '';
        
        if(active === true)
        {
            class_ += " active";
            aria == 'aria-current="page"';
            breadcrumbContainer.innerHTML += '<li class="' + class_ + '" ' + aria + '>' + location +'</li>';
        }
        
        else
        {
            breadcrumbContainer.innerHTML += '<li class="' + class_ + '" ' + aria + '><a href="'+ url + '">' + location +'</a></li>'; 
        }
    }
}

var breadcrumbs = new breadcrumb();
breadcrumbs.init();