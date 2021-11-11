function fuel_state_chart_callback(returnString, divID, graph_title)
{
    xValue = [];
    yValue = [];

    returnString.forEach(element => xValue.push(element[0]));
    returnString.forEach(element => yValue.push(Number(element[1]).toFixed(2)));

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
    
    graphwatch = graphwatch + 1;

}