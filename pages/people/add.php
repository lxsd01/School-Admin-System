<div class="card shadow-lg bg-white rounded border-none mb-4">
  <div class="card-body">

    <h3 class="card-title alert alert-primary text-center">Add Student</h3>
    <div class="card-text ">
      Fill out the form below:


      <?php
          # Checking inputs and data validation and verification


          // Sanitizing inputs
      function sanitizer($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
          // Checking if the form has been submitted
      if($_SERVER["REQUEST_METHOD"] == "POST"){
        header("Location: usrmgt.php?manage=1");
            // Boolean variable to check if an instructor for a grade is available
        $ins_check = 0;

        $name = sanitizer($_POST["std_name"]);
        $surname = sanitizer($_POST["std_sname"]);
        $designation = "student";
        // Generating Password
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $password = substr( str_shuffle( $chars ), 0, 8 );
        echo $password;
        $grade = sanitizer($_POST["grade"]);

            //selecting instructor based on the grade they are assigned to

        $validINS = $con->prepare("SELECT * FROM instructor WHERE grade = ?");
        $validINS->bind_param("i" ,$gr);
        $gr = $grade;
        $validINS->execute();
        $result = $validINS->get_result();
        if ($result) {
          $x = "";
          while ($row = $result->fetch_assoc()) {

            if ($grade == 0) {
              $instructor = $row['id'];
              $x = $row['grade'];
            }elseif ($grade == 1) {
              $instructor = $row['id'];
              $x = $row['grade'];
            }elseif ($grade == 2) {
              $instructor = $row['id'];
              $x = $row['grade'];
            }elseif ($grade == 3) {
              $instructor = $row['id'];
              $x = $row['grade'];
            }elseif ($grade == 4) {
              $instructor = $row['id'];
              $x = $row['grade'];
            }elseif ($grade == 5) {
              $instructor = $row['id'];
              $x = $row['grade'];
            }elseif ($grade == 6) {
              $instructor = $row['id'];
              $x = $row['grade'];
            }else{
              $instructor = $row['id'];
              $x = $row['grade'];
            }
          }
          if ($grade == $x) {
            $ins_check = 1;
          }
        }else{
              // error statement
          $ins_check = 0;
        }

        $phone = sanitizer($_POST["p_number"]);
        $email = sanitizer($_POST["p_email"]);
        $parentName = sanitizer($_POST["p_name"]);
        $parentSurname = sanitizer($_POST["p_sname"]);
        $parentOccupation = sanitizer($_POST["p_occupation"]);

            // Registering as a user of the website
        $stmt=$con->prepare("INSERT INTO users (username, email, password, designation) VALUES (?,?,?,?)");
        $stmt->bind_param('ssss',$usr,$mail,$pswrd,$des);
        $usr=$name;
        $mail=$email;
        $pswrd=md5($password);
        $des= $designation;
        if ($ins_check == 1) {
              # Executing query only when there is an instructor for the grade
         $result=$stmt->execute();
       }

       if ($result && $ins_check == 1) {
              // Registering the student
        $addStu=$con->prepare("INSERT INTO students (id,name,surname,parent,grade,instructor) VALUES (?,?,?,?,?,?)");
        $addStu->bind_param('issssi' ,$sid,$sn,$srn,$sp,$sg,$si);
              // Getting id from user database using email
        $sql = "SELECT id, username, email, password, designation FROM users WHERE email = ?";
        if($stmt2 = mysqli_prepare($con, $sql)){
          mysqli_stmt_bind_param($stmt2, "s", $param_email);
          $param_email = $email;
          if(mysqli_stmt_execute($stmt2)){
            mysqli_stmt_store_result($stmt2);
            if(mysqli_stmt_num_rows($stmt2)){
              mysqli_stmt_bind_result($stmt2, $id , $user, $email, $pwd, $des);
              if(mysqli_stmt_fetch($stmt2)){
                $sid = $id;
              }
            }
          }
        }
        $sn = $name;
        $srn = $surname;
        $sp = $email;
        $sg = $grade;
        $si = $instructor;

        $result2=$addStu->execute();
        if($result2){
                // Adding the student to a class
          $selectTeacher=$con->prepare("INSERT INTO class (studentid,teacher,grade) VALUES (?,?,?)");
          $selectTeacher->bind_param('isi' ,$s ,$t,$g);

                // Getting id from user database using email
          $sql = "SELECT id, username, email, password, designation FROM users WHERE email = ?";
          if($stmt3 = mysqli_prepare($con, $sql)){
            mysqli_stmt_bind_param($stmt3, "s", $param_email);
            $param_email = $email;
            if(mysqli_stmt_execute($stmt3)){
              mysqli_stmt_store_result($stmt3);
              if(mysqli_stmt_num_rows($stmt3)){
                mysqli_stmt_bind_result($stmt3, $id , $user, $email, $pwd, $des);
                if(mysqli_stmt_fetch($stmt3)){
                  $s = $id;
                }
              }
            }
          }
          $t = $instructor;
          $g = $grade;
          $result3=$selectTeacher->execute();
          if ($result3) {
            $addStu=$con->prepare("INSERT INTO parents (id,name,surname,phone,email,occupation) VALUES (?,?,?,?,?,?)");
            $addStu->bind_param('ississ' ,$pid,$pn,$psn,$p,$eml,$oc);
                  // Getting id from user database using email
            $sql = "SELECT id, username, email, password, designation FROM users WHERE email = ?";
            if($stmt4 = mysqli_prepare($con, $sql)){
              mysqli_stmt_bind_param($stmt4, "s", $param_email);
              $param_email = $email;
              if(mysqli_stmt_execute($stmt4)){
                mysqli_stmt_store_result($stmt4);
                if(mysqli_stmt_num_rows($stmt4)){
                  mysqli_stmt_bind_result($stmt4, $id , $user, $email, $pwd, $des);
                  if(mysqli_stmt_fetch($stmt4)){
                    $pid = $id;
                  }
                }
              }
            }
            $pn = $parentName;
            $psn = $parentSurname;
            $p = $phone;
            $eml = $email;
            $oc = $parentOccupation;

            $result4=$addStu->execute();
            if ($result4) {
              
              // code...
              echo "<div class='alert alert-success  col-12 alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              <strong>Done!</strong>".$name." was added, an email was sent to ".$parentName." at ".$email." containing notice of successful registration and their logon credentials"."</div>";
            }else{

            }
          }else{

          }
        }else{

        }

      }
      else{

        echo "<div class='alert alert-danger  col-12 alert-dismissible'>
        <button type='button' class='close' data-dismiss='alert'>&times;</button>
        <strong>Error!</strong> Please register a system instructor for the grade first, then try again.</div>";
      }
    }
    ?>
    <br>

    <!-- Form for adding a student -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="student_form_validation" novalidate >

      <h5>Student Details</h5>
      <div class="row ">
        <div class="col-lg-4 mb-3 ">
          <input type="text" class="form-control form-control-lg" pattern="^[a-zA-Z\s]+$" title="Only letter and spaces are allowed. Write the name, no special characters allowed " name="std_name" placeholder="Student Name" required>
          <div class="valid-feedback">Proceed entering correct data.</div>
          <div class="invalid-feedback">Only letter and spaces are allowed. Write the name, no special characters allowed</div>
        </div>
        <div class="col-lg-3 mb-3 ">
          <input type="text" class="form-control form-control-lg" pattern="^[a-zA-Z\s]+$" title="Only letter and spaces are allowed. Write the surname, no special characters allowed " name="std_sname" placeholder="Student Surame" required>
          <div class="valid-feedback">Proceed entering correct data.</div>
          <div class="invalid-feedback">Only letter and spaces are allowed. Write the name, no special characters allowed</div>
        </div>
        <div class="col-lg-3 mb-3">
          <select class="form-control form-control-lg" name="grade" id="filter" required>
            <option value="" disabled selected hidden>Select Grade</option>
            <option value="0" >Pre School</option>
            <option value="1" >Standard 1</option>
            <option value="2" >Standard 2</option>
            <option value="3" >Standard 3</option>
            <option value="4" >Standard 4</option>
            <option value="5" >Standard 5</option>
            <option value="6" >Standard 6</option>
            <option value="7" >Standard 7</option>
          </select>
          <div class="valid-feedback">Proceed.</div>
          <div class="invalid-feedback">Please select the grade the student is being registered to</div>
        </div>
        <div class="col-lg-2 mb-3">
          <select class="form-control form-control-lg" name="class" required>
            <option value="" disabled selected hidden>Class</option>
            <option value="0" >A</option>
            <option value="1" >B</option>
            <option value="2" >C</option>
            <option value="3" >D</option>
            <option value="4" >E</option>
            <option value="5" >F</option>
          </select>
          <div class="valid-feedback">Proceed.</div>
          <div class="invalid-feedback">Please select the class</div>
        </div>
      </div>
      <br>
      <h5>Parent Details</h5>
      <div class="row ">
        <div class="col-lg-6 mb-3 ">
          <input type="text" class="form-control form-control-lg" pattern="^[a-zA-Z\s]+$" title="Only letter and spaces are allowed. Write the name, no special characters allowed " name="p_name" placeholder="Parent Name" required>
          <div class="valid-feedback">Proceed enterind correct data.</div>
          <div class="invalid-feedback">Only letter and spaces are allowed. Write the name, no special characters allowed</div>
        </div>
        <div class="col-lg-6 mb-3 ">
          <input type="text" class="form-control form-control-lg" pattern="^[a-zA-Z\s]+$" title="Only letter and spaces are allowed. Write the surname, no special characters allowed " name="p_sname" placeholder="Parent Surame" required>
          <div class="valid-feedback">Proceed entering correct data.</div>
          <div class="invalid-feedback">Only letter and spaces are allowed. Write the surname, no special characters allowed</div>
        </div>
      </div>

      <div class="row ">
        <div class="col-lg-4 mb-3 ">
          <input type="text" class="form-control form-control-lg" pattern="^[a-zA-Z\s]+$" title="Only letter and spaces are allowed. Write the occupation, no special characters allowed " name="p_occupation" placeholder="Occupation" required>
          <div class="valid-feedback">Proceed entering correct data.</div>
          <div class="invalid-feedback">Only letter and spaces are allowed. Write the occupation, no special characters allowed</div>
        </div>
        <div class="col-lg-4 mb-3 ">
          <input type="number" class="form-control form-control-lg"  title="Enter the parent phone number " name="p_number" placeholder="Phone number" required>
          <div class="valid-feedback">Proceed entering correct data.</div>
          <div class="invalid-feedback">Only numbers are allowed. Write the correct phone number, no special characters allowed</div>
        </div>
        <div class="col-lg-4 mb-3 ">
          <input type="email" class="form-control form-control-lg"  title="Follow the correct email syntax e.g someone@somewhere.com" name="p_email" placeholder="Email" required>
          <div class="valid-feedback">Proceed enterind correct data.</div>
          <div class="invalid-feedback">An valid email is in the format "someone@somewhere.xxx"</div>
        </div>
      </div>
      <br>
      

      <div class="row ">
        <div class="col-12 ">
          <input type="submit" class="btn btn-primary form-control form-control-lg" value="Register">
        </div>

      </div>
    </form>

  </div>
</div>
</div>
<br><hr><br>
<div class="card shadow-lg bg-white rounded border-none mb-4">


  <div class="card-body">
    <h3 class="card-title alert alert-primary text-center">Add Instructor</h3>



    <div class="card-text ">

      Fill out the form below:

      <br>
      <?php
      if($_SERVER["REQUEST_METHOD"] == "POST"){
        header("Location: usrmgt.php?manage=1");
        $name = sanitizer($_POST["ins_name"]);
        $surname = sanitizer($_POST["ins_sname"]);
        $designation = "instructor";
        // Generating Password
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $password = substr( str_shuffle( $chars ), 0, 8 );
        echo $password;
        $grade = sanitizer($_POST["grade"]);
        $role = sanitizer($_POST["t_role"]);
        $phone = sanitizer($_POST["t_number"]);
        $email = sanitizer($_POST["t_email"]);

        $stmt=$con->prepare("INSERT INTO users (username, email, password, designation) VALUES (?,?,?,?)");
        $stmt->bind_param('ssss',$usr,$mail,$pswrd,$des);
        $usr=$name;
        $mail=$email;
        $pswrd=md5($password);
        $des= $designation;
        $result = null;

            // Checking if an instructor for this grade already exists before executing query
        $validINS = $con->prepare("SELECT * FROM instructor WHERE grade = ?");
        $validINS->bind_param("i" ,$gr);
        $gr = $grade;
        $validINS->execute();
        $res = $validINS->get_result();
        $x = 0;
        if ($res) {
          while ($row = $res->fetch_assoc()) {
            if($row['grade'] == $grade){
              $x = 1;
              break;
            }else{
                  // error
            }

          }

          if ($x == 0) {
            $result=$stmt->execute();
          }

        }
        if ($result & $x == 0) {

          $stmt2=$con->prepare("INSERT INTO instructor (id ,name, surname ,grade , role, phone, email) VALUES (?,?,?,?,?,?,?)");
          $stmt2->bind_param('issssis' ,$i ,$n,$s,$g,$r,$p,$e);
          $n= $name;
          $s = $surname;
          $g = $grade;
          $r = $role;
          $p = $phone;
          $e = $email;
              // Getting id
          $sql = "SELECT id, username, email, password, designation FROM users WHERE email = ?";
          if($stmt3 = mysqli_prepare($con, $sql)){
            mysqli_stmt_bind_param($stmt3, "s", $param_email);
            $param_email = $email;
            if(mysqli_stmt_execute($stmt3)){
              mysqli_stmt_store_result($stmt3);
              if(mysqli_stmt_num_rows($stmt3)){
                mysqli_stmt_bind_result($stmt3, $id , $user, $email, $pwd, $des);
                if(mysqli_stmt_fetch($stmt3)){
                  $i = $id;
                }
              }
            }
          }
          $result2=$stmt2->execute();
          if($result2){
            
            echo "<div class='alert alert-success  col-12 alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            <strong>Done! </strong>An instructor for this grade has been added</div>";
          }else{

          }

        }
        else{
          
          echo "<div class='alert alert-danger  col-12 alert-dismissible'>
          <button type='button' class='close' data-dismiss='alert'>&times;</button>
          <strong>Error! </strong>An instructor for this grade already exists</div>";
        }
      }
      ?>
      <!-- Form for adding an instructor -->
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="student_form_validation" novalidate >

        <h5>Instructor Details</h5>
        <div class="row ">
          <div class="col-lg-6 mb-3 ">
            <!-- Input group with regular expressions ^[a-zA-Z\s]+$ checking if input is a string of alphabets-->
            <input type="text" class="form-control form-control-lg" pattern="^[a-zA-Z\s]+$" title="Only letter and spaces are allowed. Write the name, no special characters allowed " name="ins_name" placeholder="Instructor Name" required>

            <!-- Bootstrap valid and invalid feedback output generator -->
            <div class="valid-feedback">Proceed entering correct data.</div>
            <div class="invalid-feedback">Only letter and spaces are allowed. Write the name, no special characters allowed</div>
          </div>
          <div class="col-lg-6 mb-3 ">
            <input type="text" class="form-control form-control-lg" pattern="^[a-zA-Z\s]+$" title="Only letter and spaces are allowed. Write the surname, no special characters allowed " name="ins_sname" placeholder="Instructor Surname" required>
            <div class="valid-feedback">Proceed entering correct data.</div>
            <div class="invalid-feedback">Only letter and spaces are allowed. Write the surname, no special characters allowed</div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-8 mb-3">
            <select class="form-control form-control-lg" name="grade" required>
              <option value="" disabled selected hidden>Select Grade</option>
              <option value="0" >Pre School</option>
              <option value="1" >Standard 1</option>
              <option value="2" >Standard 2</option>
              <option value="3" >Standard 3</option>
              <option value="4" >Standard 4</option>
              <option value="5" >Standard 5</option>
              <option value="6" >Standard 6</option>
              <option value="7" >Standard 7</option>
            </select>
            <div class="valid-feedback">Proceed.</div>
            <div class="invalid-feedback">Please select the grade the student is being registered to</div>
          </div>
          <div class="col-lg-4 mb-3">
            <select class="form-control form-control-lg" name="class" required>
              <option value="" disabled selected hidden>Class</option>
              <option value="0" >A</option>
              <option value="1" >B</option>
              <option value="2" >C</option>
              <option value="3" >D</option>
              <option value="4" >E</option>
              <option value="5" >F</option>
            </select>
            <div class="valid-feedback">Proceed.</div>
            <div class="invalid-feedback">Please select the class</div>
          </div>

        </div>

        <div class="row ">
          <div class="col-lg-4 mb-3 ">
            <input type="text" class="form-control form-control-lg" pattern="^[a-zA-Z\s]+$" title="Only letter and spaces are allowed. Write the occupation, no special characters allowed " name="t_role" placeholder="Role" required>
            <div class="valid-feedback">Proceed entering correct data.</div>
            <div class="invalid-feedback">Only letter and spaces are allowed. Write the role of this person in the school, no special characters allowed</div>
          </div>
          <div class="col-lg-4 mb-3 ">
            <input type="number" class="form-control form-control-lg" name="t_number" placeholder="Phone number" required>
            <div class="valid-feedback">Proceed entering correct data.</div>
            <div class="invalid-feedback">Only numbers are allowed. Write the correct phone number, no special characters allowed</div>
          </div>
          <div class="col-lg-4 mb-3 ">
            <input type="email" class="form-control form-control-lg"  title="Follow the correct email syntax e.g someone@somewhere.com" name="t_email" placeholder="Email" required>
            <div class="valid-feedback">Proceed entering correct data.</div>
            <div class="invalid-feedback">Follow the correct email syntax e.g someone@somewhere.com</div>
          </div>
        </div>
        <br>

        <div class="row ">
          <div class="col-12 ">
            <input type="submit" class="btn btn-primary form-control form-control-lg" name="registerHall" value="Register">
            <i class="text-danger text-center">Write <b>head</b> on the <b>role</b> field for adding the School Head.</i>
          </div>

        </div>
      </form>


    </div>


  </div>
</div>