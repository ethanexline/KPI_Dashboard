function units_stacked_bar_graph_callback(returnString, divID, graph_title, graphWatch)
{
    var TheUniqueWeeks = [];

    function uniqueWeeks(yearWeek){
        if(!TheUniqueWeeks.includes(yearWeek)){
            TheUniqueWeeks.push(yearWeek);
        }
    }

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

    xValue8 = [];
    yValue8 = [];
    
    returnString.forEach(function(element) {
        if (element.status == 'R') {
            xValue.push(element.year + "-" + element.week);
            yValue.push(element.count);
            uniqueWeeks(element.year + "-" + element.week);

        }
        else if (element.status == 'D') {
            xValue2.push(element.year + "-" + element.week);
            yValue2.push(element.count);
            uniqueWeeks(element.year + "-" + element.week);
        }
        else if (element.status == 'A') {
            xValue3.push(element.year + "-" + element.week);
            yValue3.push(element.count);
            uniqueWeeks(element.year + "-" + element.week);
        }
        else if (element.status == 'UO') {
            xValue4.push(element.year + "-" + element.week);
            yValue4.push(element.count);
            uniqueWeeks(element.year + "-" + element.week);

        }
        else if (element.status == 'U') {
            xValue5.push(element.year + "-" + element.week);
            yValue5.push(element.count);
            uniqueWeeks(element.year + "-" + element.week);

        }
        else if (element.status == 'S') {
            xValue6.push(element.year + "-" + element.week);
            yValue6.push(element.count);
            uniqueWeeks(element.year + "-" + element.week);

        }
        else if (element.status == 'C') {
            xValue7.push(element.year + "-" + element.week);
            yValue7.push(element.count);
            uniqueWeeks(element.year + "-" + element.week);

        }
        else {
            xValue8.push(element.year + "-" + element.week);
            yValue8.push(element.count);
            uniqueWeeks(element.year + "-" + element.week);

        }
    });

    var data = [
        {
            x: xValue,
            y: yValue,
            type: 'bar',
            name: 'Ready',
            marker: {
                color: 'rgb(0, 128, 0)'
            }
        },
        {
            x: xValue2,
            y: yValue2,
            type: 'bar',
            name: 'Dispatched',
            marker: {
                color: 'rgb(223,27,22)'
            }
        },
        {
            x: xValue3,
            y: yValue3,
            type: 'bar',
            name: 'Available',
            marker: {
                color: 'rgb(0, 128, 255)'
            }
        },
        {
            x: xValue4,
            y: yValue4,
            type: 'bar',
            name: 'Unavailable - Pending Sale',
            marker: {
                color: 'rgb(252, 218, 49)'
            }
        },
        {
            x: xValue5,
            y: yValue5,
            type: 'bar',
            name: 'Unavailable - Other',
        },
        {
            x: xValue6,
            y: yValue6,
            type: 'bar',
            name: 'Shop',
        },
        {
            x: xValue7,
            y: yValue7,
            type: 'bar',
            name: 'Customer',
        },
        {
            x: xValue8,
            y: yValue8,
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
            categoryorder: "array",
            categoryarray: TheUniqueWeeks,
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

