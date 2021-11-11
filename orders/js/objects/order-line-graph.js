function order_line_callback(returnString, divID, title)
{
  xValue = [];
  yValue = [];

  if (title == "<b>Loaded Miles by Week</b>") {
    returnString.forEach(element => yValue.push(element.miles));
    returnString.forEach(element => xValue.push(element.year + '-' + element.week));

    var currentAmt = yValue[yValue.length -1];

    const formatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 2
    })
        
    while (yValue.length < 24) {
        yValue.push('');
    }

    var data = [
      {
        x: xValue,
        y: yValue,
        mode: 'lines+markers',
        type: 'line',
        texttemplate: '%{y:$,.2f}',
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
  }
    else {
      returnString.forEach(element => yValue.push(element.amount));
      returnString.forEach(element => xValue.push(element.dayName));

      var currentAmt = yValue[yValue.length -1];

      const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
      })

      var data = [
        {
          x: xValue,
          y: yValue,
          mode: 'lines+markers',
          type: 'line',
          texttemplate: '%{y:$,.2f}',
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
    }

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
  });
  
  
  graphwatch = graphwatch + 1;

}


