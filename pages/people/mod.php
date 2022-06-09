<!--Modify User Card-->

<div class="card shadow-lg bg-white rounded border-none mb-4">
  <div class="card-body">
    <h3 class="card-title alert alert-success text-center">Modify User Records</h3>
    <div class="card shadow-lg bg-white rounded border-none mb-4">


      <div class="card-body">
        <h5>Select Student</h5>
        <form action="" method="post" accept-charset="utf-8">
          <div class="row">
            <div class="col-lg-8">
              <select class="form-control form-control-lg" name="userX" required>
                <option value="" disabled selected hidden>User List</option>
                <?php 
                $Engine = $con->prepare('SELECT * FROM users');
                $Engine->execute();
                $cRes = $Engine->get_result();
                if($cRes){
                  while ($row = $cRes->fetch_assoc()){ ?>
                    <option value="<?=$row['id']?>"><?=$row['username']?> | Role: <?=$row['designation']?></option>
                   
                  <?php $rid = $row['designation']; }} $Engine->close(); ?>
                </select>
              </div>
               <input type="" name="desg" value="<?php echo $rid ?>" hidden>
              <div class="col-lg-4">
                <button type="submit" class="form-control btn-warning m-1" name = "editor">Edit</button>
              </div>

            </div>  
          </form>
          <br>
          <div class="row">


            <div class="col-lg-12">

              <?php 
              //checking if form has been submitted
              $selected_desig = '';
              if (isset($_POST['editor'])) {
                # code...
                $selected_desig = $_POST['desg'];
                ?>
                <p class = 'text-center text-danger'>Results.</p>
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>System ID</th>
                      <th>Username</th>
                      <th>Email</th>
                      <th>Designation</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                  //Populaling The Table
                    $table = $con->prepare("SELECT * FROM users where id = ?");
                    $table->bind_param('i',$id);
                    $id = $_POST['userX'];
                    $table->execute();
                    $result = $table->get_result();
                    if ($result) {
                  # code...
                      while($row = $result->fetch_assoc()) { ?>
                        <tr>
                          <td><?php echo $row['id']; ?></td>
                          <td><?php echo $row['username']; ?></td>
                          <td><?php echo $row['email']; ?></td>
                          <td><?php echo $row['designation']; ?></td>
                          <td>
                            <div class="row">
                              <form action="" method="post" accept-charset="utf-8">
                                <input type="" name="toEdit"  value="<?php echo $row['resID'];?>" hidden>
                                <div class="row">
                                  <button type="submit" class="btn btn-warning m-1" name = "editRow">Update</button>
                                  <button type="submit" class="btn btn-danger m-1"  name = "deleteRow">Reset Password</button>
                                </div>
                              </form>
                            </div>

                          </td>
                        </tr>
                      <?php } }?>


                    </tbody>
                  </table>
                  <?php
                  $table->close();

                }
                      //Deleting results row
                if (isset($_POST['deleteRow'])) {
    # code...
                  $deleteResult = $con->prepare("DELETE FROM subjects WHERE resID = ?");
                  $deleteResult->bind_param("i",$_POST['toEdit']);
                  $deleteResult->execute();
                  $delRes = $deleteResult->get_result();
                  if ($delRes) {
                        # code...
                   echo "<div class='alert alert-success  col-12 alert-dismissible'>
                   <button type='button' class='close' data-dismiss='alert'>&times;</button>
                   <strong>Done!</strong> User Deleted</div>";
                 }
               }

               if ($selected_desig == 'instructor') {
               
                 if (isset($_POST['editRow'])) {

  //Adding previous values
                  $src_res = $con->prepare("SELECT * FROM users where id = ? AND designation = ?");
                  $src_res->bind_param('is',$id,'instructor');
                  $id = $_POST['toEdit'];
                  $src_res->execute();
                  $result = $src_res->get_result();
                  if ($result){
                    while($row = $result->fetch_assoc()){
                      ?>
                      <div class="container" id="resEdit">
                        <?php 
                      //Previous Details
                        $prev = $con->prepare("SELECT * FROM instructor WHERE id = ?");
                        $prev->bind_param('i',$cur_ins_id);
                        $cur_ins_id = $row['id'];
                        $prev->execute();
                        $ins_result = $prev->get_result();
                        if ($ins_result) {
                        # code...
                          while ($the_ins = $ins_result->fetch_assoc()){
                          # code...



                           ?>
                           <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="student_form_validation" novalidate >

                            <h5>Instructor Details</h5>
                            <div class="row ">
                              <div class="col-lg-6 mb-3 ">
                                <input type="text" class="form-control form-control-lg" pattern="^[a-zA-Z\s]+$" title="Only letter and spaces are allowed. Write the name, no special characters allowed " name="ins_name" placeholder="Instructor Name : <?php echo $the_ins['name']; ?>" required>
                                <div class="valid-feedback">Proceed entering correct data.</div>
                                <div class="invalid-feedback">Only letter and spaces are allowed. Write the name, no special characters allowed</div>
                              </div>
                              <div class="col-lg-6 mb-3 ">
                                <input type="text" class="form-control form-control-lg" pattern="^[a-zA-Z\s]+$" title="Only letter and spaces are allowed. Write the surname, no special characters allowed " name="ins_sname" placeholder="Instructor Surname : <?php echo $the_ins['surname']; ?>" required>
                                <div class="valid-feedback">Proceed entering correct data.</div>
                                <div class="invalid-feedback">Only letter and spaces are allowed. Write the surname, no special characters allowed</div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-lg-8 mb-3">
                                <select class="form-control form-control-lg" name="grade" required>
                                  <option value="" disabled selected hidden>Grade : <?php echo $the_ins['grade']; ?></option>
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
                                <input type="text" class="form-control form-control-lg" pattern="^[a-zA-Z\s]+$" title="Only letter and spaces are allowed. Write the occupation, no special characters allowed " name="t_role" placeholder="Role: <?php echo $the_ins['role']; ?>" required>
                                <div class="valid-feedback">Proceed entering correct data.</div>
                                <div class="invalid-feedback">Only letter and spaces are allowed. Write the role of this person in the school, no special characters allowed</div>
                              </div>
                              <div class="col-lg-4 mb-3 ">
                                <input type="number" class="form-control form-control-lg" name="t_number" placeholder="Phone number : <?php echo $the_ins['phone']; ?>" required>
                                <div class="valid-feedback">Proceed entering correct data.</div>
                                <div class="invalid-feedback">Only numbers are allowed. Write the correct phone number, no special characters allowed</div>
                              </div>
                              <div class="col-lg-4 mb-3 ">
                                <input type="email" class="form-control form-control-lg"  title="Follow the correct email syntax e.g someone@somewhere.com" name="t_email" placeholder="Email : <?php echo $the_ins['email']; ?>" required>
                                <div class="valid-feedback">Proceed entering correct data.</div>
                                <div class="invalid-feedback">Follow the correct email syntax e.g someone@somewhere.com</div>
                              </div>
                            </div>
                            <br>

                            <!-- sending results row id to the update query -->
                            <input type="" name="rowID" value=" <?php echo $_POST['toEdit']; ?> " hidden>
                            <div class="row ">
                              <div class="col-12 ">
                                <input type="submit" class="btn btn-primary form-control form-control-lg" name="registerHall" value="Register">

                              </div>

                            </div>

                            <?php 
                          }
                        } ?>
                        <div class="container">
                          <div class="row">
                            <div class="col-lg-6">
                              <button type="submit" class="form-control btn-warning m-1" name = "editRes" onclick="closeForm()" >Update</button>
                            </div>
                            <div class="col-lg-6">
                              <button type="submit" class="form-control btn-danger m-1" onclick="closeForm()" >Close</button>
                            </div>
                          </div>
                        </div>
                      </form>
                      <?php
                    }
                  }
                }
              }

                  // Updating results in selected row individually
              if (isset($_POST['editRes'])) {
                    # code...


                echo "<div class='alert alert-success  col-12 alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                <strong>Done!</strong> User Updated</div>";
              }
              ?>

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<script>
  function openForm() {
    document.getElementById("resEdit").style.display = "block";
  }

  function closeForm() {
    document.getElementById("resEdit").style.display = "none";
  }
</script>