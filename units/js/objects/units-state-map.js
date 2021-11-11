function units_state_map_callback(returnString, divID, graph_title)
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
            type: 'buttons',
            x: 0,
            xanchor: 'left',
            y: 0,
            yanchor: 'top',
            bgcolor: '#A9A9A9',
            bordercolor: '#DCDCDC'

        }],
        title: graph_title,
        dragmode: false,
        geo:{
            scope: 'usa',
            showlakes: true,
            lakecolor: 'rgb(255,255,255)'
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
    //             pts = {'x': data.points[i].location, 'y':data.points[i].z};
    //             //UnitGraphClickEvent(pts);

    //         }
    //     }
    // });
    
    graphwatch = graphwatch + 1;

}

