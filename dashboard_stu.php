<?php include("auth_session.php");
require_once "db.php";
ob_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Welcome | Dashboard</title>
  </head>
  <body>



      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#"><?php echo $_SESSION['username']; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a href="pages/ins/browse.php" class = "nav-link">Learning Content</a>
            </li>
            <li class="nav-item">
              <a href="pages/annSTU.php" class = "nav-link">Announcements</a>
            </li>


          </ul>
          <a href="logout.php"><button class="btn btn-outline-primary my-2 my-sm-0" type="submit" >Logout</button></a>
        </div>
      </nav>
<br><br>
<div class="container">
  <h3>Students Dashboard</h3>
  <div class="card shadow-lg bg-white rounded border-none">


    <div class="card-body">
      <h4>Welcome <?php echo $_SESSION['username']; ?></h4>

      <div class="card-text ">
        <h5>My Results</h5>
        <div class="container">
          <div class="card shadow-lg bg-white rounded border-none">
            <div class="card-body">
              <div class="card-text ">
                <h4>Continous Assessment</h4>
                <div class="row">
                  <?php
                  // Generating Monthly tests card
                  $Engine = $con->prepare('SELECT * FROM subjects WHERE stuID=?');
                  $Engine->bind_param('s',$crnt);
                  $crnt = $_SESSION['id'];
                  $Engine->execute();
                  $cRes = $Engine->get_result();
                  if($cRes){
                    while ($row = $cRes->fetch_assoc()){
                      if ($row['type'] == "January" || $row['type'] == "February" || $row['type'] == "March" || $row['type'] == "April" || $row['type'] == "May" || $row['type'] == "June" || $row['type'] == "July" || $row['type'] == "August" || $row['type'] == "September" || $row['type'] == "October" || $row['type'] == "November") {
                        #
                      
                      ?>
                      <div class="col-lg-4">
                        <div class="card shadow-lg bg-white rounded border-none mb-4">
                          <div class=" card-body ">
                            Period : <b><?=$row['type'] ?></b>
                            <hr>
                            <p class="text-info text-center">Subject Scores</p>
                            <hr>
                            English : <?=$row['English'] ?>%
                            <br>
                            Setswana : <?=$row['Setswana'] ?>%
                            <br>
                            Mathematics : <?=$row['Mathematics'] ?>%
                            <br>
                            Science : <?=$row['Science'] ?>%
                            <br>
                            CAPA : <?=$row['CAPA'] ?>%
                            <br>
                            Social Studies : <?=$row['Social_Studies'] ?>%
                            <br>
                            Agriculture : <?=$row['Agriculture'] ?>%
                            <hr>
                            <?php 
                            $allSubs = $row['English'] + $row['Setswana'] + $row['Mathematics'] + $row['Science'] + $row['CAPA'] + $row['Social_Studies'] + $row['Agriculture'];
                             ?>
                            Peformance : <?php echo "<span class='badge badge-warning p-2'>";
                                                printf("%.2f",($allSubs/700) * 100); echo " % ";
                                                echo "</span>&nbsp;";

                                                $scr = ($allSubs/700) * 100;

                                                // Auto generate results comments based on peformance
                                                if($scr > 80.0){
                                                  echo "<span class='badge badge-dark '>Excellent Performance.</span></a>";
                                                }elseif ($scr > 65 && $scr < 79.9) {
                                                    # code...
                                                  echo "<span class='badge badge-success '>Good Performance.</span></a>";
                                                }elseif ($scr > 50 && $scr < 64.9){
                                                  echo "<span class='badge badge-primary '>Room for improvement.</span></a>";
                                                }else{
                                                  echo "<span class='badge badge-danger '>Poor Performance! Needs Assistance.</span></a>";
                                                }  
                                                 ?>
                            
                          </div>
                        </div>
                      </div>
                    <?php 
                    }
                   }
                  } 
                  ?>
                </div>
                <hr>
                <h4>End Of Term Examinations</h4>
                <div class="row">
                  <?php
                  // Generating Monthly tests card
                  $Engine = $con->prepare('SELECT * FROM subjects WHERE stuID=?');
                  $Engine->bind_param('s',$crnt);
                  $crnt = $_SESSION['id'];
                  $Engine->execute();
                  $cRes = $Engine->get_result();
                  if($cRes){
                    while ($row = $cRes->fetch_assoc()){
                      if ($row['type'] == "Term 1" || $row['type'] == "Term 2" || $row['type'] == "Term 3") {
                        #
                      
                      ?>
                      <div class="col-lg-4">
                        <div class="card shadow-lg bg-white rounded border-none mb-4">
                          <div class=" card-body ">
                            Period : <b><?php if ($row['type'] == "Term 3"){ ?>
                              End Of Year Results
                            <?php  }?><?=$row['type'] ?></b>
                            <hr>
                            <p class="text-info text-center">Subject Scores</p>
                            <hr>
                            English : <?=$row['English'] ?>%
                            <br>
                            Setswana : <?=$row['Setswana'] ?>%
                            <br>
                            Mathematics : <?=$row['Mathematics'] ?>%
                            <br>
                            Science : <?=$row['Science'] ?>%
                            <br>
                            CAPA : <?=$row['CAPA'] ?>%
                            <br>
                            Social Studies : <?=$row['Social_Studies'] ?>%
                            <br>
                            Agriculture : <?=$row['Agriculture'] ?>%
                            <hr>
                            <?php 
                            $allSubs = $row['English'] + $row['Setswana'] + $row['Mathematics'] + $row['Science'] + $row['CAPA'] + $row['Social_Studies'] + $row['Agriculture'];
                             ?>
                            Peformance : <?php echo "<span class='badge badge-warning p-2'>";
                                                printf("%.2f",($allSubs/700) * 100); echo " % ";
                                                echo "</span>&nbsp;";

                                                $scr = ($allSubs/700) * 100;

                                                // Auto generate results comments based on peformance
                                                if($scr > 80.0){
                                                  echo "<span class='badge badge-dark '>Excellent Performance.</span></a>";
                                                }elseif ($scr > 65 && $scr < 79.9) {
                                                    # code...
                                                  echo "<span class='badge badge-success '>Good Performance.</span></a>";
                                                }elseif ($scr > 50 && $scr < 64.9){
                                                  echo "<span class='badge badge-primary '>Room for improvement.</span></a>";
                                                }else{
                                                  echo "<span class='badge badge-danger '>Poor Performance! Needs Assistance.</span></a>";
                                                }  
                                                 ?>

                            <hr>
                              <?php if ($row['type'] == "Term 3"): ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" accept-charset="utf-8">
                                <input type="submit" class="btn btn-danger m-1 form-group col-12"  name = "download" value="Donwload Report">
                              </form>
                              <?php endif ?>

                              
                            
                            
                          </div>
                        </div>
                      </div>
                    <?php 
                    }
                   }
                  }
                 
                  ?>
                  <?php 
                  if (isset($_POST['download'])) {
                    header("location: pdf.php");
                  }
                   ?>
                  
                  
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/jsbootstrap.min.js"></script>
  </body>
</html>
