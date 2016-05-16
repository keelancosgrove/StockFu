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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!--JavaScript-->
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
   <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.min.js"></script>
    <script src="js/chart.js"></script>
    <script src="js/viewPrivate.js"></script>
    <script src="jquery-csv-master/src/jquery.csv.js"></script>
    <!-- <script>
    <script src="https://code.jquery.com/jquery-2.2.3.js" integrity="sha256-laXWtGydpwqJ8JA+X9x2miwmaiKhn8tVmOVEigRNtP4=" crossorigin="anonymous"></script>

    </script> -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockFu | View Public Charts</title>
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
                        $chartName = $row['chartName'];
                        $start_date = date('F d, Y', strtotime($row['startDate']));
                        $end_date = date('F d, Y', strtotime($row['endDate']));
                        print($symbol);
                        print("<h4>$chartName</h4>");
                        print("$start_date - $end_date");
                    ?>
                    </h1></td>
                </tr>
            </table>
        </div>
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

    <?php
        $svg = $row['svg_string'];
        print("<div class=\"col-md-12\" id=\"chart\">");
        print($svg);
        print("</div>");
    ?>
    <div id="footer">
        <footer>
            Copyright &copy; 2016 The Web Development Group. All rights reserved.
        </footer>
    </div>
</div>
</body>
</html>
