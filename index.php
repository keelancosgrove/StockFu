<?php
//Username is Cat
//Password is Dog
//Or you can create a new user/password

session_start();

if (isset($_SESSION['logged_user'])){
  require_once("config.php");
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $username = $_SESSION['logged_user'];
  $query  = "SELECT userID FROM Users WHERE username = '$username'";
  $result = $mysqli->query($query);
  if ($result == false) print("Failed");
  $row = $result->fetch_assoc();
  $userID = $row['userID'];
  header("Location: home.php?userID=$userID");
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
    <!--JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockFu | Login</title>
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
    <div class="container">
        <?php include 'altNavBar.php'; ?>

        <div class="row">
            <div>
                <h1 class="login-title">Login</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 login-col">
                <div class="login-form">
                    <form method="post">
                        <table>
                            <tr><td colspan="2" class="label"><b>Login Here:</b></td></tr>
                            <tr>
                                <td class="label">Username:</td>
                                <td><input type="text" name="Username"></td>
                            </tr>
                            <tr>
                                <td class="label">Password:</td>
                                <td><input type="password" name="Password"></td>
                            </tr>
                            <tr><td></td><td><input type="submit" name="submit" value="Login"></td></tr>
                        </table>
                    </form>
                </div>
            </div>
            <div class="col-md-6 login-col">
                <div class="login-form">
                    <form method="post">
                        <table>
                            <tr><td colspan="2" class="label"><b>Are you new? Create a user:</b></td></tr>
                            <tr>
                                <td class="label">Username:</td>
                                <td><input type="text" name="newUser"></td>
                            </tr>
                            <tr>
                                <td class="label">Password:</td>
                                <td><input type="password" name="newPass"></td>
                            </tr>
                            <tr><td></td><td><input type="submit" name="submitNew" value="Create User"></td></tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <?php
        //Retrieves filtered username and password from user input
        $username = filter_input(INPUT_POST, 'Username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'Password', FILTER_SANITIZE_STRING);
        if (empty($username) || empty($password)){
        //Form only displayed if either username or password are blank
        ?>

        <?php
        }
        else {
            //Retrieves all records in users table with matching username
            $possibleUsers = $mysqli -> query("SELECT * FROM Users WHERE username='$username' LIMIT 1");
            if ($possibleUsers){
                $row = $possibleUsers -> fetch_assoc();
                $hashP = $row['hashedPassword'];
                $userID = $row['userID'];
                if (password_verify($password,$hashP)){
                    //Passwords match - allow user to log in, and update SESSION variable
                    //print("You have logged in successfully, $username.");
                    $_SESSION['logged_user'] = $username;
                    echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=home.php?userID=$userID\">";
                }
                else {
                    print("You did not login successfully. Please make sure your username and password are correct.");
                    //print("<p><a href=\"StockFuLogin.php\">Click here to login</a></p>");
                }
            }
            else {
                print("You did not login successfully. Please make sure your username and password are correct.");
                //print("<p><a href=\"StockFuLogin.php\">Click here to login</a></p>");
            }
        }
        ?>
        <?php
            $submitNew = isset($_POST["submitNew"])?$_POST["submitNew"]:"";
            if ($submitNew){
                $validated = true;
                $message = "";
                //Retrieves input username and passwords
                $newUser = htmlentities(isset($_POST["newUser"])?$_POST["newUser"]:"");
                $newPass = htmlentities(isset($_POST["newPass"])?$_POST["newPass"]:"");
                //Validates inputs -  username/password cannot be too long or blank
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
                    else{ $message = "User successfully added!";
                    }
                }
                print("$message");
            }
        ?>

        <div id="footer">
            <footer>
                Copyright &copy; 2016 The Web Development Group. All rights reserved.
            </footer>
        </div>
    </div>
</body>
</html>
