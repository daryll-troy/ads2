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

<?php
// Include config file
require_once "connect.php";

// Define variables and initialize with empty values
 $dri_username ="";
 $dri_username_err ="";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

     // Validate dri_username
     $input_dri_username = trim($_POST["dri_username"]);
     if (empty($input_dri_username)) {
         $dri_username_err = "Please enter an dri_username.";
     } else {
         $dri_username = $input_dri_username;
     }

     // Get the operator email of this user operator
     $getOpeEmail = "SELECT email_add FROM operators WHERE ope_username = '$userSession'";
     if($getEmailRes = mysqli_query($link, $getOpeEmail)){
         if(mysqli_num_rows($getEmailRes) > 0){
             $getRowEmail = mysqli_fetch_array($getEmailRes);
             $addEmail = $getRowEmail['email_add'];
         }
     }

     // Get the driver's ope_email input by operator to check if it is null
     $getDriOpeEmail = "SELECT ope_email FROM drivers WHERE dri_username = '$dri_username'";
     if($getDriEmailRes = mysqli_query($link, $getDriOpeEmail)){
         if(mysqli_num_rows($getDriEmailRes) > 0){
             $getDriRowEmail = mysqli_fetch_array($getDriEmailRes);
             $addDriOpeEmail = $getDriRowEmail['ope_email'];
             
         }
     }
    
    // Check input errors before inserting in database
    if (empty($dri_username_err) && $addDriOpeEmail == null) {
        // Prepare an insert statement
        $sql = "UPDATE drivers SET ope_email = ? WHERE dri_username =?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss",  $param_dri_username, $param_dri_username_where);
           
            // Set parameters
            $param_dri_username = $addEmail;
            $param_dri_username_where = $dri_username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: ope_dashboard.php");
                exit();
            } else {
                 echo "Oops! Something went wrong. Please try again later.";
            }

        }

        // Close statement
        mysqli_stmt_close($stmt);
    }else{
        
        header("location: noUser.php");
        exit();
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Jeepney</title>
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
                    <h2 class="mt-5">Add Jeepney</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        
                        <div class="form-group">
                            <label>Driver Username</label>
                            <input type="text" name="dri_username" class="form-control <?php echo (!empty($dri_username_err)) ? 'is-invalid' : ''; ?>" value="">
                            <span class="invalid-feedback"><?php echo $dri_username_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="ope_dashboard.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>