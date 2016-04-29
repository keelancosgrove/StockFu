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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockFu | Home</title>
</head>

<style type="text/css">
    #stock{
        /* Rectangle 1: */
        background-image: linear-gradient(-180deg, #151515 0%, #6F6F6F 100%);
        border-style: solid;
        border-width: 5px;
        border-color: white;
    }
    #stock:after{
        content: '';
        height: 100%;
        position: absolute;
        width: 10%;
        background: green;
        top: 0;
        bottom: 0;
        right: 0;
    }
    .symbol, .company, .dates, .multi-symbol, .name{
        color: white;
    }
    .symbol{
        font-size: 75px;
    }
    .multi-symbol{
        font-size: 50px;
    }
    footer{
        position: absolute;
        bottom: 0px;
        left: 0px;
        right: 0px;
        height: 50px;
        color: white;
        text-align: center;
        background: #9C9A9A;
        font-style: 20px;
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
    #plus-sign{
        font-size: 50px;
        font-style: bold;
        background-color: grey;
        text-align: center;
    }
</style>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="row">
            <h1 class="page-title">Your Charts<h1>
        </div>
        <div class="row">
            <div class="col-md-4" id="stock">
                <a href="viewChartPrivate.php">
                    <h1 class="symbol">AAPL</h1>
                    <h4 class="company">Apple Inc.</h4>
                    <p class="dates">Jan. 4, 2000 - Present</p>
                </a>
            </div>
            <div class="col-md-4" id="stock">
                <a href="viewChartPrivate.php">
                    <h1 class="symbol">GOOG</h1>
                    <h4 class="company">Alphabet Inc.</h4>
                    <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
                </a>
            </div> 
            <div class="col-md-4" id="stock">
                <a href="viewChartPrivate.php">
                    <h1 class="symbol">CMCSA</h1>
                    <h4 class="company">Comcast Corp.</h4>
                    <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
                </a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-4" id="stock">
                <a href="viewChartPrivate.php">
                    <h1 class="symbol">DJIA</h1>
                    <h4 class="company">Dow Jones Industrial Avg.</h4>
                    <h5 class="name">How well my portfolio is going</h5>
                    <p class="dates">This Week</p>
                </a>
            </div>
            <div class="col-md-4" id="stock">
                <a href="viewChartPrivate.php">
                    <h1 class="multi-symbol">CMCSA & GOOG</h1>
                    <h4 class="company">Comcast Corp. & Alphabet Inc.</h4>
                    <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
                </a>
            </div> 
            <div class="col-md-4" id="stock">
                <a href="viewChartPrivate.php">
                    <h1 class="symbol">FB</h1>
                    <h4 class="company">Facebook Inc.</h4>
                    <p class="dates">Max</p>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4" id="stock">
                <a href="viewChartPrivate.php">
                    <h1 class="symbol">NASDAQ</h1>
                    <h4 class="company">Nasdaq Inc.</h4>
                    <p class="dates">This Week</p>
                </a>
            </div>
            <div class="col-md-4" id="stock">
                <a href="viewChartPrivate.php">
                    <h1 class="multi-symbol">F & GM</h1>
                    <h4 class="company">Ford Motor Co. & General Motors Co.</h4>
                    <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="makeNew.php">
                    <h1 id="plus-sign">+</h1>
                </a>
            </div>
        </div>  
    </div>

    <footer>
        <!-- Tell people that this is my website do not steal -->
        <div id="copyright">
            Copyright &copy; 2016 The Web Development Group. All rights reserved.
        </div>
    </footer> 
    
</body>
</html>