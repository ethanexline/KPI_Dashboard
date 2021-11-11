function callback_daily_revenue(returnString, title, div) {
    xValue = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    xValue2 = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    yValue = [];
    yValue2 = [];

    returnString[0].forEach(element => {
        yValue.push(element.amount);
    });

    returnString[1].forEach(element=> {
        yValue2.push(element.amount);
    });

    var currentAmt = yValue[yValue.length - 1];
 
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    })

    while (yValue.length < 7) {
        yValue.push('');
    }

    var rangeSetting = [0, 1000000];
    var dtickRange = 100000;

    if(title == "Englander Revenue") {
        rangeSetting = [0, 500000];
        dtickRange = 50000;
    }

    if(title == "FLMR Dedicated/Spotting Revenue"){
        rangeSetting = [0,250000];
        dtickRange = 25000;
    }

    if(title == "Brokerage Revenue"){
        rangeSetting = [0,50000];
        dtickRange = 5000;
    }

    var data = [
        {
            x: xValue2,
            y: yValue2,
            mode: 'lines+markers',
            hoverinfo: 'none',
            name: 'Last Week',
            marker: {
                color: 'rgb(0, 128, 255)',
                opacity: 0.95,
                line: {
                    color: 'rgb(0, 128, 255)',
                    width: 1.5
                }
            },
            line: {
                dash: 'dashdot'
            }
        },
        {
            x: xValue,
            y: yValue,
            mode: 'lines+markers+text',
            type: 'line',
            name: 'This Week',
            text: yValue.map(String),
            textposition: 'right',
            texttemplate: '%{y:$,.2f}',
            hoverinfo: 'none',
            marker: {
                color: 'rgb(223,27,22)',
                opacity: 0.95,
                line: {
                    color: 'rgb(223,27,22)',
                    width: 1.5
                }
            }
        },
    ];

    if(title == "FLMR Regional/OTR Revenue") {
        var layout = {
            plot_bgcolor: "black",
            paper_bgcolor: "black",
            title: '<b>' + title + '</b><br>' + formatter.format(currentAmt),
            xaxis: {
                type: 'category'
            },

            yaxis: {
                tickmode: 'linear',
                tickprefix: '$',
                dtick: dtickRange,
                range: rangeSetting

            },

            font: {
                color: 'White'
            },
            shapes: [
                {
                    type: 'line',
                    xref: 'paper',
                    opacity: 0.5,
                    x0: 0,
                    y0: 500000,
                    x1: 1,
                    y1: 500000,
                    line: {
                        color: 'gold',
                        width: 4
                    }
                }
            ]
        }
    }
    else {
        var layout = {
            plot_bgcolor: "black",
            paper_bgcolor: "black",
            title: '<b>' + title + '</b><br>' + formatter.format(currentAmt),
            xaxis: {
                type: 'category'
            },

            yaxis: {
                tickmode: 'linear',
                tickprefix: '$',
                dtick: dtickRange,
                range: rangeSetting

            },

            font: {
                color: 'White'
            }
        }
    }

    Plotly.newPlot(div, data, layout, { responsive: true });
}