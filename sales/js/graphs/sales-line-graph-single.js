function sales_line_graph_single_callback(returnString, divID, title)
{
  xValue = [];
  yValue = [];


    returnString.forEach(element => yValue.push(element.rpm));
    returnString.forEach(element => xValue.push(element.year + '-' + element.WeekOfYear));
    
    var data = [
      {
        x: xValue,
        y: yValue,
        mode: 'lines+markers',
        type: 'line',
        texttemplate: '%{y:$,.2f}',
        name: 'BROKER',
        marker: {
            color: 'rgb(156, 39, 176)',
            opacity: 0.95,
            line: {
              color: 'rgb(156, 39, 176)',
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
  });
  
  
  graphwatch = graphwatch + 1;

}


