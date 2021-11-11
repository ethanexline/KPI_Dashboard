function units_line_callback(returnString, divID, title)
{
  xValue = [];
  yValue = [];

  if (title === "<b>Idle Percent by Week</b>") {
    returnString.forEach(element => yValue.push((100 * parseFloat(element.idle)).toFixed(1)));
    returnString.forEach(element => xValue.push(element.year + '-' + element.week));

    var data = [
      {
        x: xValue,
        y: yValue,
        mode: 'lines+markers',
        type: 'line',
        marker: {
          color: 'rgb(0,128,0)',
          opacity: 0.95,
          line: {
            color: 'rgb(0,128,0)',
            width: 1.5
          }
        }
      }
    ];
  } else if (title === "<b>Expert Fuel Compliance % by Week</b>") {
    returnString.forEach(element => yValue.push((100 * parseFloat(element.compliant_percent)).toFixed(1)));
    returnString.forEach(element => xValue.push(element.year + '-' + element.week));

    var data = [
      {
        x: xValue,
        y: yValue,
        mode: 'lines+markers',
        type: 'line',
        marker: {
          color: 'rgb(255,165,0)',
          opacity: 0.95,
          line: {
            color: 'rgb(255,165,0)',
            width: 1.5
          }
        }
      }

    ];
  }
  else if (title === "<b>ECM MPG by Week</b>") {
    returnString.forEach(element => yValue.push(parseFloat(element.mpg).toFixed(2)));
    returnString.forEach(element => xValue.push(element.year + '-' + element.week));

    var data = [
      {
        x: xValue,
        y: yValue,
        mode: 'lines+markers',
        type: 'line',
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
  } else if (title === "<b>Actual MPG by Week</b>"){
      returnString.forEach(element => yValue.push(parseFloat(element.mpg).toFixed(2)));
      returnString.forEach(element => xValue.push(element.year + '-' + element.week));

      var data = [
        {
          x: xValue,
          y: yValue,
          mode: 'lines+markers',
          type: 'line',
          marker: {
            color: 'rgb(0, 128, 255)',
            opacity: 0.95,
            line: {
              color: 'rgb(0, 128, 255)',
              width: 1.5
            }
          }
        }
      ]
  };

  var layout = {
    plot_bgcolor: "black",
    paper_bgcolor: "black",
    font: {
        color: 'White'
    },
    title: title,
    xaxis: {
        type:'category'
    },
    autosize: true
  };
      



  Plotly.newPlot(divID, data, layout, {responsive: true});
  

  // var plot = document.getElementById(divID);
  // plot.on('plotly_click', function(data){
  //   if(data.event.detail === 3){
  //       var pts = '';
  //       for(var i=0; i < data.points.length; i++){
  //           pts = {'x': data.points[i].x, 'y':data.points[i].y};
  //           UnitGraphClickEvent(pts);

  //       }
  //     }
  // });
  
  
  graphwatch = graphwatch + 1;

}


