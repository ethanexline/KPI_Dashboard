function sales_bar_graph_rpm_callback(returnString, divID, graph_title, graphWatch)
{
    xValue = [];
    yValue = [];
    
    returnString.forEach(element => xValue.push(element.origin_state + ' --> ' + element.destination_state));
    returnString.forEach(element => yValue.push(element.rpm));


    var data = [
        {
        x: xValue,
        y: yValue,
        type: 'bar',
        marker: {
            color: '#7c7ce4',
            opacity: 0.95,
            line: {
                color: '#7c7ce4',
                width: 1.5
            }
        }
        }

    ];

    var layout = {
        title: graph_title,
        plot_bgcolor: "black",
        paper_bgcolor: "black",
        xaxis: {
            type:'category',
            automargin: true,
        },
        yaxis: {
            tickprefix:'',
            automargin: true,

        }, 
        margin: { t: 70, b: 10, l: 30, r: 30 },
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
                pts = {'x': data.points[i].x, 'y':data.points[i].y}
                OrderGraphClickEvent(pts);
            }
        }
    });


    // var throttle = false;

    // plot.addEventListener('click', function (evt) {
    // var o = this,
    
    // if (!throttle && evt.detail === 3) {
    //     alert("TRIPLE CLICK MATHA")
    // }
    // });

    graphwatch = graphwatch + 1;

    
}

