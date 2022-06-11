<?php
// connect to localhost
require_once 'connect.php';


// Define variables and initialize with empty values
$email = $username = $psw = $fname = $mname = $lname = $contactno = $address = $bday = "";
$email_err = $username_err = $psw_err = $fname_err = $mname_err = $lname_err = $contactno_err = $address_err = $bday_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    $input_email = trim($_POST["email"]);
    if (empty($input_email)) {
        $email_err = "Please enter a email.";
    } elseif (!filter_var($input_email, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $email_err = "Please enter a valid email.";
    } else {
        $email = $input_email;
        $email_err = "";
    }

    // Validate username
    $input_username = trim($_POST["username"]);
    if (empty($input_username)) {
        $username_err = "Please enter an username.";
    } else {
        $username = $input_username;
        $username_err = "";
    }

    // Validate password
    $input_psw = trim($_POST["psw"]);
    if (empty($input_psw)) {
        $psw_err = "Please enter the password.";
    } else {
        $psw = $input_psw;
        $psw_err = "";
    }

    // Validate fname
    $input_fname = trim($_POST["fname"]);
    if (empty($input_fname)) {
        $fname_err = "Please enter an fname.";
    } else {
        $fname = $input_fname;
        $fname_err = "";
    }

    // Validate mname
    $input_mname = trim($_POST["mname"]);
    if (empty($input_mname)) {
        $mname_err = "Please enter an mname.";
    } else {
        $mname = $input_mname;
        $mname_err = "";
    }

    // Validate lname
    $input_lname = trim($_POST["lname"]);
    if (empty($input_lname)) {
        $lname_err = "Please enter an lname.";
    } else {
        $lname = $input_lname;
        $lname_err = "";
    }

    // Validate contactno
    $input_contactno = trim($_POST["contactno"]);
    if (empty($input_contactno)) {
        $contactno_err = "Please enter an contactno.";
    } else {
        $contactno = $input_contactno;
        $contactno_err = "";
    }

    // Validate address
    $input_address = trim($_POST["address"]);
    if (empty($input_address)) {
        $address_err = "Please enter an address.";
    } else {
        $address = $input_address;
        $address_err = "";
    }

    
    // Validate bday
    $input_bday = $_POST["bday"];
    if (empty($input_bday)) {
        $bday_err = "Please enter an bday.";
    } else {
        $bday = $input_bday;
        $bday_err = "";
    }

    // Check input errors before inserting in database
    if (
        empty($email_err) && empty($username_err) && empty($psw_err) && empty($fname_err) && empty($mname_err)
        && empty($lname_err) && empty($contact_err) && empty($address_err) && empty($bday_err)
    ) {
        // Prepare an insert statement
        $sql = "INSERT INTO operators (email_add, ope_username, password, fname, mname, lname,
                contactno, address, bday) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param(
                $stmt,
                "sssssssss",
                $param_email,
                $param_username,
                $param_psw,
                $param_fname,
                $param_mname,
                $param_lname,
                $param_contactno,
                $param_address,
                $param_bday
            );

            // Set parameters
            $param_email = $email;
            $param_username = $username;
            $param_psw = $psw;
            $param_fname = $fname;
            $param_mname = $mname;
            $param_lname = $lname;
            $param_contactno = $contactno;
            $param_address = $address;
            $param_bday = $bday;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
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
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register_ope</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="register.css">
</head>

<body>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="container">
            <h1>Register as Operator</h1>
            <p>Please fill in this form to create an account.</p>
            <hr>

            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" id="email" required>

            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Username" name="username" id="username" required>

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" id="psw" required>

            <label for="fname"><b>First Name</b></label>
            <input type="text" placeholder="First Name" name="fname" id="fname" required>

            <label for="mname"><b>Middle Name</b></label>
            <input type="text" placeholder="Middle Name" name="mname" id="mname" required>

            <label for="lname"><b>Last Name</b></label>
            <input type="text" placeholder="Last Name" name="lname" id="lname" required>

            <label for="contactno"><b>Contact No</b></label><br>
            <input type="number" placeholder="Contact No" name="contactno" id="contactno" required>
            <br><br>

            <label for="address"><b>Address</b></label>
            <input type="text" placeholder="Address" name="address" id="address" required>

            <label for="bday"><b>Birth Date</b></label><br>
            <input type="text" placeholder="Birth Date" name="bday" id="bday" required>

            <hr>


            <button type="submit" class="registerbtn">Register</button>
        </div>

        <div class="container signin">
            <p>Already have an account? <a href="login_ope.php">Sign in</a>.</p>
        </div>
    </form>
</body>

</html>