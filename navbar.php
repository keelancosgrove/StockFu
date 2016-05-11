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
    $home = 'test.php?userID='.$userID;
	print('
        <div id="header">
            <div id="yay">
                <b>StockFu</b>
                <ul id="navlist">
                   <li> <a href='.$home.' class="navbar-element" name='.$home.'>Home</a></li>
                    <li><a href="public.php" class="navbar-element" name="public.php">Public Charts</a></li>
                    <li><a href="About.php" class="navbar-element" name="About.php">About StockFu</a></li>
                    <li><a href="logout.php" class="navbar-element" name="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
        ');
?>
