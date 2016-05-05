<?php 
error_reporting(E_ALL & ~E_NOTICE);
session_start();
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--
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
-->


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
    <?php
        if (isset($_SESSION['logged_user'])){
            include 'navbar.php';
        }
        else {
            include 'altNavBar.php';
        }
    ?>
   <h1 class="page-title">What is StockFu?</h1>
   <p>StockFu allows anyone to explore the stock market in an accessible, customizable, and interactive way. The idea is to let you make your own profile of stock charts, which could vary on company, date range, and more. You can create and customize these charts, and re-visit them later on. You can also compare and contrast companies on one stock chart, or with the same company across different date ranges. Whether you're a beginner or expert, student or professional, you can get started and explore the world of stocks!</p> <br>
   <p>StockFu was created by Andrew Cadwallader in 2016. He sought to integrate web technologies with the world of stocks in order to give his audience, primarily college students, a way to easily customize and explore stock charts. </p>
   <p>Quandl.com is used to retrieve stock data.</p>

   <h3>An example chart is shown below, for Yelp data from 2013 to 2016</h3>
   
    <?php
      require_once("config.php");
      $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $getChart = $mysqli -> query("SELECT svg_string FROM Charts WHERE chartID = 1");
      $exampleChart = $getChart -> fetch_assoc();
      print($exampleChart['svg_string']);
    ?>

    <!--
    <footer>
        <div id="copyright">
            Copyright &copy; 2016 Kevin Guo. All rights reserved.
        </div>
    </footer> 
    -->
    
</body>
</html>