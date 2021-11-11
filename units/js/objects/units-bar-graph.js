function units_bar_graph_callback(returnString, divID, graph_title)
{
    xValue = [];
    yValue = [];

    returnString.forEach(element => xValue.push(element.year + "-" + element.week));
    returnString.forEach(element => yValue.push(element.amount));

    var data = [
        {
        x: xValue,
        y: yValue,
        type: 'bar',
        marker: {
            color: 'rgb(223,27,22)'
          }
        }
    ];

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
    //             pts = {'x': data.points[i].x, 'y':data.points[i].y}
    //             UnitGraphClickEvent(pts);

    //         }
    //     }
    // });

    graphwatch = graphwatch + 1;

    
}

