<?php
 // Determine the user account for the session
 session_start();
 $userSession = $_SESSION['ope_username'];

// Check if the operator is already logged in, if not then redirect him to index page
    if(!(isset($_SESSION["ope_loggedin"]) && $_SESSION["ope_loggedin"] === true)){
        /* If operator is not logged in but driver is, direct the driver session to index.php
            withou having to logout the driver logged in */
            if(isset($_SESSION["dri_loggedin"]) && $_SESSION["dri_loggedin"] === true){
                header("location: index.php");
                exit();
            }

         header("location: logout.php");
        exit();
    }
    
    // Check if a driver is already logged in, if yes, log him out
    if(isset($_SESSION["dri_loggedin"]) && $_SESSION["dri_loggedin"] === true){
        unset($_SESSION["dri_loggedin"]);
        unset($_SESSION['dri_username']);
        
    }

    // Check if an admin is already logged in, if yes, log him out
if (isset($_SESSION["adm_loggedin"]) && $_SESSION["adm_loggedin"] === true) {
    unset($_SESSION["adm_loggedin"]);
    unset($_SESSION['adm_username']);
}
?>


<!-- Read the details of this operator -->
<?php
// Check existence of operator session parameter before processing further
if (isset($_SESSION['ope_username']) && !empty(trim($_SESSION['ope_username']))){
    // Include config file
    require_once "connect.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM operators WHERE ope_username = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        
        // Set parameters
        $param_username = trim($userSession);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $email = $row["email_add"];
                $username = $row["ope_username"];
                $password = $row["password"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Credentials | Operator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">My Credentials</h1>
                    <div class="form-group">
                        <label>Email</label>
                        <p><b><?php echo $email; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <p><b><?php echo $username; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <p><b><?php echo $password; ?></b></p>
                    </div>
                   
                    <p><a href="ope_dashboard.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>