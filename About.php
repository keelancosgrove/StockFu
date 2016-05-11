<?php 
error_reporting(E_ALL & ~E_NOTICE);
session_start();
 ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/ionicons.css">
    
    <!--JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>StockFu | About</title>
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
    <?php
        if (isset($_SESSION['logged_user'])){
            include 'navbar.php';
        }
        else {
            include 'altNavBar.php';
        }
    ?>

    <div class="row">
        <div class="col-md-6">
            <h1 class="page-title">What is StockFu?<h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p>StockFu allows anyone to explore the stock market in an accessible, customizable, and interactive way. The idea is to let you make your own profile of stock charts, which could vary on company, date range, and more. You can create and customize these charts, and re-visit them later on. You can also compare and contrast companies on one stock chart, or with the same company across different date ranges. Whether you're a beginner or expert, student or professional, you can get started and explore the world of stocks!</p> <br>
            <p>StockFu was created by Andrew Cadwallader in 2016. He sought to integrate web technologies with the world of stocks in order to give his audience, primarily college students, a way to easily customize and explore stock charts. </p>
            <p>Quandl.com is used to retrieve stock data.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>An example chart is shown below, for Yelp data from 2013 to 2016</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <?php
          require_once("config.php");
          $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $getChart = $mysqli -> query("SELECT svg_string FROM Charts WHERE chartID = 1");
          $exampleChart = $getChart -> fetch_assoc();
          print($exampleChart['svg_string']);
        ?>
        </div>
    </div>
        <div id="footer">
            <footer>
                    Copyright &copy; 2016 The Web Development Group. All rights reserved.
            </footer>
        </div> 
</div>
</body>
</html>