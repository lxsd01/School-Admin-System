  <?php include("auth_session.php"); ?>
  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
      <title>Announcements and Discussions Forum</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
          <a class="navbar-brand" href="#"> <?php echo $_SESSION['username']; ?></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

              <li class="nav-item">
          <a href="../dashboard_head.php" class = "nav-link ">Students Summary</a>
        </li>
        <li class="nav-item">
          <a href="pages/announcements.php" class = "nav-link active">Announcements & Discussions</a>
        </li>


            </ul>
            <a href="../logout.php"><button class="btn btn-outline-primary my-2 my-sm-0" type="submit" >Logout</button></a>
          </div>
        </nav>
  <br><br>
  <div class="container">
    <h3>Announcements & Discussions</h3>
    <div class="card shadow-lg bg-white rounded border-none mb-4">


      <div class="card-body">
        <h4>Post an announcement</h4>
        <?php
        require_once "../db.php";
        if(isset($_POST["poster"])){

          $date = date("Y-F-d H:i:s");
          $author = $_SESSION["email"];
          $text = trim($_POST["annText"]);
          $type = trim($_POST["type"]);

          $stmt=$con->prepare("INSERT INTO announcements (nako,author,mokwalo,type) VALUES (?,?,?,?)");
          $stmt->bind_param('ssss',$n,$a,$m,$t);
          $n = $date;
          $a = $author;
          $m = $text;
          $t = $type;
          $result=$stmt->execute();
          if ($result) {
            echo "<div class='alert alert-success  col-12 alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            <strong>Posted!</strong> Scroll below to view it.</div>";
          }else{
            echo "<div class='alert alert-warning  col-12 alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            <strong>Warning!</strong>There was a problem processing your query.Please try agian.</div>";
            }
          }
          ?>
        <div class="card-text ">
          <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row mb-3">
              <div class="input-group m-1">
                <span class="input-group-text">Title</span>
                <textarea class="form-control" aria-label="With textarea" name="annTtl"></textarea>
              </div>
              <div class="input-group m-1">
                <span class="input-group-text">Text</span>
                <textarea class="form-control" aria-label="With textarea" name="annText"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 mb-3">
                <select class="form-control form-control-lg" name="type" required>
                  <option value="" disabled selected hidden>Select type of announcement</option>
                  <option value="0" >Important</option>
                  <option value="1" >Notice</option>
                  <option value="2" >Reminder</option>
                  <option value="3" >News</option>
                </select>
              </div>
              <div class="col-lg-6 mb-3 ">
                <input type="submit" class="btn btn-primary form-control form-control-lg" name = "poster" value = "post">
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
    <!--Remove Announcement Card-->
    <div class="card shadow-lg bg-white rounded border-none mb-4">
      <div class="card-body">
        <h4>Remove Announcement</h4>
        <?php
        if(isset($_POST["del"])){
          $item = trim($_POST["delopt"]);
          $del = $con->prepare("DELETE FROM announcements WHERE id = ?");
          $del->bind_param('s',$val);
          $val = $item;
          $res = $del->execute();
          if ($res) {
            echo "<div class='alert alert-success  col-12 alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            <strong>Deleted!</strong> Scroll below to confirm.</div>";
          }else{
            echo "<div class='alert alert-warning  col-12 alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            <strong>Warning!</strong>There was a problem deleting.Please try agian.</div>";
          }
        }
         ?>
        <div class="card-text ">
          <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="row">
              <div class="col-lg-6 mb-3">
                <select class="form-control form-control-lg" name="delopt" id="filter" required>
                  <option value="" disabled selected hidden>Select the announcement</option>
                  <?php
                  $select_generate = $con->prepare('SELECT * FROM announcements');
                  $select_generate->execute();
                  $result = $select_generate->get_result();
                  while ($row = $result->fetch_assoc()):
                  ?>

                  <option value="<?=$row['id']?>">Date: <?=$row['nako']?> Author: <?=$row['author']?></option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="col-lg-6 mb-3 ">
                <input type="submit" class="btn btn-danger form-control form-control-lg" name= "del" value = "Delete">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="card shadow-lg bg-white rounded border-none mb-4">
      <div class="card-body">
        <h4>Discussions Forum</h4>

        <div class="card-text ">

          <?php

            #posting comments

            if(isset($_POST['comment'])){
                $comID = $_POST['forumPost'];
                $comAuth = $_SESSION['email'];
                $comText = trim($_POST['cmnText']);
                $comPoster = $con->prepare("INSERT INTO comments(id,author,mokwalo) VALUES(?,?,?)");
                $comPoster->bind_param('iss', $cmid, $cma, $cmm);
                $cmid = $comID;
                $cma = $comAuth;
                $cmm = $comText;

                $comRes = $comPoster->execute();
                if ($comRes) {
                  
                  
                }else{
                  
                }
              }

            # Making Announcements
            $annGen = $con->prepare('SELECT * FROM announcements');
            $annGen->execute();
            $result = $annGen->get_result();
            while ($row = $result->fetch_assoc()){
                #Color coding
              if($row['type'] == 0){
                $colorCode = "alert-danger";

              }elseif($row['type'] == 1){
                $colorCode = "alert-success";

              }elseif($row['type'] == 2){
                $colorCode = "alert-warning";

              }else{
                $colorCode = "alert-primary";
              }




              ?>
              <div class='alert <?php echo $colorCode; ?>  col-12'>
                <strong> Author: <?=$row['author']?></strong>
                <br>
                <?=$row['mokwalo']?>
                <br>
                Timed: <?=$row['nako']?>
              </div>
              <!--Comments Engine... BUGS/ADDONS TO FIX: add boaders, deleting/edit own comments by current user , style the comments -->
              <?php
              $aID = $row['annID'];
              $commentsEngine = $con->prepare('SELECT * FROM comments WHERE id = ?');
              $commentsEngine->bind_param('s',$crnt);
              $crnt = $aID;
              $commentsEngine->execute();
              $cRes = $commentsEngine->get_result();
              if($cRes){
                while ($r = $cRes->fetch_assoc()){
                  if($r['id']==$aID){
                    echo "<br>";
                    echo '<div class = "container" >';
                    echo "Author: ";echo $r['author'];
                    echo "<br>";
                    echo $r['mokwalo'];
                    echo "</div>";
                  }
                }
              }else{
                echo "No comments yet!";
              }

              ?>

              <!--Comments posting form -->
              <?php
              
              ?>
              <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="row">
                  <div class="col-lg-10 mb-3">
                    <input type="" name="forumPost"  value="<?php echo $row['annID'];?>" hidden>
                    <input type="text" class="form-control form-control-lg" name="cmnText" placeholder="Type comment">
                  </div>
                  <?php $thePost = $row['annID']; ?>
                  <div class="col-lg-2 mb-3 ">
                    <input type="submit" class="btn btn-warning form-control form-control-lg" name= "comment" value = "Comment">
                  </div>
                </div>
              </form>
              <hr><hr>
            <?php
          }
            
              ?>
        </div>
      </div>
    </div>

  </div>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="bootstrap/jsbootstrap.min.js"></script>
    </body>
  </html>
