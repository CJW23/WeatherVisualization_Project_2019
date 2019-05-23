<!DOCTYPE html>
<meta charset="utf-8">
<style>
  body {
    font: 10px sans-serif;
    margin: 0;
  }

  path.line {
    fill: none;
    stroke: #666;
    stroke-width: 1.5px;
  }

  path.area {
    fill: #e7e7e7;
  }

  .axis {
    shape-rendering: crispEdges;
  }

  .x.axis line {
    stroke: #fff;
  }

  .x.axis .minor {
    stroke-opacity: .5;
  }

  .x.axis path {
    display: none;
  }

  .y.axis line,
  .y.axis path {
    fill: none;
    stroke: #000;
  }

  .guideline {
    margin-right: 100px;
    float: right;
  }
</style>

<body>
  <label class="guideline">
    Show Guideline & Curtain
    <input type="checkbox" id="show_guideline" />
  </label>
  <svg></svg>
  <script src="http://d3js.org/d3.v3.min.js"></script>
  <?php
    include "getdata.php";  //데이터 불러오는 함수 php
    $data = CityYearAvg($collection, $code);
    $datas = CityYearAvg($collection, $code2);
    $datas2 = CityYearAvg($collection, $code3);
    $datas3 = CityYearAvg($collection, $code4);
  ?>
  <script>
    var num = <?php echo $num ?> 
    function Print(num){
      console.log(num);
      var margin = { top: 80, right: 80, bottom: 80, left: 80 },
      width = 1800 - margin.left - margin.right,
      height = 700 - margin.top - margin.bottom;

    var parse = d3.time.format("%Y%i%o").parse;

    // Scales and axes. Note the inverted domain for the y-scale: bigger is up!
    var x = d3.time.scale().range([0, width]),
      y = d3.scale.linear().range([height, 0]),
      xAxis = d3.svg.axis().scale(x).tickSize(-12).tickSubdivide(true),
      yAxis = d3.svg.axis().scale(y).ticks(5).orient("right");

    var line = d3.svg.line()    /////////////////////////////////
    .interpolate("monotone")
    .x(function (d) { return x(d.Year); })
    .y(function (d) {return y(d.CityYearAvgTemp); });
    
    // Filter to one symbol; the S&P 500.
    var datas = <?php echo json_encode($data)?>;
    var datas2 = <?php echo json_encode($datas)?>;
    var datas3 = <?php echo json_encode($datas2)?>;
    var datas4 = <?php echo json_encode($datas3)?>;

    var tmp2 = Array();
      
      x.domain([datas[0].Year, datas[datas.length - 1].Year]);////////////////////////////
      y.domain([11, 16]);    ////////////////////////////////

      // Add an SVG element with the desired dimensions and margin.
      var svg = d3.select("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")")

      // Add the clip path.
      svg.append("clipPath")
        .attr("id", "clip")
        .append("rect")
        .attr("width", width)
        .attr("height", height);

      // Add the x-axis.
      svg.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + height + ")")
        .call(xAxis);

      // Add the y-axis.
      svg.append("g")
        .attr("class", "y axis")
        .attr("transform", "translate(" + width + ",0)")
        .call(yAxis);


      var colors = d3.scale.category10();
      if(num == 1){
        svg.selectAll('.line')
        .data([datas])    /////////////////////////////////////
        .enter()
        .append('path')
        .attr('class', 'line')
        .style('stroke', function (d) {
          return colors(Math.random() * 50);
        })
        .attr('clip-path', 'url(#clip)')
        .attr('d', function (d) {
          return line(d);
        });
      }
      else if(num == 2){
        svg.selectAll('.line')
        .data([datas, datas2])    /////////////////////////////////////
        .enter()
        .append('path')
        .attr('class', 'line')
        .style('stroke', function (d) {
          return colors(Math.random() * 50);
        })
        .attr('clip-path', 'url(#clip)')
        .attr('d', function (d) {
          return line(d);
        });
      }
      else if(num == 3){
        svg.selectAll('.line')
        .data([datas, datas2, datas3])    /////////////////////////////////////
        .enter()
        .append('path')
        .attr('class', 'line')
        .style('stroke', function (d) {
          return colors(Math.random() * 50);
        })
        .attr('clip-path', 'url(#clip)')
        .attr('d', function (d) {
          return line(d);
        });
      }
      else if(num == 4){
        svg.selectAll('.line')
        .data([datas, datas2, datas3, datas4])    /////////////////////////////////////
        .enter()
        .append('path')
        .attr('class', 'line')
        .style('stroke', function (d) {
          return colors(Math.random() * 50);
        })
        .attr('clip-path', 'url(#clip)')
        .attr('d', function (d) {
          return line(d);
        });
      }
      
        
        

      /* Add 'curtain' rectangle to hide entire graph */
      var curtain = svg.append('rect')
        .attr('x', -1 * width)
        .attr('y', -1 * height)
        .attr('height', height)
        .attr('width', width)
        .attr('class', 'curtain')
        .attr('transform', 'rotate(180)')
        .style('fill', '#ffffff')

      /* Optionally add a guideline */
      var guideline = svg.append('line')
        .attr('stroke', '#333')
        .attr('stroke-width', 0)
        .attr('class', 'guide')
        .attr('x1', 1)
        .attr('y1', 1)
        .attr('x2', 1)
        .attr('y2', height)

      /* Create a shared transition for anything we're animating */
      var t = svg.transition()
        .delay(750)
        .duration(6000)
        .ease('linear')
        .each('end', function () {
          d3.select('line.guide')
            .transition()
            .style('opacity', 0)
            .remove()
        });

      t.select('rect.curtain')
        .attr('width', 0);
      t.select('line.guide')
        .attr('transform', 'translate(' + width + ', 0)')

      d3.select("#show_guideline").on("change", function (e) {
        guideline.attr('stroke-width', this.checked ? 1 : 0);
        curtain.attr("opacity", this.checked ? 0.75 : 1);
      });  
    }
    Print(num);
    
  </script>