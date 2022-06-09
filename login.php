<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

  if($_SESSION["designation"] == "admin"){
    //redirecting to the admin dashboard
    header("location: admin.php");
    exit;
  }elseif ($_SESSION["designation"] == "instructor") {
    //redirecting to instructor dashboard
    header("location: dashboard_ins.php");
    exit;
  }elseif ($_SESSION["designation"] == "head") {
    //redirecting to the school head dashboard
    header("location: dashboard_head.php");
    exit;
  }
  else{
    //redirecting to the student and guardian dashboard
    header("location: dashboard_stu.php");
    exit;
  }
}

// Include config file
require_once "db.php";



// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, email, password, designation FROM users WHERE email = ?";

        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                // Check if email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt)){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id , $user, $email, $pwd, $des);
                    if(mysqli_stmt_fetch($stmt)){

                        if(md5($password) == $pwd){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["username"] = $user;
                            $_SESSION["designation"] = $des;

                            // Redirect users to thier homepages
                            if($_SESSION["designation"] == "admin"){
                              header("location: admin.php");
                              exit;
                            }elseif ($_SESSION["designation"] == "instructor") {
                              header("location: dashboard_ins.php");
                              exit;
                            }else{
                              header("location: dashboard_stu.php");
                              exit;
                            }
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                            
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($con);
}
?>

  <!DOCTYPE html>
  <html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Welcome | Login</title>
  </head>
  <body style="background-image: url(pictures/bg_log.jpg);no-repeat center center fixed; 
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
              background-size: cover;">

    <div class="container">

      <h1>Welcome!</h1>
      <br>
      <h2>Basic Education Student Administrative System</h2>
      <br>

      <div class="card shadow-lg bg-white rounded border-none">


        <div class="card-body">
          <h3 class="card-title">Login</h3>

            <form class=" d-flex justify-content-center" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

              <div class="card-text ">
                <div class="row">
                  <div class="mb-3 ">

                    <input type="email" name = "email" class="form-control form-control-lg " title="Follow the correct email syntax e.g someone@somewhere.com" name="email" placeholder="Username" >
                    <?php if ($email_err != "") {
                      echo "<i class = 'text-danger text-center'>".$email_err."</i>";
                    } ?>
                    </div>
                  </div>

                  <div class="row">
                    <div class="mb-3">
                      <input type="password" name = "password" class="form-control form-control-lg" name="pwd" placeholder="Password">
                      <?php if ($password_err != "") {
                      echo "<i class = 'text-danger text-center'>".$password_err."</i>";
                    } ?>
                    </div>
                  </div>
                  <div class="row">
                    <button type="submit" class="btn btn-primary form-control form-control-lg">Submit</button>
                  </div>


                </div>

              </form>
          </div>


        </div>


      </div>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="bootstrap/jsbootstrap.min.js"></script>
    </body>
    </html>
