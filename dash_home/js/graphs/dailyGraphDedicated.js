function callback(returnString)
        {
        xValue = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        xValue2 = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        yValue = [];
        yValue2 = [];
        
        returnString[0].forEach(element => yValue.push(element.amount));
        returnString[1].forEach(element => yValue2.push(element.amount));
        
        
        var currentAmt = yValue[yValue.length -1];
        
        const formatter = new Intl.NumberFormat('en-US', {
          style: 'currency',
          currency: 'USD',
          minimumFractionDigits: 2
        })    
            
        while (yValue.length < 7) {
            yValue.push('');
        }

        var data = [
          {
            x: xValue2,
            y: yValue2,
            mode: 'lines+markers',
            type: 'line',
            name: 'Last Week',
            hoverinfo: 'none',
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
          }

        ];

        var layout = {
        plot_bgcolor:"black",
        paper_bgcolor:"black",
        title: '<b>Fleetmaster Dedicated/Spotting Revenue for the Week</b><br>' + formatter.format(currentAmt),
            xaxis: {
                type:'category'
            },
            
            yaxis: {
                tickmode:'linear',
                tickprefix:'$',
                dtick: 25000,
                range:[0,250000]
                
            },
            
            font: {
                size: 24,
                color: 'White'
            },
            shapes: [
                {
                  type: 'line',
                  xref: 'paper',
                  opacity: 0.5,
                  x0: 0,
                  y0: 700000,
                  x1: 1,
                  y1: 700000,
                  line: {
                    color: 'gold',
                    width: 4
                  }
                }
            ]
        };


        Plotly.newPlot('chart', data, layout, {responsive: true});
        }


function httpGetAsync(theUrl, callback)
        {
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() { 
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                    callback(JSON.parse(xmlHttp.responseText));
            }
            xmlHttp.open("GET", theUrl, true); // true for asynchronous 
            xmlHttp.send(null);
        }

httpGetAsync("../KPIGraphs/dash_home/api/v1/daily_revenue_dedicated/102", callback);