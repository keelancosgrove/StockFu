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
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="row">
            <table>
                <tr>
                    <td><h1 class="page-title">GOOG<h1></td>
                </tr>
                <tr>
                    <td><h2 class="page-title">Alphabet Inc.<h2></td>
                    <td><p>Aug. 19, 2004 - Aug. 19, 2014</p></td>
                    <td><button id="edit">Edit Chart</button></td>
                    <td>
                        Make chart public?<br>
                        <input type="checkbox" name="public">
                    </td>
                    <td><a href="test.html"><button id="finish">Finish</button></a></td>
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
                            <a href="test.html">
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
            if (isset($_GET['chartID'])){
                $chartID = $_GET['chartID'];
                $selectedChart = $mysqli -> query("SELECT * FROM Charts WHERE chartID = '$chartID'");
                if ($selectedChart == false) print("Failed to find chart with associated chart ID in database");
                while ($row = $selectedChart -> fetch_assoc()){
                    $svg = $row['svg_string'];
                    print($svg);
                }
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
    </script>
    <footer>
        <!-- Tell people that this is my website do not steal -->
        <div id="copyright">
            Copyright &copy; 2016 The Web Development Group. All rights reserved.
        </div>
	</footer> 
    
</body>
</html>