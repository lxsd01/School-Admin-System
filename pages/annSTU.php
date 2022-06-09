  <?php include("auth_session.php");
  require_once "../db.php"; ?>
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
      <a class="navbar-brand" href="../dashboard_stu.php"><?php echo $_SESSION['username']; ?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a href="ins/browse.php" class = "nav-link ">Learning Content</a>
          </li>
          <li class="nav-item">
            <a href="annSTU.php" class = "nav-link active">Announcements</a>
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
              <!--Comments Engine... BUGS TO FIX: add boaders,, style the comments -->
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
