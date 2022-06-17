<?php
 // Determine the user account for the session
 session_start();
 $userSession = $_SESSION['dri_username'];

 // Check if the driver is already logged in, if not then redirect him to index page
    if(!(isset($_SESSION["dri_loggedin"]) && $_SESSION["dri_loggedin"] === true)){
        /* If driver is not logged in but operator is, direct the operator session to index.php
            withou having to logout the operator logged in */
        if(isset($_SESSION["ope_loggedin"]) && $_SESSION["ope_loggedin"] === true){
            header("location: index.php");
            exit();
        }
        
        header("location: logout.php");
        exit();
    }
    
// Check if an operator is already logged in, if yes, log him out
    if(isset($_SESSION["ope_loggedin"]) && $_SESSION["ope_loggedin"] === true){
        unset($_SESSION["ope_loggedin"]);
        unset($_SESSION["ope_username"]);
        //exit();
    }

    // Check if an admin is already logged in, if yes, log him out
if (isset($_SESSION["adm_loggedin"]) && $_SESSION["adm_loggedin"] === true) {
    unset($_SESSION["adm_loggedin"]);
    unset($_SESSION['adm_username']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Driver</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
        
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <!-- View the driver's details -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <!-- Display the username of the driver -->
                        <h2 class="pull-left">Welcome driver '<?php echo $userSession?>'!</h2><br>
                    
                        <h2 class="pull-left">My Details</h2>
                        <a href="logout.php" class="btn btn-success pull-right">Logout</a>
                    </div>
                    <?php
                    // Include config file
                    require "connect.php";
                    
                   
                  // Attempt select query execution
                    $sql = "SELECT * FROM drivers WHERE dri_username = '$userSession'";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Email</th>";
                                             
                                      echo "<th>Name</th>";
                                        echo "<th>Contact No</th>";
                                        echo "<th>Address</th>";
                                        echo "<th>Birth Date</th>";
                                        echo "<th>Hire Date</th>";
                                        echo "<th>End Date</th>";
                                        echo "<th>Status</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                     echo "<td>" . $row['email_add'] . "</td>";
                                     echo "<td>" . $row['fname'] . " " . $row['mname'] . " " . $row['lname'] . "</td>";
                                      echo "<td>" . $row['contactno'] . "</td>";
                                      echo "<td>" . $row['address'] . "</td>";
                                      echo "<td>" . $row['bday'] . "</td>";
                                      echo "<td>" . $row['hire_date'] . "</td>";
                                      echo "<td>" . $row['end_date'] . "</td>";
                                      echo "<td>" . $row['status'] . "</td>";
                                       echo "<td>";
                                            echo '<a href="dri_read_self.php' .'" class="mr-3" title="View Credentials" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="dri_modify_self.php' .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                           
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>

        <!-- View the driver's OPERATOR details -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">My Operator Details</h2>
                    </div>
                    <?php
                    // Include config file
                    require "connect.php";

                     // Get the ope_email of this driver
                     $getDriOpeEmail = "SELECT ope_email FROM drivers WHERE dri_username = '$userSession'";
                     if($getDriEmailRes = mysqli_query($link, $getDriOpeEmail)){
                         if(mysqli_num_rows($getDriEmailRes) > 0){
                             $getDriRowEmail = mysqli_fetch_array($getDriEmailRes);
                             $addDriEmail = $getDriRowEmail['ope_email'];
                         }
                     }        
 

                  // Attempt select query execution
                    $sql = "SELECT * FROM operators WHERE email_add = '$addDriEmail'";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Email</th>";
                                        echo "<th>Name</th>";
                                        echo "<th>Contact No</th>";
                                        echo "<th>Address</th>";
                                        echo "<th>Birth Date</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                     echo "<td>" . $row['email_add'] . "</td>";
                                     echo "<td>" . $row['fname'] . " " . $row['mname'] . " " . $row['lname'] . "</td>";
                                      echo "<td>" . $row['contactno'] . "</td>";
                                      echo "<td>" . $row['address'] . "</td>";
                                      echo "<td>" . $row['bday'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>