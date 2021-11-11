function sales_map_callback(returnString, divID, graph_title)
{
    xValue = [];
    yValue = [];

    console.log(returnString);
    returnString.forEach(element => xValue.push(element.origin_state));
    returnString.forEach(element => yValue.push(element.revenue));


    var data = [{
        type: 'choropleth',
        locationmode: 'USA-states',
        locations: xValue,
        z: yValue,
        text: xValue,
        hoverinfo: 'location+z',
        colorbar: {
            title: 'USD',
            thickness: 5
        },
        marker: {
            line:{
                color: 'rgb(255,255,255)',
                width: 2
            }
        }    
    }];


    var layout = {
        plot_bgcolor: "black",
        paper_bgcolor: "black",
        font: {
            color: 'White'
        },
        title: graph_title,
        dragmode: false,
        geo:{
            style: "dark",
            scope: 'usa',
            showlakes: true,
            lakecolor: "white",
            bgcolor: 'rgba(0,0,0,0)'
        },
        margin: { t: 70, b: 10, l: 30, r: 30 },
      
    };

    //Create the chart
    Plotly.newPlot(divID, data, layout, {responsive: true});

    //Create the listener for clicks
    var plot = document.getElementById(divID);
    plot.on('plotly_click', function(data){
        var pts = '';
        for(var i=0; i < data.points.length; i++){
            pts = {'x': data.points[i].location, 'y':data.points[i].z};
            //OrderGraphClickEvent(pts);

        }
    });
    
    graphwatch = graphwatch + 1;

}

