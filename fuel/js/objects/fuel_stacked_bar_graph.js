function fuel_stacked_bar_graph_callback(returnString, divID, graph_title) 
{
    if( graph_title == "<b>Fuel Purchases by Day</b>")
    {
        xValue = [];
        yValue = [];

        xValue2 = [];
        yValue2 = [];

        xValue3 = [];
        yValue3 = [];

        xValue4 = [];
        yValue4 = [];

        xValue5 = [];
        yValue5 = [];
        
        returnString.forEach(function(element) {

            xValue.push(element[0]);
            yValue.push(Number(element[1]).toFixed(2));

            xValue2.push(element[0]);
            yValue2.push(Number(element[2]).toFixed(2));

            xValue3.push(element[0]);
            yValue3.push(Number(element[3]).toFixed(2));

            xValue4.push(element[0]);
            yValue4.push(Number(element[4]).toFixed(2));

            xValue5.push(element[0]);
            yValue5.push(Number(element[5]).toFixed(2));

        });

        var data = [
            {
                x: xValue,
                y: yValue,
                type: 'bar',
                name: 'Tractor Purchases',
            },
            {
                x: xValue2,
                y: yValue2,
                type: 'bar',
                name: 'Reefer Purchases',
            },
            {
                x: xValue3,
                y: yValue3,
                type: 'bar',
                name: 'DEF Purchases',
            },
            {
                x: xValue4,
                y: yValue4,
                type: 'bar',
                name: 'Cash Advance',
            },
            {
                x: xValue5,
                y: yValue5,
                type: 'bar',
                name: 'Other',
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
            margin: { t: 70, b: 10, l: 30, r: 30 },
            barmode: 'stack'
        };

        //Create the chart
        Plotly.newPlot(divID, data, layout, {responsive: true});
    }
    else if (graph_title == "<b>Volume of Discounted Fuel Stops by Chain</b>")
    {
        xValue = [];
        yValue = [];

        xValue2 = [];
        yValue2 = [];

        xValue3 = [];
        yValue3 = [];

        xValue4 = [];
        yValue4 = [];

        xValue5 = [];
        yValue5 = [];

        xValue6 = [];
        yValue6 = [];

        xValue7 = [];
        yValue7 = [];
        
        returnString.forEach(function(element) {

            if (element[2] == "Pilot\/FlyJ") {
                xValue.push(element[0]);
                yValue.push(Number(element[3]).toFixed(2));
                
                xValue6.push(element[0]);
                yValue6.push(Number(element[4]).toFixed(2));

                xValue7.push(element[0]);
                yValue7.push(Number(element[5]).toFixed(2));
            }

            else if (element[2] == "Loves") {
                xValue2.push(element[0]);
                yValue2.push(Number(element[3]).toFixed(2));
            }

            else if (element[2] == "TA\/Petro") {
                xValue3.push(element[0]);
                yValue3.push(Number(element[3]).toFixed(2));

            }

            else if (element[2] == "Speedway") {
                xValue4.push(element[0]);
                yValue4.push(Number(element[3]).toFixed(2));
            }

            else if (element[2] == "Pilot\/One9") {
                xValue5.push(element[0]);
                yValue5.push(Number(element[3]).toFixed(2));
            }
        });

        var data = [
            {
                x: xValue,
                y: yValue,
                type: 'bar',
                name: 'Pilot/Flying J'
            },
            {
                x: xValue2,
                y: yValue2,
                type: 'bar',
                name: 'Loves'
            },
            {
                x: xValue3,
                y: yValue3,
                type: 'bar',
                name: 'TA/Petro'
            },
            {
                x: xValue4,
                y: yValue4,
                type: 'bar',
                name: 'Speedway'
            },
            {
                x: xValue5,
                y: yValue5,
                type: 'bar',
                name: 'Pilot/One9'
            },
            {
                x: xValue6,
                y: yValue6,
                type: 'bar',
                name: 'Bulk'
            },
            {
                x: xValue7,
                y: yValue7,
                type: 'bar',
                name: 'Other - Non-Discounted'
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
            margin: { t: 70, b: 10, l: 30, r: 30 },
            barmode: 'stack'
        };

        //Create the chart
        Plotly.newPlot(divID, data, layout, {responsive: true});
    }

    graphwatch = graphwatch + 1;
}