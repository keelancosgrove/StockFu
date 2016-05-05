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
    <!--JavaScript-->	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockFu | View Your Chart</title>
    <?php
        require_once 'config.php';
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        if ($mysqli->errno){
            print('There was an error in connecting to the database:');
            print($mysqli->error);
            exit();
        }
    ?>
</head>

<style type="text/css">
    footer{
        position: fixed;
        bottom: 0px;
        left: 0px;
        right: 0px;
        height: 50px;
        color: white;
        text-align: center;
        background: #9C9A9A;
    }
    td{
        padding-left: 30px;
        padding-right: 30px;
        padding-bottom: 5px;
        padding-top: 5px;
        text-align: center;
    }
    h1{
        font-size: 75px;
    }
    .stockChart{
        border-style: solid;
        border-color: black;
        border-width: 5px;
        width: 75%;
        display: block;
        margin-left: auto;
        margin-right: auto;
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
</style>

<body>
    <?php include 'navbar.php'; 
    if (isset($_GET['chartID'])){
        $chartID = $_GET['chartID'];
        $selectedChart = $mysqli -> query("SELECT * FROM Charts WHERE chartID = '$chartID'");
        if ($selectedChart == false) print("Failed to find chart with associated chart ID in database");
        $row = $selectedChart -> fetch_assoc();
    ?>
    <div class="container">
        <div class="row">
            <table>
                <tr>
                    <td><h1 class="page-title">
                    <?php
                        $symbol = $row['name'];
                        print($symbol);
                    ?>
                    <h1></td>
                <tr>
                    <td><p>
                    <?php
                        $chartName = $row['chartName'];
                        $start_date = $row['startDate'];
                        $end_date = $row['endDate'];
                        print("<h2 class=\"page-title\">$chartName</h2>");
                        print("$start_date - $end_date");
                    ?>
                    </p></td>
                    <td><button id="edit">Edit Chart</button></td>
                    <td><button id="delete">Delete Chart</button></td>
                    <td>
                        Make chart public?<br>
                        <input type="checkbox" name="public">
                    </td>
                    <td><a href="test.php"><button id="finish">Finish</button></a></td>
                </tr>
            </table>
        </div>

        <div class="row" id="editPane" style="display: none;">
            <div class="col-md-12">
                <table>
                    <tr>
                        <td>
                            Stock name or symbol:<br>
                            <input type="text" name="stock" value="GOOG">
                            <input type="button" name="addstock" id="add" value="+">
                        </td>
                        <td>
                            Start Date:<br>
                            <input type="button" name="startDate" value="Start Date">
                        </td>
                        <td>
                            End Date:<br>
                            <input type="button" name="endDate" value="End Date">
                        </td>
                        <td>
                            Type of chart you want to show:<br>
                            <input type="button" name="type" value="Choose One">
                        </td>

                        <td>
                            <a href="test.php">
                                <input type="button" name="finish" value="Finish">
                            </a>
                        </td>
                    </tr>
                    <tr id="secondOne" style="display: none;">
                        <td>
                            Stock name or symbol:<br>
                            <input type="text" name="stock">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
            $svg = $row['svg_string'];
            print($svg);
        }
        ?>
    </div>
    <script type="text/javascript">
        $('#edit').click(function(){
            $('#editPane').toggle("fast");
        });
        $('#add').click(function(){
            $('#secondOne').toggle("fast");
        });
        $('#delete').click(function(){
            if (confirm("Are you sure that you want to delete this chart?")){
                var chartID = location.search.split('chartID=')[1];
                console.log(chartID);
                var params = JSON.stringify({
                    chartID : chartID
                });
                $.ajax({
                    type: 'POST',
                    url: 'removeChart.php',
                    data: {'param' : params},
                    datatype: 'json'
                })
                .done( function(data){
                    console.log("Succeeded");
                    console.log(data);
                    window.location.replace("test.php");
                })
                .fail( function(data){
                    console.log("Failed");
                    console.log(data);
                });
            }
        });
    </script>

    <!--
    <footer>
        <div id="copyright">
            Copyright &copy; 2016 The Web Development Group. All rights reserved.
        </div>
	</footer> 
    -->
    
</body>
</html>