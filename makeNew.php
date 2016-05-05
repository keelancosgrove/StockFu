<?php 
error_reporting(E_ALL & ~E_NOTICE);
if (!isset($_SESSION)){
    session_start();
}
if (!isset($_SESSION['logged_user'])){
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />	
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.min.js"></script>
    <script src="jquery-csv-master/src/jquery.csv.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
    var companyMap;
    var reversedMap;
    var companyNames = [];
    var completed = false;
    $(function () {

        // Adds datepicker calendar feature to the following input fields
        $("#startDatePicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            onSelect: function (selected, inst) {
                // Ensures that end date is after start date
                var dt = new Date(selected);
                dt.setDate(dt.getDate() + 1);
                $("#endDatePicker").datepicker("option","minDate",dt);
            }
        });
        $("#endDatePicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            onSelect: function(selected, inst) {
                // Ensures that start date is before end date
                var dt = new Date(selected);
                dt.setDate(dt.getDate() - 1);
                $("#startDatePicker").datepicker("option","maxDate",selected);
            }
        });

        // Reads csv of company data and generates maps of company codes to company names
        function returnCompanyMap(callback){
            $.ajax({
            type: "GET",
            url: "WIKI-datasets-codes.csv",
            dataType: "text",
            success: function(data) {

                // Generates map of company names to company codes, and reversed map
                var companyMap = new Map();
                var reversedMap = new Map();
                var result = $.csv.toArrays(data);
                for (i=0; i<result.length; i++){
                    companyMap.set(result[i][0].split("/")[1],result[i][1].split(" (")[0]
                    );
                    reversedMap.set(result[i][1].split(" (")[0],result[i][0].split("/")[1]);

                }

                // Generates array of company names to be used for autocompletion
                var companyNames = [];
                for (i = 0; i<result.length; i++){
                    companyNames.push(result[i][1].split(" (")[0]);
                }

                // Callback allows global variables to be set to appropriate maps after completion of the Ajax call
                callback(companyMap, reversedMap, companyNames);
                $("#stock1").autocomplete({
                    source: companyNames
                });
                $("#stock2").autocomplete({
                    source: companyNames
                });
            }
            });
        }

        // Sets global variables, as well as flag to indicate Ajax call has completed
        returnCompanyMap(function(map, reversed, names){
            companyMap = map;
            reversedMap = reversed;
            companyNames = names;
            completed = true;
        });
    });
    </script>
    <title>StockFu | New Chart</title>

    <?php
    // Set up DB connection
    require_once 'config.php';
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli->errno){
        print('There was an error in connecting to the database:');
        print($mysqli->error);
        exit();
    }
    ?>
</head>

<style type="text/css">
    footer{
        position: fixed;
        bottom: 0px;
        left: 0px;
        right: 0px;
        height: 50px;
        color: white;
        text-align: center;
        background: #9C9A9A;
    }
    td{
        padding: 30px;
        text-align: center;
    }
    .newChart{
        border-style: solid;
        border-color: black;
        border-width: 5px;
        width: 75%;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
    #toolbar{
        left: 0px;
        right: 0px;
        top: 0px;
        position: absolute;
        background: #9C9A9A;
        width: 100%
    }
    #page-title{
        font-size: 200px;
    }
    #navbar-element{
        padding: 30px;
    }
    .axis path {
        fill: none;
        stroke: #777;
        shape-rendering: crispEdges;
    }
    .axis text {
        font-family: Lato;
        font-size: 13px;
    }
</style>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="row">
            <h1 class="page-title">Make a new chart (please fill out all options)<h1>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table>
                    <tr>
                        <td>
                            Name your chart
                            <input id = "chartName" type="text">
                        </td>
                        <td>
                            Stock name or symbol:<br>
                            <!--<input type="text" name="stock">-->
                            <input id="stock1" name="stock1" />
                            <input type="button" name="addstock" id="add" value="+">
                        </td>
                        <td>
                            Start Date:<br>
                            <input type="text" name="startDate" id="startDatePicker">
                        </td>
                        <td>
                            End Date:<br>
                            <input type="text" name="endDate" id="endDatePicker">
                        </td>
                        <td>
                            <p>Type of chart you want to show:</p><br>
                            <input type="radio" id="stockValue" name = "stockValue" value="Open"> Open<br>
                            <input type="radio" id="stockValue" name = "stockValue" value="High"> High<br>
                            <input type="radio" id="stockValue" name = "stockValue" value="Low"> Low
                        </td>
                        <td>
                            Make chart public?<br>
                            <input type="checkbox" name="public">
                        </td>
                        <td>
                            <input type="button" id = "finish" name="finish" value="Finish">
                        </td>
                    </tr>
                    <tr id="secondOne" style="display: none;">
                        <td>
                            Stock name or symbol:<br>
                            <input id="stock2" name="stock2">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <svg id="newChart" width="1000px" height="500px"></svg>
    </div>
    <script type="text/javascript">
        $('#add').click(function(){
            $('#secondOne').toggle("fast");
        });
        $('#finish').click(function(){
            
            // Spin waits until Ajax call has completed - next segment needs updated companyMap
            while (!completed);
            // Retrieves start date, end date, and stock options from user input fields
            var chartName = $("#chartName").val();
            var sDate = $("#startDatePicker").val();
            var eDate = $("#endDatePicker").val();
            var startDate = (sDate != '') ? ('start_date=' + sDate) : '';
            var endDate = (eDate != '') ? ('&end_date=' + eDate) : '';
            var stock1Name = $("#stock1").val();
            var stock1 = reversedMap.get(stock1Name);
            var stock2 = $("#stock2").val();
            var stock2Completed = (stock2 == "") ? true : false;
            var stockValue = document.querySelector('input[name="stockValue"]:checked').value;
            var priceOption = (stockValue == "Low") ? 3 : (stockValue == "High") ? 2 : 1;

            // Forms API call from user inputs
            var APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1 + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
            console.log(APICall);

            // TD: What if company has no stock info for given date range? 
            // Hint: use newest available date range from the API data before accessing stockData
            $.getJSON(APICall).done(function(result){
                var data = result["dataset"];
                var stockData = data["data"];
                var demo = d3.select("#newChart");
                var maxDate = new Date(stockData[0][0]);
                var minDate = new Date(stockData[stockData.length-1][0]);
                var stockData2;

                // Sets height, width, and margins for the new chart
                var height = 400;
                var width = 900;
                var margins = {
                    top: 20,
                    right: 20,
                    bottom: 20,
                    left: 50
                };

                // Computes maximum value of y axis based on highest stock price
                var priceYMax = stockData[0][priceOption];
                for (i = 0; i<stockData.length; i++){
                    if (stockData[i][priceOption] > priceYMax){
                        priceYMax = stockData[i][priceOption];
                    }
                }


                // Sets up x and y axis
                var xScale = d3.time.scale().range([margins.left,width-margins.right]).domain([minDate,maxDate]);
                var yScale = d3.scale.linear().range([height-margins.top,margins.bottom]);

                // Adds line chart for second company, if not empty
                // TD: Even if second API call fails, this still executes first one. 
                console.log("Test 1");

                /*
                function getSecondStockData(callback){
                    if (stock2 != ""){
                        stock2 = reversedMap.get(stock2);
                        console.log("test 1.5");
                        var secondAPICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock2 + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
                        $.getJSON(secondAPICall, function(result2){
                            console.log("test 2");
                            var data2 = result2["dataset"];
                            stockData2 = data2["data"];
                            for (i = 0; i<stockData2.length; i++){
                                if (stockData2[i][priceOption] > priceYMax){
                                    priceYMax = stockData2[i][priceOption];
                                }
                            }
                            console.log("WELL HERE I AM");
                            stock2Completed = true;
                            callback(stockData2, stock2Completed);
                        }).fail(function(jqxhr){
                            alert("The data you inputted was invalid - please choose a company from the autocomplete feature");
                            stock2Completed = true;
                            callback(stockData2, stock2Completed);
                            });
                    }
                    else callback([], true);
                }

                getSecondStockData(function(sData, sCompleted){
                    console.log("callback completed");
                    stockData2 = sData;
                    stock2Completed = sCompleted;
                    priceYMax += 20;
                    yScale.domain([0,priceYMax]);

                    var xAxis = d3.svg.axis().scale(xScale).ticks(8);
                    var yAxis = d3.svg.axis().scale(yScale).orient("left");
                    var formatTime = d3.time.format("%e %B");
                    // Orients axes
                    demo.append("svg:g").attr("class","axis").attr("transform","translate(0," + (height - margins.bottom) + ")").call(xAxis);
                    demo.append("svg:g").attr("class","axis").attr("transform","translate(" + margins.left + ",0)").call(yAxis);

                    var div = demo.append("div").attr("class","tooltip").style("opacity",0);

                    // Generates lines using open stock price
                    var lineGen = d3.svg.line()
                    .x(function(d) {
                        return xScale(new Date(d[0]));
                    })
                    .y(function(d) {
                        return yScale(d[priceOption]);
                    })
                    .interpolate("basis");

                    // Appends line chart to svg with dank attributes
                    demo.append('svg:path')
                    .attr('d',lineGen(stockData))
                    .attr("id","lineChart")
                    .attr('stroke','green')
                    .attr('stroke-width',2)
                    .attr('fill','none');
                  
                    if (stock2 != ""){
                        demo.append('svg:path')
                        .attr('d',lineGen(stockData2))
                        .attr('stroke','blue')
                        .attr('stroke-width',2)
                        .attr('fill','none');
                    }

                    // Adds x-axis label
                    demo.append("text").attr("x",width/2).attr("y",height + 30).style("text-anchor","middle").style("font-size",16).style("font-family","Lato").text("Date");

                    // Adds y-axis label
                    demo.append("text").attr("transform","rotate(-90)").attr("y",10).attr("x",-height/2).style("text-anchor","middle").style("font-family","Lato").text("Open Stock Price (in USD)");

                    // Gets HTML representation of svg element
                    svgChildren = document.getElementById("newChart").outerHTML;

                    var parameters = JSON.stringify({
                        svg : svgChildren,
                        company : stock1Name,
                        start_date : sDate,
                        end_date : eDate,
                        chartName: chartName
                    });

                    // Send relevant input, including SVG, to PHP
                    $.ajax({
                        type: 'POST',
                        url: 'ChartToDB.php',
                        data: {'param': parameters},
                        dataType: 'json'
                    })
                    .done( function(data){
                        console.log('done');
                        console.log(data);
                        //window.location.replace("makeNew.php");
                    })
                    .fail( function(data){
                        console.log('failure');
                        console.log(data);
                    });
                }); // end function call
                .fail(function(jqxhr){
                    alert("The data you inputted was invalid - please choose a company from the autocomplete feature");
                   });
                */

                //while (!stock2Completed);
                
                priceYMax += 20;
                yScale.domain([0,priceYMax]);

                var xAxis = d3.svg.axis().scale(xScale).ticks(8);
                var yAxis = d3.svg.axis().scale(yScale).orient("left");
                
                // Orients axes
                demo.append("svg:g").attr("class","axis").attr("transform","translate(0," + (height - margins.bottom) + ")").call(xAxis);
                demo.append("svg:g").attr("class","axis").attr("transform","translate(" + margins.left + ",0)").call(yAxis);

                // Generates lines using open stock price
                var lineGen = d3.svg.line()
                .x(function(d) {
                    return xScale(new Date(d[0]));
                })
                .y(function(d) {
                    return yScale(d[priceOption]);
                });

                // Appends line chart to svg with dank attributes
                demo.append('svg:path')
                .attr('d',lineGen(stockData))
                .attr('stroke','green')
                .attr('stroke-width',2)
                .attr('fill','none');

                if (stock2 != ""){
                    demo.append('svg:path')
                    .attr('d',lineGen(stockData2))
                    .attr('stroke','blue')
                    .attr('stroke-width',2)
                    .attr('fill','none');
                }

                // Adds x-axis label
                demo.append("text").attr("x",width/2).attr("y",height + 30).style("text-anchor","middle").style("font-size",16).style("font-family","Lato").text("Date");

                // Adds y-axis label
                demo.append("text").attr("transform","rotate(-90)").attr("y",10).attr("x",-height/2).style("text-anchor","middle").style("font-family","Lato").text("Open Stock Price (in USD)");

                // Gets HTML representation of svg element
                svgChildren = document.getElementById("newChart").outerHTML;

                var parameters = JSON.stringify({
                    svg : svgChildren,
                    company : stock1Name,
                    start_date : sDate,
                    end_date : eDate,
                    chartName: chartName
                });

                // Send relevant input, including SVG, to PHP
                $.ajax({
                    type: 'POST',
                    url: 'ChartToDB.php',
                    data: {'param': parameters},
                    dataType: 'json'
                })
                .done( function(data){
                    console.log('done');
                    console.log(data);
                    //window.location.replace("makeNew.php");
                })
                .fail( function(data){
                    console.log('failure');
                    console.log(data);
                });
            }).fail(function(jqxhr){
            alert("The data you inputted was invalid - please choose a company from the autocomplete feature");
            }); 
         });
    </script>

    <footer>
        <!-- Tell people that this is my website do not steal -->
        <div id="copyright">
            Copyright &copy; 2016 The Web Development Group. All rights reserved.
        </div>
	</footer> 
    
</body>
</html>
