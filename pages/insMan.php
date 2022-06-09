<?php include("auth_session.php"); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>School Management</title>
  </head>
  <body>



      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../admin.php">School: <?php echo $_SESSION['username']; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="stdMan.php">Student Management</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="insMan.php">Instructor Management</a>
            </li>
            <li class="nav-item">
              <a href="announcements.php" class = "nav-link">Announcements</a>
            </li>
          </ul>
            <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Logout</button>

        </div>
      </nav>

      <br><br>

      <div class="container">
        <br>
        <h2>Instructor Management</h2>
        <br>

        <div >
          <ul class="nav nav-pills">
            <li class="nav-item">
              <a href="people/addTeach.php" class="nav-link ">Add Instructor</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">Remove Instructor</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">Modify Instructor Record</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">View Instructor</a>
            </li>
          </ul>

        </div>
        <br>

          <div class="card shadow-lg bg-white rounded border-none">


              <div class="card-body">
                <h3 class="card-title alert alert-danger">Information!</h3>

                <form class="login_form d-flex justify-content-center" action="index.html" method="post">

                  <div class="card-text ">

                    Select an option on the navigation to continue...
                    <br>
                    Teaching Staff Management Pane.


                  </div>

                </form>
              </div>



        </div>




      </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/jsbootstrap.min.js"></script>
  </body>
</html>
