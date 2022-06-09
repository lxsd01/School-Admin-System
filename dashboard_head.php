<?php include("auth_session.php");
require_once "db.php"; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <title>School Head Dashboard</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="dashboard_head.php"><i class="bi bi-house"></i> <?php echo $_SESSION['username']; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">

        <li class="nav-item">
          <a href="dashboard_head.php" class = "nav-link active">Students Summary</a>
        </li>
        <li class="nav-item">
          <a href="pages/announcements.php" class = "nav-link">Announcements & Discussions</a>
        </li>


      </ul>
      <a href="logout.php"><button class="btn btn-outline-primary my-2 my-sm-0" type="submit" >Logout</button></a>
    </div>
  </nav>

  <br>
  <br>

  <div class="container">
    <h3>School Peformance Summary</h3>
    <div class="card shadow-lg bg-white rounded border-none mb-4">

      <div class="container">
        <h3>Students</h3>
        <h5>Total Students : <?php $all = $con->prepare('SELECT COUNT(*) FROM students;'); $all->execute(); $ttl = $all->get_result(); if($ttl){$all_students = $ttl->fetch_row(); echo $all_students[0];}?></h5>
    <br>
    <div class="card shadow-lg bg-white rounded border-none mb-4">


      <div class="card-body">
        <h4>Peformance Summary</h4><br><hr>
        <h4 class="text-center text-danger text-capitalize">Standard 7</h4> <br><hr>
        <div class="row">
          
          <?php

          $Engine = $con->prepare('SELECT * FROM students WHERE grade=?');
          $Engine->bind_param('i',$crnt);
          $crnt = 7;
          $Engine->execute();
          $cRes = $Engine->get_result();
          if($cRes){
            while ($row = $cRes->fetch_assoc()){
              ?>
              <div class="col-lg-4">
                <div class="card shadow-lg bg-white rounded border-none mb-4">
                  <div class=" card-body ">
                    <h4 class="text-center text-danger">Class :A</h4>
                    
                    <?php
                    $p = $con->prepare('SELECT * FROM students WHERE grade=?');
                    $p->bind_param('i',$c);
                    $c = 7;
                    $p->execute();
                    $rs = $p->get_result();
                    if($rs){
                      $total= $recMult = $totalMarks = 0;
                      while ($r = $rs->fetch_assoc()){
                        $total += 1;
                        $recMult +=1;
                        $mrks = $con->prepare('SELECT * FROM students INNER JOIN subjects ON students.id=subjects.stuID WHERE students.id = ? AND students.grade = ?; '); 
                        $mrks->bind_param('ii',$sid,$curGD);
                        $sid = $r['id'];
                        $curGD = 7;
                        $sid =  $mrks->execute(); 
                        $ttlmrks = $mrks->get_result();
                        //multiple entries for a single student multiplier constant
                        $multiplier = 0;
                        //check for records for students
                        $hasRecord = 0;
                        if($ttlmrks){
                          while ($srow = $ttlmrks->fetch_assoc()) {
                            if ($srow['English'] != 0 && $srow['English'] != null) {
                              $totalMarks += $srow['English'];
                              $hasRecord = 1;
                            }
                            if ($srow['Setswana'] != 0 && $srow['Setswana'] != null) {
                              $totalMarks += $srow['Setswana'];
                              $hasRecord = 1;
                            }
                            if ($srow['Mathematics'] !=0 && $srow['Mathematics'] != null) {
                              # code...
                              $totalMarks += $srow['Mathematics'];
                              $hasRecord = 1;
                            }
                            if ($srow['Science'] != 0 && $srow['Science'] != null) {
                              # code...
                              $totalMarks += $srow['Science'];
                              $hasRecord = 1;
                            }
                            if ($srow['CAPA'] != 0 && $srow['CAPA'] != null) {
                              # code...
                              $totalMarks += $srow['CAPA'];
                              $hasRecord = 1;
                            }
                            if ($srow['Social_Studies'] != 0 && $srow['Social_Studies'] != null) {
                              # code...
                              $totalMarks += $srow['Social_Studies'];
                              $hasRecord = 1;
                            }
                            if ($srow['Agriculture'] != 0 && $srow['Agriculture'] != null) {
                              # code...
                              $totalMarks += $srow['Agriculture'];
                              $hasRecord = 1;
                            }
                            
                            $multiplier += 1;
                          }
                          if ($hasRecord = 0) {
                              $recMult -= 1;
                            }
                        }
                         
                      }
                    }else{
                      echo "<b class ='text-warning'>No results recorded yet!</b>";
                    }
                    $p->close(); ?>
                    Total Students : <?php echo $total,'<br>'; ?>
                    Average CA : <?php
                    if ($multiplier == 0) {
                          # code...
                          $multiplier = 1;
                        } 
                    echo "<span class='badge badge-warning p-2'>";
                        printf("%.2f",($totalMarks/(700*$multiplier)) * 100); echo " % ";
                        echo "</span>&nbsp;"; ?><br>
                    
                    <h6>Average Term Performance :</h6>
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
        <hr>
        <h4 class="text-center text-danger text-capitalize">Standard 6</h4> <br><hr>
        <div class="row">
          
          <?php

          $Engine = $con->prepare('SELECT * FROM students WHERE grade=?');
          $Engine->bind_param('i',$crnt);
          $crnt = 6;
          $Engine->execute();
          $cRes = $Engine->get_result();
          if($cRes){
            while ($row = $cRes->fetch_assoc()){
              ?>
              <div class="col-lg-4">
                <div class="card shadow-lg bg-white rounded border-none mb-4">
                  <div class=" card-body ">
                    <h4 class="text-center text-danger">Class :A</h4>
                    
                    <?php
                    $p = $con->prepare('SELECT * FROM students WHERE grade=?');
                    $p->bind_param('i',$c);
                    $c = 6;
                    $p->execute();
                    $rs = $p->get_result();
                    if($rs){
                      $total= $recMult = $totalMarks = 0;
                      while ($r = $rs->fetch_assoc()){
                        $total += 1;
                        $recMult +=1;
                        $mrks = $con->prepare('SELECT * FROM students INNER JOIN subjects ON students.id=subjects.stuID WHERE students.id = ? AND students.grade = ?; '); 
                        $mrks->bind_param('ii',$sid,$curGD);
                        $sid = $r['id'];
                        $curGD = 6;
                        $sid =  $mrks->execute(); 
                        $ttlmrks = $mrks->get_result();
                        //multiple entries for a single student multiplier constant
                        $multiplier = 0;
                        //check for records for students
                        $hasRecord = 0;
                        if($ttlmrks){
                          while ($srow = $ttlmrks->fetch_assoc()) {
                            if ($srow['English'] != 0 && $srow['English'] != null) {
                              $totalMarks += $srow['English'];
                              $hasRecord = 1;
                            }
                            if ($srow['Setswana'] != 0 && $srow['Setswana'] != null) {
                              $totalMarks += $srow['Setswana'];
                              $hasRecord = 1;
                            }
                            if ($srow['Mathematics'] !=0 && $srow['Mathematics'] != null) {
                              # code...
                              $totalMarks += $srow['Mathematics'];
                              $hasRecord = 1;
                            }
                            if ($srow['Science'] != 0 && $srow['Science'] != null) {
                              # code...
                              $totalMarks += $srow['Science'];
                              $hasRecord = 1;
                            }
                            if ($srow['CAPA'] != 0 && $srow['CAPA'] != null) {
                              # code...
                              $totalMarks += $srow['CAPA'];
                              $hasRecord = 1;
                            }
                            if ($srow['Social_Studies'] != 0 && $srow['Social_Studies'] != null) {
                              # code...
                              $totalMarks += $srow['Social_Studies'];
                              $hasRecord = 1;
                            }
                            if ($srow['Agriculture'] != 0 && $srow['Agriculture'] != null) {
                              # code...
                              $totalMarks += $srow['Agriculture'];
                              $hasRecord = 1;
                            }
                            
                            $multiplier += 1;
                          }
                          if ($hasRecord = 0) {
                              $recMult -= 1;
                            }
                        }
                         
                      }
                    }else{
                      echo "<b class ='text-warning'>No results recorded yet!</b>";
                    }
                    $p->close(); ?>
                    Total Students : <?php echo $total,'<br>'; ?>
                    Average CA : <?php 
                    if ($multiplier == 0) {
                          # code...
                          $multiplier = 1;
                        }
                    echo "<span class='badge badge-warning p-2'>";
                        printf("%.2f",($totalMarks/(700*$multiplier)) * 100); echo " % ";
                        echo "</span>&nbsp;"; ?><br>
                    
                    <h6>Average Term Performance :</h6>
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
        <hr>
        <h4 class="text-center text-danger text-capitalize">Standard 5</h4> <br><hr>
        <div class="row">
          
          <?php

          $Engine = $con->prepare('SELECT * FROM students WHERE grade=?');
          $Engine->bind_param('i',$crnt);
          $crnt = 5;
          $Engine->execute();
          $cRes = $Engine->get_result();
          if($cRes){
            while ($row = $cRes->fetch_assoc()){
              ?>
              <div class="col-lg-4">
                <div class="card shadow-lg bg-white rounded border-none mb-4">
                  <div class=" card-body ">
                    <h4 class="text-center text-danger">Class :A</h4>
                    
                    <?php
                    $p = $con->prepare('SELECT * FROM students WHERE grade=?');
                    $p->bind_param('i',$c);
                    $c = 5;
                    $p->execute();
                    $rs = $p->get_result();
                    if($rs){
                      $total= $recMult = $totalMarks = 0;
                      while ($r = $rs->fetch_assoc()){
                        $total += 1;
                        $recMult +=1;
                        $mrks = $con->prepare('SELECT * FROM students INNER JOIN subjects ON students.id=subjects.stuID WHERE students.id = ? AND students.grade = ?; '); 
                        $mrks->bind_param('ii',$sid,$curGD);
                        $sid = $r['id'];
                        $curGD = 5;
                        $sid =  $mrks->execute(); 
                        $ttlmrks = $mrks->get_result();
                        //multiple entries for a single student multiplier constant
                        $multiplier = 0;
                        //check for records for students
                        $hasRecord = 0;
                        if($ttlmrks){
                          while ($srow = $ttlmrks->fetch_assoc()) {
                            if ($srow['English'] != 0 && $srow['English'] != null) {
                              $totalMarks += $srow['English'];
                              $hasRecord = 1;
                            }
                            if ($srow['Setswana'] != 0 && $srow['Setswana'] != null) {
                              $totalMarks += $srow['Setswana'];
                              $hasRecord = 1;
                            }
                            if ($srow['Mathematics'] !=0 && $srow['Mathematics'] != null) {
                              # code...
                              $totalMarks += $srow['Mathematics'];
                              $hasRecord = 1;
                            }
                            if ($srow['Science'] != 0 && $srow['Science'] != null) {
                              # code...
                              $totalMarks += $srow['Science'];
                              $hasRecord = 1;
                            }
                            if ($srow['CAPA'] != 0 && $srow['CAPA'] != null) {
                              # code...
                              $totalMarks += $srow['CAPA'];
                              $hasRecord = 1;
                            }
                            if ($srow['Social_Studies'] != 0 && $srow['Social_Studies'] != null) {
                              # code...
                              $totalMarks += $srow['Social_Studies'];
                              $hasRecord = 1;
                            }
                            if ($srow['Agriculture'] != 0 && $srow['Agriculture'] != null) {
                              # code...
                              $totalMarks += $srow['Agriculture'];
                              $hasRecord = 1;
                            }
                            
                            $multiplier += 1;
                          }
                          if ($hasRecord = 0) {
                              $recMult -= 1;
                            }
                        }
                         
                      }
                    }else{
                      echo "<b class ='text-warning'>No results recorded yet!</b>";
                    }
                    $p->close(); ?>
                    Total Students : <?php echo $total,'<br>'; ?>
                    Average CA : <?php 
                    if ($multiplier == 0) {
                          # code...
                          $multiplier = 1;
                        }
                    echo "<span class='badge badge-warning p-2'>";
                        printf("%.2f",($totalMarks/(700*$multiplier)) * 100); echo " % ";
                        echo "</span>&nbsp;"; ?><br>
                    
                    <h6>Average Term Performance :</h6>
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
        <hr>
        <h4 class="text-center text-danger text-capitalize">Standard 4</h4> <br><hr>
        <div class="row">
          
          <?php

          $Engine = $con->prepare('SELECT * FROM students WHERE grade=?');
          $Engine->bind_param('i',$crnt);
          $crnt = 4;
          $Engine->execute();
          $cRes = $Engine->get_result();
          if($cRes){
            while ($row = $cRes->fetch_assoc()){
              ?>
              <div class="col-lg-4">
                <div class="card shadow-lg bg-white rounded border-none mb-4">
                  <div class=" card-body ">
                    <h4 class="text-center text-danger">Class :A</h4>
                    
                    <?php
                    $p = $con->prepare('SELECT * FROM students WHERE grade=?');
                    $p->bind_param('i',$c);
                    $c = 4;
                    $p->execute();
                    $rs = $p->get_result();
                    if($rs){
                      $total= $recMult = $totalMarks = 0;
                      while ($r = $rs->fetch_assoc()){
                        $total += 1;
                        $recMult +=1;
                        $mrks = $con->prepare('SELECT * FROM students INNER JOIN subjects ON students.id=subjects.stuID WHERE students.id = ? AND students.grade = ?; '); 
                        $mrks->bind_param('ii',$sid,$curGD);
                        $sid = $r['id'];
                        $curGD = 4;
                        $sid =  $mrks->execute(); 
                        $ttlmrks = $mrks->get_result();
                        //multiple entries for a single student multiplier constant
                        $multiplier = 0;
                        //check for records for students
                        $hasRecord = 0;
                        if($ttlmrks){
                          while ($srow = $ttlmrks->fetch_assoc()) {
                            if ($srow['English'] != 0 && $srow['English'] != null) {
                              $totalMarks += $srow['English'];
                              $hasRecord = 1;
                            }
                            if ($srow['Setswana'] != 0 && $srow['Setswana'] != null) {
                              $totalMarks += $srow['Setswana'];
                              $hasRecord = 1;
                            }
                            if ($srow['Mathematics'] !=0 && $srow['Mathematics'] != null) {
                              # code...
                              $totalMarks += $srow['Mathematics'];
                              $hasRecord = 1;
                            }
                            if ($srow['Science'] != 0 && $srow['Science'] != null) {
                              # code...
                              $totalMarks += $srow['Science'];
                              $hasRecord = 1;
                            }
                            if ($srow['CAPA'] != 0 && $srow['CAPA'] != null) {
                              # code...
                              $totalMarks += $srow['CAPA'];
                              $hasRecord = 1;
                            }
                            if ($srow['Social_Studies'] != 0 && $srow['Social_Studies'] != null) {
                              # code...
                              $totalMarks += $srow['Social_Studies'];
                              $hasRecord = 1;
                            }
                            if ($srow['Agriculture'] != 0 && $srow['Agriculture'] != null) {
                              # code...
                              $totalMarks += $srow['Agriculture'];
                              $hasRecord = 1;
                            }
                            
                            $multiplier += 1;
                          }
                          if ($hasRecord = 0) {
                              $recMult -= 1;
                            }
                        }
                         
                      }
                    }else{
                      echo "<b class ='text-warning'>No results recorded yet!</b>";
                    }
                    $p->close(); ?>
                    Total Students : <?php echo $total,'<br>'; ?>
                    Average CA : <?php
                    if ($multiplier == 0) {
                          # code...
                          $multiplier = 1;
                        } 
                    echo "<span class='badge badge-warning p-2'>";
                        printf("%.2f",($totalMarks/(700*$multiplier)) * 100); echo " % ";
                        echo "</span>&nbsp;"; ?><br>
                    
                    <h6>Average Term Performance :</h6>
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
        <hr>
        <h4 class="text-center text-danger text-capitalize">Standard 3</h4> <br><hr>
        <div class="row">
          
          <?php

          $Engine = $con->prepare('SELECT * FROM students WHERE grade=?');
          $Engine->bind_param('i',$crnt);
          $crnt = 3;
          $Engine->execute();
          $cRes = $Engine->get_result();
          if($cRes){
            while ($row = $cRes->fetch_assoc()){
              ?>
              <div class="col-lg-4">
                <div class="card shadow-lg bg-white rounded border-none mb-4">
                  <div class=" card-body ">
                    <h4 class="text-center text-danger">Class :A</h4>
                    
                    <?php
                    $p = $con->prepare('SELECT * FROM students WHERE grade=?');
                    $p->bind_param('i',$c);
                    $c = 3;
                    $p->execute();
                    $rs = $p->get_result();
                    if($rs){
                      $total= $recMult = $totalMarks = 0;
                      while ($r = $rs->fetch_assoc()){
                        $total += 1;
                        $recMult +=1;
                        $mrks = $con->prepare('SELECT * FROM students INNER JOIN subjects ON students.id=subjects.stuID WHERE students.id = ? AND students.grade = ?; '); 
                        $mrks->bind_param('ii',$sid,$curGD);
                        $sid = $r['id'];
                        $curGD = 3;
                        $sid =  $mrks->execute(); 
                        $ttlmrks = $mrks->get_result();
                        //multiple entries for a single student multiplier constant
                        $multiplier = 0;
                        //check for records for students
                        $hasRecord = 0;
                        if($ttlmrks){
                          while ($srow = $ttlmrks->fetch_assoc()) {
                            if ($srow['English'] != 0 && $srow['English'] != null) {
                              $totalMarks += $srow['English'];
                              $hasRecord = 1;
                            }
                            if ($srow['Setswana'] != 0 && $srow['Setswana'] != null) {
                              $totalMarks += $srow['Setswana'];
                              $hasRecord = 1;
                            }
                            if ($srow['Mathematics'] !=0 && $srow['Mathematics'] != null) {
                              # code...
                              $totalMarks += $srow['Mathematics'];
                              $hasRecord = 1;
                            }
                            if ($srow['Science'] != 0 && $srow['Science'] != null) {
                              # code...
                              $totalMarks += $srow['Science'];
                              $hasRecord = 1;
                            }
                            if ($srow['CAPA'] != 0 && $srow['CAPA'] != null) {
                              # code...
                              $totalMarks += $srow['CAPA'];
                              $hasRecord = 1;
                            }
                            if ($srow['Social_Studies'] != 0 && $srow['Social_Studies'] != null) {
                              # code...
                              $totalMarks += $srow['Social_Studies'];
                              $hasRecord = 1;
                            }
                            if ($srow['Agriculture'] != 0 && $srow['Agriculture'] != null) {
                              # code...
                              $totalMarks += $srow['Agriculture'];
                              $hasRecord = 1;
                            }
                            
                            $multiplier += 1;
                          }
                          if ($hasRecord = 0) {
                              $recMult -= 1;
                            }
                        }
                         
                      }
                    }else{
                      echo "<b class ='text-warning'>No results recorded yet!</b>";
                    }
                    $p->close(); ?>
                    Total Students : <?php echo $total,'<br>'; ?>
                    Average CA : <?php 
                    if ($multiplier == 0) {
                          # code...
                          $multiplier = 1;
                        }
                    echo "<span class='badge badge-warning p-2'>";
                        if ($multiplier == 0) {
                          # code...
                          $multiplier = 1;
                        }
                        printf("%.2f",($totalMarks/(700*$multiplier)) * 100); echo " % ";
                        echo "</span>&nbsp;"; ?><br>
                    
                    <h6>Average Term Performance :</h6>
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
        <hr>
        <h4 class="text-center text-danger text-capitalize">Standard 2</h4> <br><hr>
        <div class="row">
          
          <?php

          $Engine = $con->prepare('SELECT * FROM students WHERE grade=?');
          $Engine->bind_param('i',$crnt);
          $crnt = 2;
          $Engine->execute();
          $cRes = $Engine->get_result();
          if($cRes){
            while ($row = $cRes->fetch_assoc()){
              ?>
              <div class="col-lg-4">
                <div class="card shadow-lg bg-white rounded border-none mb-4">
                  <div class=" card-body ">
                    <h4 class="text-center text-danger">Class :A</h4>
                    
                    <?php
                    $p = $con->prepare('SELECT * FROM students WHERE grade=?');
                    $p->bind_param('i',$c);
                    $c = 2;
                    $p->execute();
                    $rs = $p->get_result();
                    if($rs){
                      $total= $recMult = $totalMarks = 0;
                      while ($r = $rs->fetch_assoc()){
                        $total += 1;
                        $recMult +=1;
                        $mrks = $con->prepare('SELECT * FROM students INNER JOIN subjects ON students.id=subjects.stuID WHERE students.id = ? AND students.grade = ?; '); 
                        $mrks->bind_param('ii',$sid,$curGD);
                        $sid = $r['id'];
                        $curGD = 2;
                        $sid =  $mrks->execute(); 
                        $ttlmrks = $mrks->get_result();
                        //multiple entries for a single student multiplier constant
                        $multiplier = 0;
                        //check for records for students
                        $hasRecord = 0;
                        if($ttlmrks){
                          while ($srow = $ttlmrks->fetch_assoc()) {
                            if ($srow['English'] != 0 && $srow['English'] != null) {
                              $totalMarks += $srow['English'];
                              $hasRecord = 1;
                            }
                            if ($srow['Setswana'] != 0 && $srow['Setswana'] != null) {
                              $totalMarks += $srow['Setswana'];
                              $hasRecord = 1;
                            }
                            if ($srow['Mathematics'] !=0 && $srow['Mathematics'] != null) {
                              # code...
                              $totalMarks += $srow['Mathematics'];
                              $hasRecord = 1;
                            }
                            if ($srow['Science'] != 0 && $srow['Science'] != null) {
                              # code...
                              $totalMarks += $srow['Science'];
                              $hasRecord = 1;
                            }
                            if ($srow['CAPA'] != 0 && $srow['CAPA'] != null) {
                              # code...
                              $totalMarks += $srow['CAPA'];
                              $hasRecord = 1;
                            }
                            if ($srow['Social_Studies'] != 0 && $srow['Social_Studies'] != null) {
                              # code...
                              $totalMarks += $srow['Social_Studies'];
                              $hasRecord = 1;
                            }
                            if ($srow['Agriculture'] != 0 && $srow['Agriculture'] != null) {
                              # code...
                              $totalMarks += $srow['Agriculture'];
                              $hasRecord = 1;
                            }
                            
                            $multiplier += 1;
                          }
                          if ($hasRecord = 0) {
                              $recMult -= 1;
                            }
                        }
                         
                      }
                    }else{
                      echo "<b class ='text-warning'>No results recorded yet!</b>";
                    }
                    $p->close(); ?>
                    Total Students : <?php echo $total,'<br>'; ?>
                    Average CA : <?php 
                    if ($multiplier == 0) {
                          # code...
                          $multiplier = 1;
                        }
                    echo "<span class='badge badge-warning p-2'>";
                        printf("%.2f",($totalMarks/(700*$multiplier)) * 100); echo " % ";
                        echo "</span>&nbsp;"; ?><br>
                    
                    <h6>Average Term Performance :</h6>
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
        <hr>
        <h4 class="text-center text-danger text-capitalize">Standard 1</h4> <br><hr>
        <div class="row">
          
          <?php

          $Engine = $con->prepare('SELECT * FROM students WHERE grade=?');
          $Engine->bind_param('i',$crnt);
          $crnt = 1;
          $Engine->execute();
          $cRes = $Engine->get_result();
          if($cRes){
            while ($row = $cRes->fetch_assoc()){
              ?>
              <div class="col-lg-4">
                <div class="card shadow-lg bg-white rounded border-none mb-4">
                  <div class=" card-body ">
                    <h4 class="text-center text-danger">Class :A</h4>
                    
                    <?php
                    $p = $con->prepare('SELECT * FROM students WHERE grade=?');
                    $p->bind_param('i',$c);
                    $c = 1;
                    $p->execute();
                    $rs = $p->get_result();
                    if($rs){
                      $total= $recMult = $totalMarks = 0;
                      while ($r = $rs->fetch_assoc()){
                        $total += 1;
                        $recMult +=1;
                        $mrks = $con->prepare('SELECT * FROM students INNER JOIN subjects ON students.id=subjects.stuID WHERE students.id = ? AND students.grade = ?; '); 
                        $mrks->bind_param('ii',$sid,$curGD);
                        $sid = $r['id'];
                        $curGD = 1;
                        $sid =  $mrks->execute(); 
                        $ttlmrks = $mrks->get_result();
                        //multiple entries for a single student multiplier constant
                        $multiplier = 0;
                        //check for records for students
                        $hasRecord = 0;
                        if($ttlmrks){
                          while ($srow = $ttlmrks->fetch_assoc()) {
                            if ($srow['English'] != 0 && $srow['English'] != null) {
                              $totalMarks += $srow['English'];
                              $hasRecord = 1;
                            }
                            if ($srow['Setswana'] != 0 && $srow['Setswana'] != null) {
                              $totalMarks += $srow['Setswana'];
                              $hasRecord = 1;
                            }
                            if ($srow['Mathematics'] !=0 && $srow['Mathematics'] != null) {
                              # code...
                              $totalMarks += $srow['Mathematics'];
                              $hasRecord = 1;
                            }
                            if ($srow['Science'] != 0 && $srow['Science'] != null) {
                              # code...
                              $totalMarks += $srow['Science'];
                              $hasRecord = 1;
                            }
                            if ($srow['CAPA'] != 0 && $srow['CAPA'] != null) {
                              # code...
                              $totalMarks += $srow['CAPA'];
                              $hasRecord = 1;
                            }
                            if ($srow['Social_Studies'] != 0 && $srow['Social_Studies'] != null) {
                              # code...
                              $totalMarks += $srow['Social_Studies'];
                              $hasRecord = 1;
                            }
                            if ($srow['Agriculture'] != 0 && $srow['Agriculture'] != null) {
                              # code...
                              $totalMarks += $srow['Agriculture'];
                              $hasRecord = 1;
                            }
                            
                            $multiplier += 1;
                          }
                          if ($hasRecord = 0) {
                              $recMult -= 1;
                            }
                        }
                         
                      }
                    }else{
                      echo "<b class ='text-warning'>No results recorded yet!</b>";
                    }
                    $p->close(); ?>
                    Total Students : <?php echo $total,'<br>'; ?>
                    Average CA : <?php 
                    if ($multiplier == 0) {
                          # code...
                          $multiplier = 1;
                        }
                    echo "<span class='badge badge-warning p-2'>";
                        printf("%.2f",($totalMarks/(700*$multiplier)) * 100); echo " % ";
                        echo "</span>&nbsp;"; ?><br>
                    
                    <h6>Average Term Performance :</h6>
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
