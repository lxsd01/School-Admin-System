<!--Remove User Card-->

    <div class="card shadow-lg bg-white rounded border-none mb-4">
      <div class="card-body">
        <h3 class="card-title alert alert-danger text-center">Delete User</h3>
        <?php
        if(isset($_POST["del"])){
          $item = trim($_POST["delopt"]);
          $del = $con->prepare("DELETE FROM users WHERE id = ?");
          $del->bind_param('s',$val);
          $val = $item;
          $res = $del->execute();
          if ($res) {
            echo "<div class='alert alert-success  col-12 alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            <strong>Deleted!</strong></div>";
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
                  <option value="" disabled selected hidden>Select User</option>
                  <?php
                  $select_generate = $con->prepare('SELECT * FROM users');
                  $select_generate->execute();
                  $result = $select_generate->get_result();
                  while ($row = $result->fetch_assoc()):
                  ?>

                  <option value="<?=$row['id']?>"><?=$row['username']?> | Role: <?=$row['designation']?></option>
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