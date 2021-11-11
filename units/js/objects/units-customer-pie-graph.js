function units_cust_pie_graph_callback(returnString, divID, graph_title)
{
    xValue = [];
    yValue = [];
    
    returnString.slice(0,9).forEach(element => xValue.push(element.customer));
    returnString.slice(0,9).forEach(element => yValue.push(element.amount));

    var data = [{
        values: yValue,
        labels: xValue,
        type: 'pie',
        hole: .4
      }];
      

    var layout = {
        plot_bgcolor: "black",
        paper_bgcolor: "black",
        font: {
            color: 'White'
        },
        title: graph_title,
        xaxis: {
            type:'category',
            automargin: true,
        },
        yaxis: {
            tickprefix:'',
            automargin: true,

        }, 
        showlegend: true,
	    legend: {"orientation": "h", font: {size: 8}},
        margin: { t: 70, b: 10, l: 30, r: 30 }
        
    };

    //Create the chart
    Plotly.newPlot(divID, data, layout, {responsive: true});

    //Create the listener for clicks
    // var plot = document.getElementById(divID);
    // plot.on('plotly_click', function(data){
    //     if(data.event.detail === 3){
    //         var pts = '';
    //         for(var i=0; i < data.points.length; i++){
    //             pts = {'x': data.points[i].label, 'y':data.points[i].value};
    //             //UnitGraphClickEvent(pts);
    //         }
    //     }
    // });

    graphwatch = graphwatch + 1;

    
}

