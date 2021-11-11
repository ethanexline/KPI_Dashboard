function fuel_line_callback(returnString, divID, title)
{
    xValue = [];
    yValue = [];

    xValue2 = [];
    yValue2 = [];

    returnString.forEach(element => xValue.push(element[0]));
    returnString.forEach(element => yValue.push("$" + Number(element[2]).toFixed(3)));

    returnString.forEach(element => xValue2.push(element[0]));
    returnString.forEach(element => yValue2.push("$" + Number(element[3]).toFixed(3)));

    var data = [
        {
        x: xValue,
        y: yValue,
        mode: 'lines+markers',
        type: 'line',
        name: 'RKE Ave Bulk cost per gallon',
        marker: {
            color: 'rgb(223,27,22)',
            opacity: 0.95,
            line: {
                width: 1.5
            }
        }
        },
        {
        x: xValue2,
        y: yValue2,
        mode: 'lines+markers',
        type: 'line',
        name: 'VA OTR Ave $ per gal',
        marker: {
            color: '#1676DF',
            opacity: 0.95,
            line: {
                width: 1.5
            }
        }
        }
    ];

    var layout = {
    title: title,
    autosize: false,
    height: 550,
    xaxis: {
        type:'category'
    },
    yaxis: {
        tickprefix:'$',
        tick0: 0,
        dtick: 0.05,
        title: {
            text: 'Dollars per Gallon'
        }
    },
    autosize: true,
    font: {
      color: 'White'
    },
    plot_bgcolor: "black",
    paper_bgcolor: "black",
    };

    Plotly.newPlot(divID, data, layout, {responsive: true});
  
    graphwatch = graphwatch + 1;

}