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
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/ionicons.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!-- JavaScript -->
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
   <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="js/chart.js"></script>
    <script src="js/makeNew.js"></script>
    <script src="jquery-csv-master/src/jquery.csv.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

<body>
    <div class="container" id="main">
    <?php include 'navbar.php'; ?>
        <div class="row">
            <h1 class="page-title">Make a new chart</h1>
            <h3>(please fill out all options)</h3>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table>
                    <tr>
                        <td><b>Name your chart</b></td>
                        <td><b>Stock name or symbol:</b></td>
                        <td><b>Pick a Start Date:</b></td>
                        <td><b>Pick an End Date:</b></td></td>
                    </tr>
                    <tr>
                        <td><input id="chartName" type="text" value="Enter a name"></td>
                        <td><input id="stock1" name="stock1" value="GOOGL"/></td>
                        <?php
                          $endDate = date("Y-m-d");
                          $startTime = strtotime("-1 year", time());
                          $startDate = date("Y-m-d",$startTime);
                          echo "<td><input type=\"text\" name=\"startDate\" id=\"startDatePicker\" value=\"$startDate\"></td>";
                          echo "<td><input type=\"text\" name=\"endDate\" id=\"endDatePicker\" value=\"$endDate\"></td>";
                         ?>


                        <td><input type="button" id = "finish" name="finish" value="Finish"></td>
                    </tr>
                    <tr id="secondOne" style="display: none;">
                        <td></td>
                        <td>
                            Stock name or symbol:<br>
                            <input id="stock2" name="stock2">
                        </td>
                    </tr>
                    <tr>
                        <td><b>Make chart public?</b></td>
                        <td><input type="checkbox" id = "public" name="public"></td>
                    </tr>
                    <tr id="stock-options" style="position: absolute">
                        <td colspan="3"><b>Type of chart you want to show:</b></td>
                        <td><input type="radio" class="stockValue" name = "stockValue" checked="checked" value=1> Open</td>
                        <td><input type="radio" class="stockValue" name = "stockValue" value=2> High</td>
                        <td><input type="radio" class="stockValue" name = "stockValue" value=3> Low</td>

                    </tr>

                </table>

            </div>
        </div>
        <br>
        <div class="row" id="chart">


        <div class="row">
            <div class="col-md-6">
                <table id="tooltip">
                    <tr>
                        <td><b>Date:</b></td>
                        <td><b>Open:</b></td>
                        <td><b>High:</b></td>
                        <td><b>Low:</b></td>
                        <td><b>Close:</b></td>
                        <td><b>Volume:</b></td>
                    </tr>
                    <tr>
                        <td id="date"></td>
                        <td id="open"></td>
                        <td id="high"></td>
                        <td id="low"></td>
                        <td id="close"></td>
                        <td id="volume"></td>
                    </tr>
                </table>
            </div>
        </div>

          <p id="noDataMessage" style="color: red; display: none;"> Warning: Graph did not update due to lack of data. </p>
            <p id="charTooltip"></p>
            <svg id="newChart" width="1500px" height="500px"></svg>
        </div>
        <div id="footer">
            <footer>
                    Copyright &copy; 2016 The Web Development Group. All rights reserved.
            </footer>
        </div>
    </div>
</body>
</html>
