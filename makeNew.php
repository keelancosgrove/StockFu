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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="js/makeNew.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.min.js"></script>
    <script src="jquery-csv-master/src/jquery.csv.js"></script>

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
    <div class="container" id="main">
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
                            <input type="radio" id="stockValue" name = "stockValue" value=1> Open<br>
                            <input type="radio" id="stockValue" name = "stockValue" value=2> High<br>
                            <input type="radio" id="stockValue" name = "stockValue" value=3> Low
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

    <footer>
        <!-- Tell people that this is my website do not steal -->
        <div id="copyright">
            Copyright &copy; 2016 The Web Development Group. All rights reserved.
        </div>
	</footer>

</body>
</html>
