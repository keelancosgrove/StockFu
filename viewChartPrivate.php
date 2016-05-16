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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.min.js"></script>
    <script>
        $(document).ready(function() {
            $.getScript("js/viewPrivate.js");
        });
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
                    <td><a href="home.php"><button id="finish">Finish</button></a></td>
                </tr>
            </table>
        </div>

        <div class="row" id="editPane" style="display: none;">
            <div class="col-md-12">
                <table>
                    <tr>
                        <td>
                            <b>Stock name or symbol:</b><br>
                            <input type="text" id="stock1Name" name="stock">
                            <input type="button" name="addstock" id="add" value="+">
                        </td>
                        <td>
                            <b>Start Date:</b><br>
                            <input type="button" name="startDate" value="Start Date" id="startDatePicker">
                        </td>
                        <td>
                            <b>End Date:</b><br>
                            <input type="button" name="endDate" value="End Date" id="endDatePicker">
                        </td>
                        <td>
                            <a href="viewChartPrivate.php">
                                <input type="button" name="finish" value="Finish">
                            </a>
                        </td>
                    </tr>
                    <tr id="secondOne" style="display: none;">
                        <td>
                            Stock name or symbol:<br>
                            <input type="text" name="stock">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>Type of chart you want to show:</b></td>
                    </tr>
                    <tr>
                        <td><input type="radio" id="stockValue" name = "stockValue" value=1> Open</td>
                        <td><input type="radio" id="stockValue" name = "stockValue" value=2> High</td>
                        <td><input type="radio" id="stockValue" name = "stockValue" value=3> Low</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table>
                    <th>
                        <td><b>Date:</b></td>
                        <td><b>Open:</b></td>
                        <td><b>High:</b></td>
                        <td><b>Low:</b></td>
                        <td><b>Close:</b></td>
                        <td><b>Volume:</b></td>
                    </th>
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
        echo "<div class=\"col-md-12\" id=\"chart\">";
            print($svg);
        echo "</div>";
    ?>
    <div id="footer">
        <footer>
            Copyright &copy; 2016 The Web Development Group. All rights reserved.
        </footer>
    </div>
</div>
</body>
</html>
