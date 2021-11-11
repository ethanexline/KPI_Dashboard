function sales_pie_graph_callback(returnString, divID, graph_title)
{
    xValue = [];
    yValue = [];
    
    returnString.forEach(element => xValue.push(element.customer));
    returnString.forEach(element => yValue.push(element.revenue));

    var data = [ {
        x: xValue,
        y: yValue,
        type: 'bar',
        marker: {
            color: 'rgb(223,27,22)',
            opacity: 0.95,
            line: {
                color: 'rgb(223,27,22)',
                width: 1.5
            }
        }
    }];
      

    var layout = {
        title: graph_title,
        xaxis: {
            type:'category',
            automargin: true,
        },
        yaxis: {
            tickprefix:'',
            automargin: true,

        }, 	    
        plot_bgcolor: "black",
        paper_bgcolor: "black",
        font: {
            color: 'White'
        }
    };

    //Create the chart
    Plotly.newPlot(divID, data, layout, {responsive: true});

    //Create the listener for clicks
    var plot = document.getElementById(divID);
    plot.on('plotly_click', function(data){
        if(data.event.detail === 3){
        var pts = '';
        for(var i=0; i < data.points.length; i++){
            pts = {'x': data.points[i].label, 'y':data.points[i].value};
            //OrderGraphClickEvent(pts);
        }
    }
    });

    graphwatch = graphwatch + 1;

    
}

