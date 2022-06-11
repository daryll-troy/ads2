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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Operator</title>
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
        <!-- Display the user operator details -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <!-- Display the username of the driver -->
                        <h2 class="pull-left">Welcome operator '<?php echo $userSession?>'!</h2><br>
                    
                        <h2 class="pull-left">My Details</h2>
                        <a href="logout.php" class="btn btn-success pull-right">Logout</a>
                    </div>
                    <?php
                    // Include config file
                    require "connect.php";

                  // Attempt select query execution
                    $sql = "SELECT * FROM operators WHERE ope_username = '$userSession'";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Name</th>";
                                        echo "<th>Contact No</th>";
                                        echo "<th>Address</th>";
                                        echo "<th>Birth Date</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                     
                                     echo "<td>" . $row['fname'] . " " . $row['mname'] . " " . $row['lname'] . "</td>";
                                      echo "<td>" . $row['contactno'] . "</td>";
                                      echo "<td>" . $row['address'] . "</td>";
                                      echo "<td>" . $row['bday'] . "</td>";
                                       echo "<td>";
                                            echo '<a href="ope_read_self.php' . '" class="mr-3" title="View Credentials" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="ope_modify_self.php' . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
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

        <!-- Display the user operator drivers employees' details -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Driver Employees Details</h2>
                    </div>
                    <?php
                    // Include config file
                    require "connect.php ";

                    // Get the operator email of this user operator
                    $getOpeEmail = "SELECT email_add FROM operators WHERE ope_username = '$userSession'";
                    if($getEmailRes = mysqli_query($link, $getOpeEmail)){
                        if(mysqli_num_rows($getEmailRes) > 0){
                            $getRowEmail = mysqli_fetch_array($getEmailRes);
                            $addEmail = $getRowEmail['email_add'];
                        }
                    }
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM drivers WHERE ope_email = '$addEmail'";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        
                                       echo "<th>Name</th>";
                                        echo "<th>Contact No</th>";
                                        echo "<th>Address</th>";
                                        echo "<th>Birth Date</th>";
                                       echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                       
                                      echo "<td>" . $row['fname'] . " " . $row['mname'] . " " . $row['lname'] . "</td>";
                                        echo "<td>" . $row['contactno'] . "</td>";
                                        echo "<td>" . $row['address'] . "</td>";
                                        echo "<td>" . $row['bday'] . "</td>";
                                       
                                      echo "<td>";
                                            echo '<a href="ope_read.php?dri_username='. $row['dri_username'] .'" class="mr-3" title="View Credentials" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';                                         
                                            echo '<a href="ope_delete.php?dri_username='. $row['dri_username'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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

       
    </div>
</body>
</html>