<?php
error_reporting(E_ALL & ~E_NOTICE);
if (!isset($_SESSION)){
    session_start();
}
if (!isset($_SESSION['logged_user'])) {
    header('Location: index.php');
}
else if (!isset($_GET['userID'])){
    require_once 'config.php';
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli->errno){
        print('There was an error in connecting to the database:');
        print($mysqli->error);
        exit();
    }
    $username = $_SESSION['logged_user'];
    $userQuery = $mysqli -> query("SELECT userID FROM Users WHERE username = '$username'");
    $row = $userQuery -> fetch_assoc();
    $userID = $row['userID'];
    header("Location: test.php?userID=$userID");
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
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
        position: fixed;
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
    <?php include 'navbar.php';
    /* Display all user charts */

    if (isset($_GET['userID'])) {
        require_once 'config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        /*Check if userID = logged_user and display their charts*/
        $userID = $_GET['userID'];
        $username = $_SESSION['logged_user'];
        $query  = "SELECT userID FROM Users WHERE username = '$username'";
        $result = $mysqli->query($query);
        if ($result == false) print("<h1>NOOO</h1>");
        $row = $result -> fetch_assoc();
        $logged_userID = $row['userID'];
        if ($logged_userID == $userID) {
            /*current user = userID*/
            $userID = $_GET['userID'];
            $query  = "SELECT * FROM Charts WHERE userID = $userID";
            $result = $mysqli->query($query);
            if ($result) {
                    /*user has charts, display them*/
                    echo "<div class=\"container\">
                          <div class=\"row\">
                              <h1 class=\"page-title\">Your Charts<h1>
                          </div>";

                    $count = 0;

                    while ($count < 3){
                        echo "<div class=\"row\">";
                        while ($row = $result -> fetch_assoc()) {
                                $chartID = $row['chartID'];
                                $symbol = $row['name'];
                                $startDate = $row['startDate'];
                                $endDate = $row['endDate'];
                                $chartName = $row['chartName'];
                                echo "<div class=\"col-md-4\" id=\"stock\">
                                  <a href=\"viewChartPrivate.php?chartID=$chartID\">
                                      <h1 class=\"symbol\">$symbol</h1>
                                      <h4 class=\"company\">$chartName</h4>
                                      <p class=\"dates\">$startDate - $endDate</p>
                                  </a>
                              </div>";
                              $count++;
                        }
                        echo "</div>";
                        $count = 0;
                        if (!$row) break;
                    }
                  echo '<div class="col-md-4">
                          <a href="makeNew.php">
                          <h1 id="plus-sign">+</h1>
                          </a>
                        </div>';
                  echo "</div>";
                }
                else {
                    print("<h2>Nothing here yet!</h2>");
                    echo '<div class="col-md-4">
                          <a href="makeNew.php">
                          <h1 id="plus-sign">+</h1>
                          </a>
                        </div>';
                }
        }
        else {
            die('This user is not logged in. You must log in to see your charts.');
        }
    }

    ?>
    

    <!--
    <footer>
        <div id="copyright">
            Copyright &copy; 2016 The Web Development Group. All rights reserved.
        </div>
    </footer> 
    -->


</body>
</html>
