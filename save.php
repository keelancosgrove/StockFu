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
    $startDate = $chartData -> startDate;
    $endDate = $chartData -> endDate;
    $svg_string = $chartData -> svg;
    $minDate = $chartData -> minDate;
    $maxDate = $chartData -> maxDate;
    $priceYMax = $chartData -> priceYMax;
    $public = $chartData -> publicChart;
    $dates = $chartData -> dates;
    $dateMap = $chartData -> dateMap;

    $varQuery = $mysqli -> query("UPDATE Charts SET chartName='$chartName',
    startDate='$startDate',endDate='$endDate',svg_string='$svg_string',
    minDate='$minDate',maxDate='$maxDate',priceYMax='$priceYMax',public='$public',
    dates='$dates',dateMap='$dateMap' WHERE chartID = $chartID");


?>
