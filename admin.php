<?php include("auth_session.php"); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>School Management</title>
  </head>
  <body style="background-image: url(pictures/bg_log.jpg);no-repeat center center fixed; 
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
              background-size: cover;">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">School: <?php echo $_SESSION['username']; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="pages/people/usrmgt.php">User Management</a>
            </li>
          </ul>
            <a href="logout.php"><button class="btn btn-outline-primary my-2 my-sm-0" type="submit" >Logout</button></a>

        </div>
      </nav>

      <br><br>

      <div class="container">
        <br>
        <h2>Basic Education Student Administrative System</h2>
        <br>

        <div class="card shadow-lg bg-white rounded border-none">


            <div class="card-body">
              <h3 class="card-title alert alert-danger">Information!</h3>

              <form class="login_form d-flex justify-content-center" action="index.html" method="post">

                <div class="card-text ">

                  This is where you can manage school teaching staff and the students.

                  This account lets the user to <strong>add/modify/delete</strong> instructor and student records. The system can only be populated by this manner in order to improve effeciency in controlling access to the system.
                  Users added by you can use the credentials specified by you to login to the system.


                </div>

              </form>
            </div>


        </div>


      </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/jsbootstrap.min.js"></script>
  </body>
</html>
