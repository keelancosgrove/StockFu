<?php session_start(); ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <title>StockFu Login</title>
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



<body>
    <?php
    //Retrieves filtered username and password from user input
    $username = filter_input(INPUT_POST, 'Username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'Password', FILTER_SANITIZE_STRING);
    if (empty($username) || empty($password)){
    //Form only displayed if either username or password are blank
    ?>
	<form method="post">
        Login Here:
        <br>
        <br>
        <label>Username</label>
        <br>
        <input type="text" name="Username">
        <br>
        <label>Password</label>
        <br>
        <input type="password" name="Password">
        <br>
        <input type="submit" name="submit" value="Login">
    </form>
    <?php
    }
    else {
        //Retrieves all records in users table with matching username
        $possibleUsers = $mysqli -> query("SELECT * FROM Users WHERE username='$username' LIMIT 1");
        if ($possibleUsers){
            $row = $possibleUsers -> fetch_assoc();
            $hashP = $row['hashedPassword'];
            if (password_verify($password,$hashP)){
                //Passwords match - allow user to log in, and update SESSION variable
                print("You have logged in successfully, $username.");
                $_SESSION['logged_user'] = $username;
            }
            else {
                print("You did not login successfully. Please make sure your username and password are correct.");
                print("<p><a href=\"StockFuLogin.php\">Click here to login</a></p>");
            }
        }
        else {
            print("You did not login successfully. Please make sure your username and password are correct.");
            print("<p><a href=\"StockFuLogin.php\">Click here to login</a></p>");
        }
    }
    ?>

    <br><br><br><br><br><br><br>
    <form method="post">
        Are you new? Create a user:
        <br>
        <br>
        <label>Username</label>
        <br>
        <input type="text" name="newUser">
        <br>
        <label>Password</label>
        <br>
        <input type="password" name="newPass">
        <br>
        <label>Retype Password</label>
        <br>
        <input type="password" name="newPassTwo">
        <br>
        <input type="submit" name="submitNew" value="Create User">

        <?php
            $submitNew = isset($_POST["submitNew"])?$_POST["submitNew"]:"";
            if ($submitNew){
                $validated = true;
                $message = "";
                //Retrieves input username and passwords
                $newUser = htmlentities(isset($_POST["newUser"])?$_POST["newUser"]:"");
                $newPass = htmlentities(isset($_POST["newPass"])?$_POST["newPass"]:"");
                $newPassTwo = htmlentities(isset($_POST["newPassTwo"])?$_POST["newPassTwo"]:"");
                //Validates inputs - passwords must match, username/password cannot be too long or blank
                if ($newPass !== $newPassTwo){
                    $validated = false;
                    $message = "Please make sure your passwords match";
                }
                if (strlen($newUser)>25 || strlen($newPass)>25 || $newUser == "" || $newPass == ""){
                    $validated = false;
                    $message = "Your username and password must not be empty or longer than 20 characters";
                }
                if ($validated){
                    //Inserts username and hashed version of the password into users
                    $hashedP = password_hash($newPass,PASSWORD_DEFAULT);
                    $addQuery = $mysqli -> query("INSERT INTO Users (username,hashedPassword) VALUES ('$newUser','$hashedP')");
                    if ($addQuery == false){
                        $message = "Failed to add user";
                    }
                    else $message = "User successfully added!";
                }
                print("<p>$message</p>");
            }
        ?>
    </form>
    <footer>
        <!-- Tell people that this is my website do not steal -->
        <div id="copyright">
            Copyright &copy; 2016 Kevin Guo. All rights reserved.
        </div>
	</footer> 
    
</body>
</html>