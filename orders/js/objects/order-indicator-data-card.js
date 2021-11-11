function order_indicator_data_card(returnString, divID, graph_title)
{   

  if (graph_title == "<b>Rate Per Mile</b>") {
    var data = [
      {
        domain: { x: [0, 1], y: [0, 2] },
        value: returnString[0].amount,
        title: { text: "<b>Rate Per Mile</b> <br> (Order Miles)" },
        type: "indicator",
        mode: "gauge+number+delta",
        delta: { reference: returnString[0].average },
        gauge: {
          axis: { range: [null, returnString[0].amount] },
          steps: [
            { range: [0, 250], color: "lightgray" },
            { range: [250, 400], color: "gray" }
          ],
          threshold: {
            line: { color: "red", width: 4 },
            thickness: 0.75,
            value: returnString[0].average
          }
        }
      }
    ]
  }
  else {
    var data = [
      {
        domain: { x: [0, 1], y: [0, 2] },
        value: returnString[0].deadhead * 100,
        title: { text: "<b>Deadhead Percentage</b>" },
        type: "indicator",
        mode: "gauge+number+delta",
        delta: { reference: returnString[0].placeholder},
        gauge: {
          axis: { range: [null, returnString[0].deadhead * 100] },
          steps: [
            { range: [0, 250], color: "lightgray" },
            { range: [250, 400], color: "gray" }
          ],
          threshold: {
            line: { color: "red", width: 4 },
            thickness: 0.75,
            value: returnString[0].placeholder
          }
        }
      }
    ]
  };
  
  var layout = 
  { 
    margin: { t: 70, b: 10, l: 30, r: 30 },
    plot_bgcolor: "black",
    paper_bgcolor: "black",
    font: {
      color: 'White'
   }
   
  };

      
  Plotly.newPlot(divID, data, layout, {responsive: true});

    //Create the chart
    // Plotly.newPlot(divID, data, layout,);
  var plot = document.getElementById(divID);
  
  graphwatch = graphwatch + 1;

    
}

