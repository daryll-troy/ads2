<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["adm_loggedin"]) && $_SESSION["adm_loggedin"] === true){
    header("location: adm_dashboard.php");
    exit();
}
 
// Include config file
require_once "connect.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
        $username_err = "";
    }
    
    // Check if password is empty
    if(empty(trim($_POST["psw"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["psw"]);
        $password_err = "";
    }
    
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT adm_username, password FROM admin WHERE adm_username = ? ";
       
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
           
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){ 
                // Store result
                mysqli_stmt_store_result($stmt);

                $test = mysqli_stmt_num_rows($stmt);
                
               
                // Check if username exists, if yes then verify password                
                if(mysqli_stmt_num_rows($stmt) == 1){ 
                    
                    // Bind result variables 
                    mysqli_stmt_bind_result($stmt, $username, $stored_password);
                    if(mysqli_stmt_fetch($stmt)){
                      
                        if($password == $stored_password){         
                            // Password is correct, so start a new session
                            session_start();
                           // Store data in session variables
                            $_SESSION["adm_loggedin"] = true;
                            $_SESSION['adm_username'] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: adm_dashboard.php");
                            exit();
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.1";
                            
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.2";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later."; 
            }
           // Close statement
            mysqli_stmt_close($stmt);
        }
     
    }
     // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login|Admin</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="login.css">
</head>

<body>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="imgcontainer">
            <h1>Login As Admin</h1>
        </div>

        <div class="container">
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>

            <button type="submit">Login</button>
           
        </div>

        <div class="container" style="background-color:#f1f1f1">
            <a href="index.php"><button type="button" class="cancelbtn">Cancel </button></a>
        </div>
    </form>
</body>

</html>