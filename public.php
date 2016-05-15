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
    <!--JavaScript -->	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
 <!--   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockFu | Public</title>
</head>


<body>
    <div class="container">
        <?php include 'navbar.php'; ?>
        <div class="row">
            <h1 class="page-title">Public Charts</h1>
        </div>

        <?php
            require_once 'config.php';
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $publicCharts = $mysqli -> query("SELECT * FROM Charts WHERE Public = 1");
            while ($row = $publicCharts -> fetch_assoc()){
                $chartID = $row['chartID'];
                $symbol = $row['name'];
                $startDate = date('F d, Y', strtotime($row['startDate']));
                $endDate = date('F d, Y', strtotime($row['endDate']));
                $chartName = $row['chartName'];
                $svg = str_replace("width=\"1000px\"", "width=\"380px\"", $row['svg_string']);
                $svg = str_replace("height=\"500px\"", "height=\"230px\"", $svg);
                $svg = str_replace("id=\"newChart\"", "class=\"backgroundSvg\"", $svg);

                echo "
                    <div class=\"col-md-4\" id=\"stock\">
                        <a href=\"viewChartPublic.php?chartID=$chartID\">
                        <h1 class=\"symbol\">$symbol</h1>
                        <h4 class=\"company\">$chartName</h4>
                        <p class=\"dates\">$startDate to $endDate</p>
                        </a>
                    </div>";
            }

        ?>
    </div>

    <!--
    <footer>
        <div id="copyright">
            Copyright &copy; 2016 The Web Development Group. All rights reserved.
        </div>
	</footer> 
    -->
    
</body>
</html>