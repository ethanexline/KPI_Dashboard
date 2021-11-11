function fuel_bar_graph_callback(returnString, divID, graph_title) 
{
    if(graph_title == "<b>Fuel PPG by Day</b>")
    {
        xValue = [];
        yValue = [];

        xValue2 = [];
        yValue2 = [];

        xValue3 = [];
        yValue3 = [];

        returnString.forEach(element => xValue.push(element[0]));
        returnString.forEach(element => yValue.push(Number(element[1]).toFixed(3)));

        returnString.forEach(element => xValue2.push(element[0]));
        returnString.forEach(element => yValue2.push(Number(element[2]).toFixed(3)));

        returnString.forEach(element => xValue3.push(element[0]));
        returnString.forEach(element => yValue3.push(Number(element[3]).toFixed(3)));

        var data = [
            {
            x: xValue,
            y: yValue,
            name: "Tractor PPG",
            type: 'bar',
            },
            {
            x: xValue2,
            y: yValue2,
            name: "Reefer PPG",
            type: 'bar',
            },
            {
            x: xValue3,
            y: yValue3,
            name: "DEF PPG",
            type: 'bar',
            }
        ];

        var layout = {
            plot_bgcolor: "black",
            paper_bgcolor: "black",
            font: {
                color: 'White'
            },
            title: graph_title,
            barmode: 'group',
            xaxis: {
                type: 'category',
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
    }

    else if (graph_title == "<b>POS Discount</b>") 
    {
        xValue = [];
        yValue = [];

        returnString.forEach(element => xValue.push(element[0]));
        returnString.forEach(element => yValue.push(Number(element[2]).toFixed(2)));

        var data = [
            {
            x: xValue,
            y: yValue,
            type: 'bar',
            marker: {
                color: '#7c7ce4'
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
            barmode: 'group',
            xaxis: {
                type: 'category',
                automargin: true,
            },
            yaxis: {
                tickprefix:'$',
                automargin: true,

            }, 
            margin: { t: 70, b: 10, l: 30, r: 30 }

        };

        //Create the chart
        Plotly.newPlot(divID, data, layout, {responsive: true});
    }

    else if (graph_title == "<b>Avg. Gallons per Day</b>") 
    {
        xValue = [];
        yValue = [];

        returnString.forEach(element => xValue.push(element[0]));
        returnString.forEach(element => yValue.push(Number(element[2]).toFixed(2)));

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
            barmode: 'group',
            xaxis: {
                type: 'category',
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
    }

    else if (graph_title == "<b>Avg. Discount per Gallon</b>") 
    {
        xValue = [];
        yValue = [];

        returnString.forEach(element => xValue.push(element[0]));
        returnString.forEach(element => yValue.push(Number(element[2]).toFixed(4)));

        var data = [
            {
            x: xValue,
            y: yValue,
            type: 'bar',
            marker: {
                color: 'rgb(0, 128, 0)'
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
            barmode: 'group',
            xaxis: {
                type: 'category',
                automargin: true,
            },
            yaxis: {
                tickprefix:'$',
                title: {
                    text: 'Dollars per Gallon'
                },
                automargin: true,

            }, 
            margin: { t: 70, b: 10, l: 30, r: 30 }

        };

        //Create the chart
        Plotly.newPlot(divID, data, layout, {responsive: true});
    }


    graphwatch = graphwatch + 1;
}