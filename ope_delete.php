<?php
 // Determine the user account for the session
 session_start();

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
    
    // Check if the driver is already logged in, if yes, log him out
    if(isset($_SESSION["dri_loggedin"]) && $_SESSION["dri_loggedin"] === true){
        unset($_SESSION["dri_loggedin"]);
        unset($_SESSION['dri_username']);
        //exit();
    }

    // Check if an admin is already logged in, if yes, log him out
    if (isset($_SESSION["adm_loggedin"]) && $_SESSION["adm_loggedin"] === true) {
        unset($_SESSION["adm_loggedin"]);
        unset($_SESSION['adm_username']);
    }
?>

<?php
// Process delete operation after confirmation
if (isset($_POST["dri_username"]) && !empty($_POST["dri_username"])) {
    // Include config file
    require_once "connect.php";

    // Prepare a delete statement
    $sql = "UPDATE drivers SET ope_email = null, end_date = CURDATE(), status= 'Inactive' WHERE dri_username =?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_dri_username);

        // Set parameters
        $param_dri_username = trim($_POST["dri_username"]);
       

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records deleted successfully. Redirect to landing page
            header("location: ope_dashboard.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter
    if (empty(trim($_GET["dri_username"]))) {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
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
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                        <input type="hidden" name="dri_username" value="<?php echo trim($_GET["dri_username"]); ?>" />
                            
                            <p>Are you sure you want to remove this driver from your employees?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="ope_dashboard.php" class="btn btn-secondary">No </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>