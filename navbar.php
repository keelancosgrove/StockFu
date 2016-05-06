<?php
	session_start();
	$username = $_SESSION['logged_user'];
    require_once 'config.php';
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli->errno){
        print('There was an error in connecting to the database:');
        print($mysqli->error);
        exit();
    }
    $IDQuery = $mysqli -> query("SELECT userID FROM Users WHERE username = '$username'");
    if ($IDQuery == false) print("Failed to query userID");
    $row = $IDQuery -> fetch_assoc();
    $userID = strval($row['userID']);
	print('
        <div id="header">
            <nav>
                StockFu
                <a href="test.php?userID='.$userID.'" id="navbar-element">Home</a>
                <a href="public.php" id="navbar-element">Public Charts</a>
                <a href="About.php" id="navbar-element">About StockFu</a>
                <a href="logout.php" id="navbar-element">Logout</a>
            </nav>
        </div>
        ');
?>