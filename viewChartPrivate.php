<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (!isset($_SESSION['logged_user'])){
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8" />
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/ionicons.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!--JavaScript-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.min.js"></script>
    <script src="js/viewPrivate.js"></script>

    <script>
        var minDate;
        var maxDate;
        var xScale;
        var yScale;
        var dates = [];
        var dateMap;
        var height = 400;
        var width = 900;
        var margins = {
            top: 20,
            right: 20,
            bottom: 20,
            left: 50
        };
        var demo = d3.select("#newChart");
    </script>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockFu | View Your Chart</title>
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



    <div class="container">
    <?php include 'navbar.php';

    if (isset($_GET['chartID'])){
        $chartID = $_GET['chartID'];
        $selectedChart = $mysqli -> query("SELECT * FROM Charts WHERE chartID = '$chartID'");
        if ($selectedChart == false) print("Failed to find chart with associated chart ID in database");
        $row = $selectedChart -> fetch_assoc();
    }
    ?>
        <div class="row">
            <table>
                <tr>
                    <td><h1 class="page-title">
                    <?php
                        $symbol = $row['name'];
                        print($symbol);
                    ?>
                    </h1></td>
                </tr>
                <tr>
                    <td><p>
                    <?php
                        $chartName = $row['chartName'];
                        $start_date = date('F d, Y', strtotime($row['startDate']));
                        $end_date = date('F d, Y', strtotime($row['endDate']));
                        print("<h4>$chartName</h4>");
                        print("$start_date - $end_date");
                    ?>
                    </p></td>
                    <td><button id="edit">Edit Chart</button></td>
                    <td><button id="delete">Delete Chart</button></td>
                    <td>
                        Make chart public?<br>
                        <input type="checkbox" name="public">
                    </td>
                    <td><a href="test.php"><button id="finish">Finish</button></a></td>
                </tr>
            </table>
        </div>

        <div class="row" id="editPane" style="display: none;">
            <div class="col-md-12">
                <table>
                    <tr>
                        <td>
                            Stock name or symbol:<br>
                            <input type="text" id="stock1Name" name="stock">
                            <input type="button" name="addstock" id="add" value="+">
                        </td>
                        <td>
                            Start Date:<br>
                            <input type="button" name="startDate" value="Start Date">
                        </td>
                        <td>
                            End Date:<br>
                            <input type="button" name="endDate" value="End Date">
                        </td>
                        <td>
                            Type of chart you want to show:<br>
                            <input type="button" name="type" value="Choose One">
                        </td>

                        <td>
                            <a href="test.php">
                                <input type="button" name="finish" value="Finish">
                            </a>
                        </td>
                        <td><b>Stock name or symbol:</b></td>
                        <td><b>Pick another Start Date:</b></td>
                        <td><b>Pick another End Date:</b></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="stock"><input type="button" name="addstock" id="add" value="+"></td>
                        <td><input type="button" name="startDate" value="Start Date"></td>
                        <td><input type="button" name="endDate" value="End Date"></td>
                    </tr>
                    <tr id="secondOne" style="display: none;">
                        <td>
                            Stock name or symbol:<br>
                            <input type="text" name="stock">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>Type of chart you want to show:</b></td>
                        <td><b>Make chart public?</b></td>
                    </tr>
                    <tr>
                        <td><input type="radio" id="stockValue" name = "stockValue" value=1> Open</td>
                        <td><input type="radio" id="stockValue" name = "stockValue" value=2> High</td>
                        <td><input type="radio" id="stockValue" name = "stockValue" value=3> Low</td>
                        <td><input type="checkbox" name="public"></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
            $svg = $row['svg_string'];
            echo "<div class=\"col-md-12\" id=\"chart\">";
                print($svg);
            echo "</div>";
        ?>
    <script>
         demo.append("text")
        .attr("x", (width / 2))
        .attr("y", 0 - (margins.top / 2))
        .attr("text-anchor", "middle")
        .attr("id", "charTooltip")
        .style("font-size", "16px")
        .style('fill', "black")
        .style("text-decoration", "underline")
        .text("chartName"); 

        // Obtains chart data from database to reconstruct scales
        function getChartData(callback){
            var chartID = location.search.split('chartID=')[1];
            var parameters = JSON.stringify({
                chartID: chartID
            });
            $.ajax({
                type: 'POST',
                url: 'query.php',
                data: {
                    'param': parameters
                },
                datatype: 'json',
            })
            .done(function(data) {
                // Creates scales identical to those used in makeNew.js
                data = JSON.parse(data);
                minDate = new Date(data[0]);
                maxDate = new Date(data[1]);
                var priceYMax = data[2];
                dates = JSON.parse(data[3]);
                dateMap = new Map(JSON.parse(data[4]));
                xScale = d3.time.scale().range([margins.left, width - margins.right]).domain([minDate, maxDate]);
                yScale = d3.scale.linear().range([height - margins.top, margins.bottom]).domain([0, priceYMax]);
                callback(xScale, yScale);
            });
        }

        getChartData(function(xScale, yScale) {
            d3.select("#newChart")
            .on("mouseover", function(){
                // Allows tooltip to display in chart
                d3.select("#charTooltip").attr("display",null);
            })
            .on("mouseout", function(){
                // Causes tooltip text to dissapear upon removing mouse from line chart
                d3.select("#charTooltip").attr("display","none");
            })
            .on("mousemove", function(){
                // Updates position and text in tooltip with correct information based on where mouse is on chart
                var date = xScale.invert(d3.event.pageX-margins.left).toString().split(" ");
                var date_formatted = new Date(xScale.invert(d3.event.pageX).toString());
                var beforedates = dates.filter(function(d) {
                    return d-date_formatted < 0;
                });
                console.log(date_formatted);
                var dateData= dateMap.get(beforedates[0]);
                console.log(dateData);
                d3.select("#charTooltip")
                .attr("class", "thisText")
                .attr("x", 330)
                .attr("y", 15)
                .attr("fill", "black").style("text-anchor", "middle")
                // Sets text to tooltip with stock information from given date
                .text(date[1] + " " + date[2] + " " + date[3] + 
                    " Open: " + dateData[1] + 
                    " High: " + dateData[2] + " Low: " + dateData[3] + 
                    " Close: " + dateData[4] + 
                    " Volume: " + dateData[5])
                .style("font-weight","bold");
            });


        });
    </script>

    <div id="footer">
        <footer>
            Copyright &copy; 2016 The Web Development Group. All rights reserved.
        </footer>
    </div>
</div>    
</body>
</html>
