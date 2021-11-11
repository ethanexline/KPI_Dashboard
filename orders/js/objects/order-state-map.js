function order_state_map_callback(returnString, divID, graph_title)
{
    xValue = [];
    yValue = [];

    xValue2 = [];
    yValue2 = [];

    returnString[0].forEach(element => xValue.push(element.state));
    returnString[0].forEach(element => yValue.push(element.revenue));

    returnString[1].forEach(element => xValue2.push(element.state));
    returnString[1].forEach(element => yValue2.push(element.revenue));

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
        },
        visible: true
    },
    {
        type: 'choropleth',
        locationmode: 'USA-states',
        locations: xValue2,
        z: yValue2,
        text: xValue2,
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
        },
        visible: false

    }];


    var layout = {
        plot_bgcolor: "black",
        paper_bgcolor: "black",
        font: {
            color: 'White'
        },
        updatemenus: [{
            buttons: [{
                method: 'restyle',
                args: ['visible', [true, false]],
                label: 'Destination'
            }, {
                method: 'restyle',
                args: ['visible', [false, true]],
                label: 'Origin'
            }],
            direction: 'left',
            pad: {'r': 10, 't': 10},
            showactive: true,
            bgcolor: 'rgb(169,169,169)',
            font: {'color': '#000000'},
            type: 'buttons',
            x: 0,
            xanchor: 'left',
            y: 0,
            yanchor: 'top',
        }],
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

