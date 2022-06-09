<?php include("auth_session.php");
require_once "db.php"; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <title>Instructor Dashboard</title>
</head>
<body style="background-image: url(pictures/bg_log.jpg);no-repeat center center fixed; 
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
              background-size: cover;">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="dashboard_ins.php"><i class="bi bi-house"></i> <?php echo $_SESSION['username']; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">

        <li class="nav-item">
          <a href="pages/ins/stud.php" class = "nav-link">My Students</a>
        </li>
        <li class="nav-item">
          <a href="pages/ins/content.php" class = "nav-link">Learning Content</a>
        </li>
        <li class="nav-item">
          <a href="pages/annINS.php" class = "nav-link">Announcements</a>
        </li>


      </ul>
      <a href="logout.php"><button class="btn btn-outline-primary my-2 my-sm-0" type="submit" >Logout</button></a>
    </div>
  </nav>

  <br>
  <br>

  <div class="container">
    <h3>Instructor Dashboard</h3>
    <div class="card shadow-lg bg-white rounded border-none mb-4">

      <div class="container">
        <h3>My Students</h3>
    <h4>Class Teacher: <?php echo $_SESSION['username']; ?></h4>
    Total Students : <?php
    $totalStudents = $con->prepare('SELECT * FROM students WHERE instructor = ?');
    $totalStudents->bind_param('s',$val);
    $val = $_SESSION["id"];
    $totalStudents->execute();
    $totalStudents->store_result();
    $tt = $totalStudents->num_rows;

    echo $tt;

    ?>
    <br>
      Grade : 
      <?php 
      $grd = $con->prepare("SELECT * FROM instructor WHERE id = ?");
      $grd->bind_param('i',$ses);
      $ses = $_SESSION['id'];
      $grd->execute();
      $res = $grd->get_result();
      if($res){
        while ($row = $res->fetch_assoc()){
          if($row['grade'] > 0){
            echo "Standard - ".$row['grade']."A";
          }else{
            echo "Kindergarten";
          }
        }
      }
      ?>
    <br>
    <div class="card shadow-lg bg-white rounded border-none mb-4">


      <div class="card-body">
        <h4>Student Summary</h4>
        <div class="row">
          <?php
          $Engine = $con->prepare('SELECT * FROM students WHERE instructor=?');
          $Engine->bind_param('s',$crnt);
          $crnt = $_SESSION['id'];
          $Engine->execute();
          $cRes = $Engine->get_result();
          if($cRes){
            while ($row = $cRes->fetch_assoc()){
              ?>
              <div class="col-lg-4">
                <div class="card shadow-lg bg-white rounded border-none mb-4">
                  <div class=" card-body ">
                    Name                       : <?=$row['name']?> <?=$row['surname']?><br>
                    <?php
                    $p = $con->prepare('SELECT * FROM parents WHERE id=?');
                    $p->bind_param('s',$c);
                    $c = $row['id'];
                    $p->execute();
                    $rs = $p->get_result();
                    if($rs){
                      while ($r = $rs->fetch_assoc()){
                        ?>
                        Guardian  : <?=$r['name']?> <?=$r['surname']?><br>
                        <?php $phn = $r['phone'];$eml = $r['email'];
                      }
                    }
                    $p->close(); ?>
                    Guardian Contacts          :
                    <ol>
                      <li>Phone : <?php echo $phn ?></li>

                      <li>Email : <?php echo $eml ?></li>
                    </ol><hr>
                    <h6><span class="label label-default">Average Monthly Test Score :</span></h6><?php 
                    $numMonths = 0;
                    $cummScore = 0;
                      //getting all monthly test for individual students by a while loop
                    $mts = $con->prepare("SELECT * FROM subjects where stuID = ?");
                    $mts->bind_param('i',$cur);
                    $cur = $row['id'];

                    
                    $mts->execute();
                    $res = $mts->get_result();
                    if($res){
                      while($srow = $res->fetch_assoc()){
                        $numMonths = $numMonths + 1;
                        $allSubs = $srow['English'] + $srow['Setswana'] + $srow['Mathematics'] + $srow['Science'] + $srow['CAPA'] + $srow['Social_Studies'] + $srow['Agriculture'];
                        $cummScore = $cummScore + $allSubs;
                      }
                        //check if there were any result for current student card
                      if($numMonths == 0){
                        echo "<b class ='text-warning'>No results recorded yet!</b>";
                      }else{
                        echo "<span class='badge badge-warning p-2'>";
                        printf("%.2f",($cummScore/($numMonths*700)) * 100); echo " % ";
                        echo "</span>&nbsp;";
                        $scr = ($cummScore/($numMonths*700)) * 100;

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
                      }
                    }$mts->close();
                    ?><br>

                    <h6>Term Performance :</h6>
                    <?php 

                    # Getting results for the 3 seperate terms

                    $num_term1 = 0;
                    $num_term2 = 0;
                    $num_term3 = 0;
                    $cummScore1 = 0;
                    $cummScore2 = 0;
                    $cummScore3 = 0;
                      //getting all monthly test for individual students by a while loop
                    $mts = $con->prepare('SELECT * FROM subjects where stuID = ?');
                    $mts->bind_param('i',$cur);
                    $cur = $row['id'];
                    $mts->execute();
                    $res = $mts->get_result();
                    if($res){
                      while($srow = $res->fetch_assoc()){

                        if($srow['type'] == "Term 1"){
                          $num_term1 = $num_term1 + 1;
                          $allSubs = $srow['English'] + $srow['Setswana'] + $srow['Mathematics'] + $srow['Science'] + $srow['CAPA'] + $srow['Social_Studies'] + $srow['Agriculture'];
                          $cummScore1 = $cummScore1 + $allSubs;

                        }elseif($srow['type'] == "Term 2"){
                          $num_term2 = $num_term2 + 1;
                          $allSubs = $srow['English'] + $srow['Setswana'] + $srow['Mathematics'] + $srow['Science'] + $srow['CAPA'] + $srow['Social_Studies'] + $srow['Agriculture'];
                          $cummScore2 = $cummScore2 + $allSubs;
                        }elseif($srow['type'] == "Term 3"){
                          $num_term3 = $num_term3 + 1;
                          $allSubs = $srow['English'] + $srow['Setswana'] + $srow['Mathematics'] + $srow['Science'] + $srow['CAPA'] + $srow['Social_Studies'] + $srow['Agriculture'];
                          $cummScore3 = $cummScore3 + $allSubs;

                        }

                      }
                        //check if there were any result for current student card
                      if($num_term1 == 0){
                        echo "<b>Term 1 :</b><br>";
                        echo "<b class ='text-warning'>No results recorded yet!</b>";
                      }elseif($num_term1 > 0){
                        echo "<b>Term 1 :</b><br>";
                        echo "<span class='badge badge-warning p-2'>";
                        printf("%.2f",($cummScore1/($num_term1*700)) * 100); echo " % ";
                        echo "</span>&nbsp;";
                        $scr = ($cummScore1/($num_term1*700)) * 100;

                        //Auto generate results comments based on peformance
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
                      }
                        //term 2
                      if($num_term2 == 0){
                        echo "<br><b>Term 2 :</b><br>";
                        echo "<b class ='text-warning'>No results recorded yet!</b>";
                      }elseif($num_term2 > 0){
                        echo "<br><b>Term 2 :</b><br>";
                        echo "<span class='badge badge-warning p-2'>";
                        printf("%.2f",($cummScore2/($num_term2*700)) * 100); echo " % ";
                        echo "</span>&nbsp;";
                        $scr = ($cummScore2/($num_term2*700)) * 100;
                        
                        //Auto generate results comments based on peformance
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
                      }
                        //term 3
                      if($num_term3 == 0){
                        echo "<br><b>Term 3 :</b><br>";
                        echo "<b class ='text-warning'>No results recorded yet!</b>";
                      }elseif($num_term3 > 0){
                        echo "<br><b>Term 3 :</b><br>";
                        echo "<span class='badge badge-warning p-2'>";
                        printf("%.2f",($cummScore3/($num_term3*700)) * 100); echo " % ";
                        echo "</span>&nbsp;";
                        $scr = ($cummScore3/($num_term3*700)) * 100;
                        
                        //Auto generate results comments based on peformance
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
                      }
                    }$mts->close(); ?>

                  </div>

                </div>
              </div>
              <?php
            }
          }else{

          }
          $Engine->close();
          ?>
        </div>


      </div>
    </div>

      </div>

      <div class="card-body">
        <h4>Welcome <?php echo $_SESSION['username']; ?></h4>

        <div class="card-text ">
          <p>The website is still in its ealy stages,</p>


        </div>


      </div>
    </div>
  </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/jsbootstrap.min.js"></script>
  </body>
  </html>
