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
        $data = json_decode($_POST['param']);
        $chartID = $data -> chartID;
        $chartIDQuery = $mysqli -> query("DELETE FROM Charts WHERE chartID = '$chartID'");
        print('{}');
    }
    else print('{"Failure: user not logged in"}');
?>