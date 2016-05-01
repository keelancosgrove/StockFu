<?php 
session_start();
if (!isset($_SESSION['logged_user'])){
    header('Location: StockFuLogin.php');
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

    <script src="http://cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
    $(function () {

        // Adds datepicker calendar feature to the following input fields
        $("#startDatePicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            onSelect: function (selected, inst) {
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
                var dt = new Date(selected);
                dt.setDate(dt.getDate() - 1);
                $("#startDatePicker").datepicker("option","maxDate",selected);
            }
        });

        // Example array: real array should be list of all company stock option
        // TD: Get list of all companies
        var tags = ["AAPL","MSFT","FB","GOOG","AMZN","YELP"];

        // Adds autocomplete feature to the following input fields
        $("#stock1").autocomplete({
            source: tags
        });
        $("#stock2").autocomplete({
            source: tags
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
</style>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="row">
            <h1 class="page-title">Make a new chart<h1>
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
                            Type of chart you want to show:<br>
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
            // Retrieves start date, end date, and stock options from user input fields
            var chartName = $("#chartName").val();
            var sDate = $("#startDatePicker").val();
            var eDate = $("#endDatePicker").val();
            var startDate = (sDate != '') ? ('start_date=' + sDate) : '';
            var endDate = (eDate != '') ? ('&end_date=' + eDate) : '';
            console.log(startDate);
            var stock1 = $("#stock1").val();
            //var stock2 = $("#stock2").val();
            var stockValue = document.querySelector('input[name="stockValue"]:checked').value;
            var priceOption = (stockValue == "Low") ? 3 : (stockValue == "High") ? 2 : 1;
            console.log(stockValue);

            // Forms API call from user inputs
            var APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1 + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
            console.log(APICall);

            $.getJSON(APICall).done(function(result){
                var data = result["dataset"];
                console.log("Happy times!");
                var stockData = data["data"];
                var demo = d3.select("#newChart");
                var maxDate = new Date(stockData[0][0]);
                var minDate = new Date(stockData[stockData.length-1][0]);

                // Computes maximum value of y axis based on highest stock price
                var priceYMax = stockData[0][priceOption];
                for (i = 0; i<stockData.length; i++){
                    if (stockData[i][priceOption] > priceYMax){
                        priceYMax = stockData[i][priceOption];
                    }
                }
                priceYMax += 20;

                var height = 400;
                var width = 900;
                var margins = {
                    top: 20,
                    right: 20,
                    bottom: 20,
                    left: 50
                };

                // Sets up x and y axis
                var xScale = d3.time.scale().range([margins.left,width-margins.right]).domain([minDate,maxDate]);
                var yScale = d3.scale.linear().range([height-margins.top,margins.bottom]).domain([0,priceYMax]);
                var xAxis = d3.svg.axis().scale(xScale);
                var yAxis = d3.svg.axis().scale(yScale).orient("left");
                
                // Orients axes
                demo.append("svg:g").attr("transform","translate(0," + (height - margins.bottom) + ")").call(xAxis);
                demo.append("svg:g").attr("transform","translate(" + margins.left + ",6)").call(yAxis);

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

                // Adds x-axis label
                demo.append("text").attr("x",width/2).attr("y",height + 30).style("text-anchor","middle").style("font-size",16).text("Date");

                // Adds y-axis label
                demo.append("text").attr("transform","rotate(-90)").attr("y",10).attr("x",-height/2).style("text-anchor","middle").text("Open Stock Price");

                // Gets HTML representation of svg element
                svgChildren = document.getElementById("newChart").outerHTML;

                var parameters = JSON.stringify({
                    svg : svgChildren,
                    company : stock1,
                    start_date : sDate,
                    end_date : eDate,
                    chartName: chartName
                });
                console.log(parameters);

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
