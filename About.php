<?php session_start(); ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About</title>
    <?php
    require_once 'config.php';
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli->errno){
        print('There was an error in connecting to the database:');
        print($mysqli->error);
        exit();
    }
    ?>
</head>



<body>
   <h1>What is StockFu?</h1>
   <p>StockFu allows anyone to explore the stock market in an accessible, customizable, and interactive way. The idea is to let you make your own profile of stock charts, which could vary on company, date range, and more. You can create and customize these charts, and re-visit them later on. You can also compare and contrast companies on one stock chart, or with the same company across different date ranges. Whether you're a beginner or expert, student or professional, you can get started and explore the world of stocks!</p> <br>
   <p>StockFu was created by Andrew Cadwallader in 2016. He sought to integrate web technologies with the world of stocks in order to give his audience, primarily college students, a way to easily customize and explore stock charts. </p>

   <h3>An example chart is shown below, for Apple data</h3>
   <svg id="exampleChart" width="1000px" height="500px"></svg>
    <script type="text/javascript">
    //Same example using Ajax/JQuery
    $.getJSON("https://www.quandl.com/api/v3/datasets/WIKI/AAPL.json?end_date=2015-05-28&api_key=KDzspapgf7Mv2zbUmTgd", function(result){
        var data = result["dataset"];
        var stockData = data["data"];
        var demo = d3.select("#exampleChart");
        var maxDate = new Date(stockData[0][0]);
        var minDate = new Date(stockData[stockData.length-1][0]);
        var height = 400;
        var width = 900;
        var margins = {
            top: 20,
            right: 20,
            bottom: 20,
            left: 50
        };

        //Sets up x and y axis
        var xScale = d3.time.scale().range([margins.left,width-margins.right]).domain([minDate,maxDate]);
        var yScale = d3.scale.linear().range([height-margins.top,margins.bottom]).domain([0,600]);
        xAxis = d3.svg.axis().scale(xScale);
        yAxis = d3.svg.axis().scale(yScale).orient("left");
        //Orients axes
        demo.append("svg:g").attr("transform","translate(0," + (height - margins.bottom) + ")").call(xAxis);
        demo.append("svg:g").attr("transform","translate(" + margins.left + ",6)").call(yAxis);

        //Generates lines using open stock price
        var lineGen = d3.svg.line()
        .x(function(d) {
            return xScale(new Date(d[0]));
        })
        .y(function(d) {
            return yScale(d[1]);
        });

        //Appends line chart to svg with dank attributes
        demo.append('svg:path')
        .attr('d',lineGen(stockData))
        .attr('stroke','green')
        .attr('stroke-width',2)
        .attr('fill','none');

        //Adds x-axis label
        demo.append("text").attr("x",width/2).attr("y",height + 30).style("text-anchor","middle").style("font-size",16).text("Date");

        //Adds y-axis label
        demo.append("text").attr("transform","rotate(-90)").attr("y",10).attr("x",-height/2).style("text-anchor","middle").text("Open Stock Price");
    });
    </script>
    <footer>
        <!-- Tell people that this is my website do not steal -->
        <div id="copyright">
            Copyright &copy; 2016 Kevin Guo. All rights reserved.
        </div>
    </footer> 
    
</body>
</html>