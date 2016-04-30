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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
    $(function() {
        $(".datePicker").datepicker({
            changeMonth: true,
            changeYear: true
        });
        var tags = ["blue","blargh","green"];
        $(".stock").autocomplete({
            source: tags
        });
    });
    </script>
    <title>StockFu | New Chart</title>
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
        padding: 30px;
        text-align: center;
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
            <h1 class="page-title">Make a new chart<h1>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table>
                    <tr>
                        <td>
                            Stock name or symbol:<br>
                            <!--<input type="text" name="stock">-->
                            <input class="stock" name="stock1" />
                            <input type="button" name="addstock" id="add" value="+">
                        </td>
                        <td>
                            Start Date:<br>
                            <input type="text" name="startDate" class="datePicker">
                        </td>
                        <td>
                            End Date:<br>
                            <input type="text" name="endDate" class="datePicker">
                        </td>
                        <td>
                            Type of chart you want to show:<br>
                            <input type="radio" name="stockValue" value="Open"> Open<br>
                            <input type="radio" name="stockValue" value="High"> High<br>
                            <input type="radio" name="stockValue" value="Low"> Low
                        </td>
                        <td>
                            Make chart public?<br>
                            <input type="checkbox" name="public">
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
                            <input class="stock" name="stock2">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <img src="sample.png" id="stockChart">
    </div>
    <script type="text/javascript">
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