function order_h_bar_graph_callback(returnString, divID, graph_title)
{
    xValue = [];
    yValue = [];

    xValue2 = [];
    yValue2 = [];

    returnString[0].forEach(element => xValue.push(element.year + "-" + element.week));
    returnString[0].forEach(element => yValue.push(element.amount));

    returnString[1].forEach(element => xValue2.push(element.year + "-" + element.week));
    returnString[1].forEach(element => yValue2.push(element.amount));

    var data = [
        {
        x: xValue,
        y: yValue,
        type: 'bar',
        name: 'Spotting',
        marker: {
            color: 'rgb(223,27,22)'
          }
        }
        ,
        {
            x: xValue2,
            y: yValue2,
            type: 'bar',
            name: 'Other',
            marker: {
                color: 'rgb(128, 128, 128)'
              }

        }
    ];

    var layout = {
        plot_bgcolor: "black",
        paper_bgcolor: "black",
        title: graph_title,
        autosize: true,
        xaxis: {
            type:'category',
            automargin: true,
        },
        yaxis: {
            tickprefix:'',
            automargin: true,

        }, 
        barmode: 'stack',
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

    graphwatch = graphwatch + 1;

    
}

