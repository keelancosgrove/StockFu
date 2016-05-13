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
            <h1 class="page-title">Public Charts<h1>
        </div>
        <div class="row">
            <div class="col-md-4" id="stock">
                <a href="viewChartPublic.php">
                    <h1 class="symbol">AAPL</h1>
                    <h4 class="company">Apple Inc.</h4>
                    <p class="dates">Jan. 4, 2000 - Present</p>
                    <h5 class="user">Made by <i>Keelan C.</i></h5>
                </a>
            </div>
            <div class="col-md-4" id="stock">
                <a href="viewChartPublic.php">
                    <h1 class="symbol">GOOG</h1>
                    <h4 class="company">Alphabet Inc.</h4>
                    <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
                    <h5 class="user">Made by <i>Steve M.</i></h5>
                </a>
            </div> 
            <div class="col-md-4" id="stock">
                <a href="viewChartPublic.php">
                    <h1 class="symbol">CMCSA</h1>
                    <h4 class="company">Comcast Corp.</h4>
                    <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
                    <h5 class="user">Made by <i>Shea B.</i></h5>
                </a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-4" id="stock">
                <a href="viewChartPublic.php">
                    <h1 class="symbol">DJIA</h1>
                    <h4 class="company">Dow Jones Industrial Avg.</h4>
                    <h5 class="name">How well my portfolio is going</h5>
                    <p class="dates">This Week</p>
                    <h5 class="user">Made by <i>Louis C.</i></h5>
                </a>
            </div>
            <div class="col-md-4" id="stock">
                <a href="viewChartPublic.php">
                    <h1 class="multi-symbol">CMCSA & GOOG</h1>
                    <h4 class="company">Comcast Corp. & Alphabet Inc.</h4>
                    <h5 class="name">Old Tech vs. New Tech</h5>
                    <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
                    <h5 class="user">Made by <i>Kevin G.</i></h5>
                </a>
            </div> 
            <div class="col-md-4" id="stock">
                <a href="viewChartPublic.php">
                    <h1 class="symbol">FB</h1>
                    <h4 class="company">Facebook Inc.</h4>
                    <p class="dates">Max</p>
                    <h5 class="user">Made by <i>Mark Z.</i></h5>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4" id="stock">
                <a href="viewChartPublic.php">
                    <h1 class="symbol">NASDAQ</h1>
                    <h4 class="company">Nasdaq Inc.</h4>
                    <p class="dates">This Week</p>
                    <h5 class="user">Made by <i>Daniel F.</i></h5>
                </a>
            </div>
            <div class="col-md-4" id="stock">
                <a href="viewChartPublic.php">
                    <h1 class="multi-symbol">F & GM</h1>
                    <h4 class="company">Ford Motor Co. & General Motors Co.</h4>
                    <h5 class="name">Battle of the Giants</h5>
                    <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
                    <h5 class="user">Made by <i>Anthony G.</i></h5>
                </a>
            </div>
            <div class="col-md-4">
                <a href="viewChartPublic.php">
                    <h1 class="multi-symbol">MSFT</h1>
                    <h4 class="company">Microsoft Corp.</h4>
                    <p class="dates">Dec. 11, 2000 - Present</p>
                    <h5 class="user">Made by <i>Claire B.</i></h5>
                </a>
            </div>
        </div>  
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