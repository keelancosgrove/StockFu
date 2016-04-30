<?php
	// Set up DB connection
    require_once 'config.php';
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli->errno){
        print('There was an error in connecting to the database:');
        print($mysqli->error);
        exit();
    }
    $data = json_decode($_POST['param']);
    $svg = $data -> svg;
    $company = $data -> company;
    $start_date = $data -> start_date;
    $end_date = $data -> end_date;
    $choose = $mysqli -> query("INSERT INTO Charts (userID, name, startDate, endDate, xLabel, yLabel, thumbnail, svg_string) VALUES (1, '$company', '$start_date', '$end_date', 'Date', 'Stock Price', 'N/A', '$svg')");
    if ($choose == false) print("NOONONONONONONONO");
    print('{}');

?>