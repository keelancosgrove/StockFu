<?php
    session_start();
    if (isset($_SESSION['logged_user'])){
    	// Set up DB connection
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
        $userID = $row['userID'];
        $data = json_decode($_POST['param']);
        $svg = $data -> svg;
        $company = $data -> company;
        $start_date = $data -> start_date;
        $end_date = $data -> end_date;
        $chartName = $data -> chartName;
        $minDate = $data -> minDate;
        $maxDate = $data -> maxDate;
        $priceYMax = $data -> priceYMax;
        $choose = $mysqli -> query("INSERT INTO Charts (userID, name, chartName, startDate, endDate, xLabel, yLabel, thumbnail, svg_string, minDate, maxDate, priceYMax) VALUES ('$userID', '$company', '$chartName','$start_date', '$end_date', 'Date', 'Stock Price', 'N/A', '$svg', '$minDate', '$maxDate', '$priceYMax')");
        print('{}');
    }
    else print('{"Failure: user not logged in"}');
?>