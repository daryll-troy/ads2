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
    
     // Check if the driver is already logged in, if yes then log him out
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
// Include config file
require_once "connect.php";

// Define variables and initialize with empty values
$username = $psw = $fname = $mname = $lname = $contactno = $address = $bday = "";
 $username_err = $psw_err = $fname_err = $mname_err = $lname_err = $contactno_err = $address_err = $bday_err =  "";

// Processing form data when form is submitted
if (isset($_POST["username"]) && !empty($_POST["username"])) {
    

    

    // Validate username
    $input_username = trim($_POST["username"]);
    if (empty($input_username)) {
        $username_err = "Please enter an username.";
    } else {
        $username = $input_username;
    }


    // Validate password
    $input_psw = trim($_POST["psw"]);
    if (empty($input_psw)) {
        $psw_err = "Please enter the password.";
    } else {
        $psw = $input_psw;
    }

    // Validate fname
    $input_fname = trim($_POST["fname"]);
    if (empty($input_fname)) {
        $fname_err = "Please enter an fname.";
    } else {
        $fname = $input_fname; 
    }

    // Validate mname
    $input_mname = trim($_POST["mname"]);
    if (empty($input_mname)) {
        $mname_err = "Please enter an mname.";
    } else {
        $mname = $input_mname;
    }

    // Validate lname
    $input_lname = trim($_POST["lname"]);
    if (empty($input_lname)) {
        $lname_err = "Please enter an lname.";
    } else {
        $lname = $input_lname;
    }

    // Validate contactno
    $input_contactno = trim($_POST["contactno"]);
    if (empty($input_contactno)) {
        $contactno_err = "Please enter an contactno.";
    } else {
        $contactno = $input_contactno;
    }

    // Validate address
    $input_address = trim($_POST["address"]);
    if (empty($input_address)) {
        $address_err = "Please enter an address.";
    } else {
        $address = $input_address;
    }


    // Validate bday
    $input_bday = trim($_POST["bday"]);
    if (empty($input_bday)) {
        $bday_err = "Please enter an bday.";
    } else {
        $bday = $input_bday;
    }

    // Check input errors before inserting in database
    if (
        empty($username_err) && empty($psw_err) && empty($fname_err) && empty($mname_err)
        && empty($lname_err) && empty($contact_err) && empty($address_err) && empty($bday_err)
    ) {
        // Prepare an update statement
        $sql = "UPDATE operators SET ope_username=?, password=?, fname=?, mname=?, lname=?, contactno=?, 
                address=?, bday=? WHERE ope_username=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param(
                $stmt,
                "sssssisss",
               
                $param_username,
                $param_psw,
                $param_fname,
                $param_mname,
                $param_lname,
                $param_contactno,
                $param_address,
                $param_bday,
                $param_where
            );

            // Set parameters
           
            $param_username = $username;
            $param_psw = $psw;
            $param_fname = $fname;
            $param_mname = $mname;
            $param_lname = $lname;
            $param_contactno = $contactno;
            $param_address = $address;
            $param_bday = $bday;
            $param_where = $userSession;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Update the value of $_SESSION["ope_username"] with the new ope_username, if there is a new one
                $_SESSION["ope_username"] = $username;
                // Records updated successfully. Redirect to landing page
                header("location: ope_dashboard.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter before processing further
    if (isset($_SESSION["ope_username"]) && !empty(trim($_SESSION["ope_username"]))) {
        

        // Prepare a select statement
        $sql = "SELECT * FROM operators WHERE ope_username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $userSession;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                  
                    $username = $row["ope_username"];
                    $psw = $row["password"];
                    $fname = $row["fname"];
                    $mname = $row["mname"];
                    $lname = $row["lname"];
                    $contactno = $row["contactno"];
                    $address = $row["address"];
                    $bday = $row["bday"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    echo 'hello world!';
                    // header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
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
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                       
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" name="psw" class="form-control <?php echo (!empty($psw_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $psw; ?>">
                            <span class="invalid-feedback"><?php echo $psw_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="fname" class="form-control <?php echo (!empty($fname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fname; ?>">
                            <span class="invalid-feedback"><?php echo $fname_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" name="mname" class="form-control <?php echo (!empty($mname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $mname; ?>">
                            <span class="invalid-feedback"><?php echo $mname_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="lname" class="form-control <?php echo (!empty($lname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lname; ?>">
                            <span class="invalid-feedback"><?php echo $lname_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Contact No</label>
                            <input type="text" name="contactno" class="form-control <?php echo (!empty($contactno_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $contactno; ?>">
                            <span class="invalid-feedback"><?php echo $contactno_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label> Address</label>
                            <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                            <span class="invalid-feedback"><?php echo $address_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label> Birth Date</label>
                            <input type="text" name="bday" class="form-control <?php echo (!empty($bday_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $bday; ?>">
                            <span class="invalid-feedback"><?php echo $bday_err; ?></span>
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