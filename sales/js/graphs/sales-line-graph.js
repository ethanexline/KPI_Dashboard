function sales_line_graph_callback(returnString, divID, title)
{
  if(title == "<b>Commodity Code BROKER <br> vs All Others Revenue by Week</b>")
  {
    xValue = [];
    yValue = [];

    xValue2 = [];
    yValue2 = [];
    
    xValue3 = [];
    yValue3 = [];
    
      returnString[0].forEach(element => yValue.push(element.revenue));
      returnString[0].forEach(element => xValue.push(element.year + '-' + element.week));
      
      returnString[1].forEach(element => yValue2.push(element.revenue));
      returnString[0].forEach(element => xValue2.push(element.year + '-' + element.week));
    
      returnString[2].forEach(element => yValue3.push(element.revenue));
      returnString[0].forEach(element => xValue3.push(element.year + '-' + element.week));

      var data = [
        {
          x: xValue,
          y: yValue,
          mode: 'lines+markers',
          type: 'line',
          texttemplate: '%{y:$,.2f}',
          name: 'BROKER',
          // marker: {
          //     color: 'rgb(192, 57, 43)',
          //     opacity: 0.95,
          //     line: {
          //       color: 'rgb(192, 57, 43)',
          //       width: 1.5
          //     }
          // }
        },
        {
          x: xValue2,
          y: yValue2,
          mode: 'lines+markers',
          type: 'line',
          texttemplate: '%{y:$,.2f}',
          name: 'Not BROKER',
          // marker: {
          //     color: 'rgb(41, 128, 185)',
          //     opacity: 0.95,
          //     line: {
          //       color: 'rgb(41, 128, 185)',
          //       width: 1.5
          //     }
          // }
        },
        {
          x: xValue3,
          y: yValue3,
          mode: 'lines+markers',
          type: 'line',
          texttemplate: '%{y:$,.2f}',
          name: 'All',
          // marker: {
          //     color: 'rgb(236, 240, 241)',
          //     opacity: 0.95,
          //     line: {
          //       color: 'rgb(236, 240, 241)',
          //       width: 1.5
          //     }
          // }
        }
      ];

    var layout = {
      title: title,
      xaxis: {
          type:'category'
      },
      autosize: true,
      font: {
        color: 'White'
      },
      plot_bgcolor: "black",
      paper_bgcolor: "black",
    };
        



    Plotly.newPlot(divID, data, layout, {responsive: true});
    

    var plot = document.getElementById(divID);
    plot.on('plotly_click', function(data){
      if(data.event.detail === 3){
        var pts = '';
        for(var i=0; i < data.points.length; i++){
            pts = {'x': data.points[i].x, 'y':data.points[i].y};
            OrderGraphClickEvent(pts);

        }
      }
    })
  }
  else if (title == "<b>Top 5 Customer Revenue by Week</b>")
  {
    var companies = [];

    returnString.forEach( function(element) {
      if(!companies.includes(element.topCustomer)) {
        companies.push(element.topCustomer);
      }
    });

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

    returnString.forEach( function(element) {
      if(element.topCustomer == companies[0]) {
        yValue.push(element.topRevenue);
        xValue.push(element.week);
      }

      else if(element.topCustomer == companies[1]) {
        yValue2.push(element.topRevenue);
        xValue2.push(element.week);
      } 

      else if(element.topCustomer == companies[2]) {
        yValue3.push(element.topRevenue);
        xValue3.push(element.week);
      } 

      else if(element.topCustomer == companies[3]) {
        yValue4.push(element.topRevenue);
        xValue4.push(element.week);
      } 

      else if(element.topCustomer == companies[4]) {
        yValue5.push(element.topRevenue);
        xValue5.push(element.week);
      } 
    });


    var data = [
      {
        x: xValue,
        y: yValue,
        mode: 'lines+markers',
        type: 'line',
        texttemplate: '%{y:$,.2f}',
        name: companies[0],
        marker: {
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
        texttemplate: '%{y:$,.2f}',
        name: companies[1],
        marker: {
            opacity: 0.95,
            line: {
              width: 1.5
            }
        }
      },
      {
        x: xValue3,
        y: yValue3,
        mode: 'lines+markers',
        type: 'line',
        texttemplate: '%{y:$,.2f}',
        name: companies[2],
        marker: {
            opacity: 0.95,
            line: {
              width: 1.5
            }
        }
      },
      {
        x: xValue4,
        y: yValue4,
        mode: 'lines+markers',
        type: 'line',
        texttemplate: '%{y:$,.2f}',
        name: companies[3],
        marker: {
            opacity: 0.95,
            line: {
              width: 1.5
            }
        }
      },
      {
        x: xValue5,
        y: yValue5,
        mode: 'lines+markers',
        type: 'line',
        texttemplate: '%{y:$,.2f}',
        name: companies[4],
        marker: {
            opacity: 0.95,
            line: {
              width: 1.5
            }
        }
      }
    ];

    var layout = {
      title: title,
      xaxis: {
          type:'category'
      },
      autosize: true,
      font: {
        color: 'White'
      },
      plot_bgcolor: "black",
      paper_bgcolor: "black",
    };
        



    Plotly.newPlot(divID, data, layout, {responsive: true});
    

    var plot = document.getElementById(divID);
    plot.on('plotly_click', function(data){
      if(data.event.detail === 3){
        var pts = '';
        for(var i=0; i < data.points.length; i++){
            pts = {'x': data.points[i].x, 'y':data.points[i].y};
            OrderGraphClickEvent(pts);

        }
      }
    })

  }
  
  
  graphwatch = graphwatch + 1;

}


