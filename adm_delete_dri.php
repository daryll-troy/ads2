<?php
// Determine the user account for the session
session_start();


// Check if the operator is already logged in, if not then redirect him to index page
if (!(isset($_SESSION["adm_loggedin"]) && $_SESSION["adm_loggedin"] === true)) {

    header("location: logout.php");
    exit();
}

// Check if a driver is already logged in, if yes, log him out
if (isset($_SESSION["dri_loggedin"]) && $_SESSION["dri_loggedin"] === true) {
    unset($_SESSION["dri_loggedin"]);
    unset($_SESSION['dri_username']);
}

// Check if an operator is already logged in, if yes, log him out
if (isset($_SESSION["ope_loggedin"]) && $_SESSION["ope_loggedin"] === true) {
    unset($_SESSION["ope_loggedin"]);
    unset($_SESSION['ope_username']);
}
?>


<?php
// Process delete operation after confirmation
if (isset($_POST["username"]) && !empty($_POST["username"])) {
    // Include config file
    require_once "connect.php";

     
    // Prepare a delete statement
    $sql = "DELETE FROM drivers WHERE dri_username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_id);

        // Set parameters
        $param_id = trim($_POST["username"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records deleted successfully. Redirect to landing page
            header("location: adm_dashboard.php");
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
                            <input type="hidden" name="username" value="<?php echo trim($_GET["dri_username"]); ?>" />
                            
                            <p>Are you sure you want to delete this operator record?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="adm_dashboard.php" class="btn btn-secondary">No </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>