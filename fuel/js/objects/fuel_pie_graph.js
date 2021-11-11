function fuel_pie_graph_callback(returnString, divID, graph_title)
{

    if (graph_title == "<b>Fuel Purchases by Chain</b>") {
        xValue = [];
        yValue = [];

        returnString.forEach(element => xValue.push(element[0]));
        returnString.forEach(element => yValue.push(Number(element[1]).toFixed(2)));

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
        }

        //Create the chart
        Plotly.newPlot(divID, data, layout, {responsive: true});
    }

    else if (graph_title == "<b>Fuel Purchases by Terminal</b>") {
        xValue = [];
        yValue = [];

        returnString.forEach(element => xValue.push(element[0]));
        returnString.forEach(element => yValue.push(Number(element[1]).toFixed(2)));

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
        }

        //Create the chart
        Plotly.newPlot(divID, data, layout, {responsive: true, displayModeBar: false});
    }

    else if (graph_title == "<b>Fuel Purchases by Fuel Type</b>") {
        xValue = ["Tractor", "Reefer", "DEF", "Cash Advance", "Other"];
        yValue = [];

        returnString.slice(0,9).forEach(element => yValue.push(Number(element[0]).toFixed(2)));
        returnString.slice(0,9).forEach(element => yValue.push(Number(element[1]).toFixed(2)));
        returnString.slice(0,9).forEach(element => yValue.push(Number(element[2]).toFixed(2)));
        returnString.slice(0,9).forEach(element => yValue.push(Number(element[3]).toFixed(2)));
        returnString.slice(0,9).forEach(element => yValue.push(Number(element[4]).toFixed(2)));

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
        }

        //Create the chart
        Plotly.newPlot(divID, data, layout, {responsive: true, displayModeBar: false});
    }
    graphwatch = graphwatch + 1;
}