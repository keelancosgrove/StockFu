<?php
	require_once 'config.php';
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli->errno){
        print('There was an error in connecting to the database:');
        print($mysqli->error);
        exit();
    }
    $chartData = json_decode($_POST["param"]);
    $chartID = $chartData -> chartID;
    $varQuery = $mysqli -> query("SELECT * FROM Charts WHERE chartID = '$chartID'");
    $row = $varQuery -> fetch_assoc();
    $data = array();
    $data[] = $row['minDate'];
    $data[] = $row['maxDate'];
    $data[] = $row['priceYMax'];
    $data[] = $row['dates'];
    $data[] = $row['dateMap'];
		$data[] = $row['name'];
		$data[] = $row['startDate'];
		$data[] = $row['endDate'];
		$data[] = $row['chartName'];
    echo json_encode($data);
?>
