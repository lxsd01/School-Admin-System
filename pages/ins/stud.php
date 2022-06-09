<?php include("../../auth_session.php");
require_once "../../db.php"; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="select2.min.css" />
	<title>Instructor Dashboard</title>
</head>
<body style="background-image: url(../../pictures/bg_log.jpg);no-repeat center center fixed; 
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
              background-size: cover;">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="../../dashboard_ins.php"><i class="bi bi-house"></i> <?php echo $_SESSION['username']; ?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">

				<li class="nav-item">
					<a href="stud.php" class = "nav-link active">My Students</a>
				</li>
				<li class="nav-item">
					<a href="content.php" class = "nav-link">Learning Content</a>
				</li>
				<li class="nav-item">
					<a href="../annINS.php" class = "nav-link">Announcements</a>
				</li>


			</ul>
			<a href="../../logout.php"><button class="btn btn-outline-primary my-2 my-sm-0" type="submit" >Logout</button></a>
		</div>
	</nav>
	<br>
	<br>

	<div class="container">

		<!-- Results recording and modification -->
		<div class="card shadow-lg bg-white rounded border-none mb-4">


			<div class="card-body">
				<h4>Records Update/Delete</h4>
				<div class="card shadow-lg bg-white rounded border-none mb-4">


					<div class="card-body">
						<h5>Select Student</h5>
						<form action="" method="post" accept-charset="utf-8">
							<div class="row">
								<div class="col-lg-8">
									<select id="studentX" class="form-control form-control-lg"  name="studentX" required>
										<option value="" disabled selected hidden>My Student List</option>
										<?php 
										$Engine = $con->prepare('SELECT * FROM students WHERE instructor=?');
										$Engine->bind_param('s',$crnt);
										$crnt = $_SESSION['id'];
										$Engine->execute();
										$cRes = $Engine->get_result();
										if($cRes){
											while ($row = $cRes->fetch_assoc()){ ?>
												<option value="<?=$row['id']?>" name="student_id"><?=$row['name']?> <?=$row['surname']?></option>
											<?php }} $Engine->close(); ?>
										</select>
									</div>
									<div class="col-lg-4">
										<button type="submit" class="form-control form-control-sm btn-warning" name = "editor">Edit</button>
									</div>
									
								</div>  
							</form>
							<br>
							<div class="row">


								<div class="col-lg-12">

									<?php 
							//checking if form has been submitted
									if (isset($_POST['editor'])) {
								# code...

									 ?>
									 <p class = 'text-center text-danger'>Showing All Results.</p>
									 <table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Date Recorded</th>
												<th>Time Period</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
									//Populaling The Table
											$table = $con->prepare("SELECT * FROM subjects where stuID = ?");
											$table->bind_param('i',$id);
											$id = $_POST['studentX'];
											$table->execute();
											$result = $table->get_result();
											if ($result) {
									# code...
												while($row = $result->fetch_assoc()) { ?>
													<tr>
														<td><?php echo $row['month']; ?></td>
														<td><?php echo $row['type']; ?></td>
														<td>
															<div class="row">
																<form action="" method="post" accept-charset="utf-8">
																	<input type="" name="toEdit"  value="<?php echo $row['resID'];?>" hidden>
																	<div class="row">
																		<button type="submit" class="btn btn-warning m-1" name = "editRow">Update</button>
																		<button type="submit" class="btn btn-danger m-1"  name = "deleteRow">Delete</button>
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
										 <strong>Done!</strong> Results Deleted</div>";
									 }
								 }

											//Edit Results row 
								 if (isset($_POST['editRow'])) {

												//Adding previous values
									$prev = $con->prepare("SELECT * FROM subjects where resID = ?");
									$prev->bind_param('i',$id);
									$id = $_POST['toEdit'];
									$prev->execute();
									$result = $prev->get_result();
									if ($result) {
												# code...
										while($row = $result->fetch_assoc()) { 
											?>

											<div class="container" id="resEdit">

												<form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
													<div class="card shadow-lg bg-white rounded m-4">
														<div class="card-body">
															<p class = "alert alert-success col-12">Enter <b>New</b> Percentage (<b>XX</b>%) Marks <br>Only numerical values ranging 0 to 100, the form contains previous values <br>Unfilled fields' values will be retained </p>
															<br>
															<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="English" placeholder="English <?php echo 'Previous :'.$row['English']; ?>">
															<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Setswana" placeholder="Setswana  <?php echo 'Previous :'.$row['Setswana']; ?>">
															<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Mathematics" placeholder="Mathematics <?php echo 'Previous :'.$row['Mathematics']; ?>">
															<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Science" placeholder="Science <?php echo 'Previous :'.$row['Science']; ?>">
															<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="CAPA" placeholder="CAPA  <?php echo 'Previous :'.$row['CAPA']; ?>">
															<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="social" placeholder="Social Studies <?php echo 'Previous :'.$row['Social_Studies']; ?>">
															<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Agriculture" placeholder="Agriculture <?php echo 'Previous :'.$row['Agriculture']; ?>">

															<!-- sending results row id to the update query -->
															<input type="" name="rowID" value=" <?php echo $_POST['toEdit']; ?> " hidden>
														</div>
													</div>
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

											</div>

											<?php
										}
									}
								}
									// Updating results in selected row individually
								if (isset($_POST['editRes'])) {
										# code...

									if(!empty($_POST['English'])){
										$updt = $con->prepare("UPDATE subjects SET English  =? where resID = ?");
										$updt->bind_param("ii",$_POST['English'], $_POST['rowID']);
										$updt->execute();

										$updt->close();
									}if(!empty($_POST['Setswana'])){
										$updt = $con->prepare("UPDATE subjects SET Setswana =? where resID = ?");
										$updt->bind_param("ii",$_POST['Setswana'], $_POST['rowID']);
										$updt->execute();
										$updt->close();

									}if(!empty($_POST['Mathematics'])){
										$updt = $con->prepare("UPDATE subjects SET Mathematics =? where resID = ?");
										$updt->bind_param("ii",$_POST['Mathematics'],$_POST['rowID']);
										$updt->execute();
										$updt->close();

									}if(!empty($_POST['Science'])){
										$updt = $con->prepare("UPDATE subjects SET Science =? where resID = ?");
										$updt->bind_param("ii",$_POST['Science'], $_POST['rowID']);
										$updt->execute();
										$updt->close();

									}if(!empty($_POST['CAPA'])){
										$updt = $con->prepare("UPDATE subjects SET CAPA =? where resID = ?");
										$updt->bind_param("ii",$_POST['CAPA'], $_POST['rowID']);
										$updt->execute();
										$updt->close();

									}if(!empty($_POST['social'])){
										$updt = $con->prepare("UPDATE subjects SET Social_Studies =? where resID = ?");
										$updt->bind_param("ii",$_POST['social'], $_POST['rowID']);
										$updt->execute();
										$updt->close();

									}if(!empty($_POST['Agriculture'])){
										$updt = $con->prepare("UPDATE subjects SET Agriculture =? where resID = ?");
										$updt->bind_param("ii",$_POST['Agriculture'], $_POST['rowID']);
										$updt->execute();
										$updt->close();

									}

									echo "<div class='alert alert-success  col-12 alert-dismissible'>
									<button type='button' class='close' data-dismiss='alert'>&times;</button>
									<strong>Done!</strong> Results Updated</div>";
								}
								?>

							</div>
						</div>
					</div>

				</div>
			</div>

			<div class="container">
				<div class="card shadow-lg bg-white rounded mb-4">


					<div class="card-body">
						<h4>New Results Recording Pane</h4>
						<div class="row">

							<div class="col-lg-6">
								<div class="card shadow-lg bg-white rounded mb-4">

									<div class="card-body ">
										<h4>Month : <?php echo date("F Y"); ?></h4>
										<h5>Select Student Below For Continous Assesment Marks</h5>
					
										<?php 

								//Inserting data for monthly reports

										if (isset($_POST['resMonth'])) {


												// Sanitizing inputs
											$stuID = sanitizer($_POST["instructor"]);
											$English = sanitizer($_POST["English"]);
											$Setswana = sanitizer($_POST["Setswana"]);
											$Mathematics = sanitizer($_POST["Mathematics"]);
											$Science = sanitizer($_POST["Science"]);
											$CAPA = sanitizer($_POST["CAPA"]);
											$Social_Studies = sanitizer($_POST["social"]);
											$Agriculture = sanitizer($_POST["Agriculture"]);
											$type = sanitizer($_POST["trm"]);
											$month = date("d/F/Y");

											$monthly = $con->prepare('INSERT INTO subjects(stuID,English,Setswana,Mathematics,Science,CAPA,Social_Studies,Agriculture,type,month) VALUES(?,?,?,?,?,?,?,?,?,?)') ;
											$monthly->bind_param("iiiiiiiiss", $sid,$eng,$sets,$math,$sci,$cp,$ss,$agr,$t,$mnt);
											$sid = $stuID;
											$eng = $English;
											$sets = $Setswana;
											$math = $Mathematics;
											$sci = $Science;
											$cp = $CAPA;
											$ss = $Social_Studies;
											$agr = $Agriculture;
											$t = $type;
											$mnt = $month;
											$res = $monthly->execute();

											if($res){
												echo "<div class='alert alert-success  col-12 alert-dismissible'>
												<button type='button' class='close' data-dismiss='alert'>&times;</button>
												<strong>Success!</strong>Results Added</div>";
											}else{


												echo "<div class='alert alert-danger  col-12 alert-dismissible'>
												<button type='button' class='close' data-dismiss='alert'>&times;</button>
												<strong>Results Invalid!</strong>Results already exist, if not try again</div>";
											}
											$monthly->close();
										}
										?>
										<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
											<div class="row">
												<div class="col-lg-8">
													<select  id="stu_search"  class="form-control form-control-lg"name="instructor" required>
														<option value="" disabled selected hidden>Students List</option>
														<?php



														$select_generate = $con->prepare('SELECT * FROM students WHERE instructor=?');
														$select_generate->bind_param('s',$val);
														$val = $_SESSION["id"];
														$select_generate->execute();
														$result = $select_generate->get_result();
														while ($row = $result->fetch_assoc()):


															?>

															<option value="<?=$row['id']?>"><?=$row['name']?> <?=$row['surname']?></option>
														<?php endwhile; $select_generate->close();

														?>
													</select>
												</div>
												<div class="col-lg-4">
													<select name="trm" class="form-control form-control-sm" required>
														<option value="" disabled selected hidden>Period</option>

														<option value="January">January</option>
														<option value="February">February</option>
														<option value="March">March</option>
														<option value="April">April</option>
														<option value="May">May</option>
														<option value="June">June</option>
														<option value="July">July</option>
														<option value="August">August</option>
														<option value="September">September</option>
														<option value="October">October</option>
														<option value="November">November</option>
														
														
													</select>
												</div>
											</div>
											<div class="card shadow-lg bg-white rounded m-4">
												<div class="card-body">
													<p class = "alert alert-success col-12">Enter Percentage (<b>XX</b>%) Marks <br>Only numerical values ranging 0 to 100</p>
													<br>
													<input type="number" class="form-control form-control-lg m-1"  min="0" max="100" title="Enter numerical percentage 1 to 100" name="English" placeholder="English">
													<input type="number" class="form-control form-control-lg m-1"  min="0" max="100" title="Enter numerical percentage 1 to 100" name="Setswana" placeholder="Setswana">
													<input type="number" class="form-control form-control-lg m-1"  min="0" max="100" title="Enter numerical percentage 1 to 100" name="Mathematics" placeholder="Mathematics">
													<input type="number" class="form-control form-control-lg m-1"  min="0" max="100" title="Enter numerical percentage 1 to 100" name="Science" placeholder="Science">
													<input type="number" class="form-control form-control-lg m-1"  min="0" max="100" title="Enter numerical percentage 1 to 100" name="CAPA" placeholder="CAPA">
													<input type="number" class="form-control form-control-lg m-1"  min="0" max="100" title="Enter numerical percentage 1 to 100" name="social" placeholder="Social Studies">
													<input type="number" class="form-control form-control-lg m-1" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Agriculture" placeholder="Agriculture">
												</div>
											</div>
											<input type="submit" class="btn btn-primary form-control form-control-lg" name="resMonth" value = "Send">

										</form>

									</div>
								</div>
							</div>

							<div class="col-lg-6">
								<div class="card shadow-lg bg-white rounded mb-4">

									<div class="card-body ">
										<h4><?php
										$currentMonth = date("F");
										$currentTerm = "";
										if($currentMonth == "January" || $currentMonth == "February" || $currentMonth == "March" || $currentMonth == "April"){
											$currentTerm = "Term 1";
										}elseif($currentMonth == "May" || $currentMonth == "June" || $currentMonth == "July" || $currentMonth == "August"){
											$currentTerm = "Term 2";
										}else{
											$currentTerm = "Term 3";
										}
										echo $currentTerm;
										?></h4>
										<h5>Select Student Below For Term Results Records</h5>
										<?php 
									//Inserting data

										if (isset($_POST['resTerm'])) {
												// code...
											$stuID = sanitizer($_POST["instructor"]);
											$English = sanitizer($_POST["English"]);
											$Setswana = sanitizer($_POST["Setswana"]);
											$Mathematics = sanitizer($_POST["Mathematics"]);
											$Science = sanitizer($_POST["Science"]);
											$CAPA = sanitizer($_POST["CAPA"]);
											$Social_Studies = sanitizer($_POST["social"]);
											$Agriculture = sanitizer($_POST["Agriculture"]);
											$type = sanitizer($_POST["trm"]);
											$month = date("d/F/Y");

											$monthly = $con->prepare('INSERT INTO subjects(stuID,English,Setswana,Mathematics,Science,CAPA,Social_Studies,Agriculture,type,month) VALUES(?,?,?,?,?,?,?,?,?,?)') ;
											$monthly->bind_param("iiiiiiiiss", $sid,$eng,$sets,$math,$sci,$cp,$ss,$agr,$t,$mnt);
											$sid = $stuID;
											$eng = $English;
											$sets = $Setswana;
											$math = $Mathematics;
											$sci = $Science;
											$cp = $CAPA;
											$ss = $Social_Studies;
											$agr = $Agriculture;
											$t = $type;
											$mnt = $month;
											$res = $monthly->execute();

											if($res){
												echo "<div class='alert alert-success  col-12 alert-dismissible'>
												<button type='button' class='close' data-dismiss='alert'>&times;</button>
												<strong>Success!</strong>Results Added</div>";
											}else{


												echo "<div class='alert alert-danger  col-12 alert-dismissible'>
												<button type='button' class='close' data-dismiss='alert'>&times;</button>
												<strong>Results Invalid!</strong>Results already exist, if not try again</div>";
											}
											$monthly->close();
										}
										function sanitizer($data){
											$data = trim($data);
											$data = stripslashes($data);
											$data = htmlspecialchars($data);
											return $data;
										}
										?>



										<form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
											<div class="row">
												<div class="col-lg-8">
													<select  class="form-control form-control-lg" id="stu_search2" name="instructor" required>
														<option value="" disabled selected hidden>Students List</option>
														<?php



														$select_generate = $con->prepare('SELECT * FROM students WHERE instructor=?');
														$select_generate->bind_param('s',$val);
														$val = $_SESSION["id"];
														$select_generate->execute();
														$result = $select_generate->get_result();
														while ($row = $result->fetch_assoc()):


															?>

															<option value="<?=$row['id']?>"><?=$row['name']?> <?=$row['surname']?></option>
														<?php endwhile; $select_generate->close();

														?>
													</select>
												</div>
												<div class="col-lg-4">
													<select name="trm"  class="form-control form-control-sm" required>
														<option value="" disabled selected hidden>Period</option>

														<option value="Term 1" >Term 1</option>
														<option value="Term 2" >Term 2</option>
														

													</select>
												</div>
											</div>

											<div class="card shadow-lg bg-white rounded m-4">
												<div class="card-body">
													<p class = "alert alert-success col-12">Enter Percentage (<b>XX</b>%) Marks <br>Only numerical values ranging 0 to 100</p>
													<br>
													<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="English" placeholder="English">
													<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Setswana" placeholder="Setswana">
													<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Mathematics" placeholder="Mathematics">
													<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Science" placeholder="Science">
													<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="CAPA" placeholder="CAPA">
													<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="social" placeholder="Social Studies">
													<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Agriculture" placeholder="Agriculture">
													<div class="input-group">
														<span class="input-group-text">Teacher's Remarks</span>
														<textarea class="form-control" aria-label="With textarea"></textarea>
													</div>
												</div>
											</div>
											<input type="submit" class="btn btn-primary form-control form-control-lg" name = "resTerm" value = "Send">

										</form>


									</div>
								</div>
							</div>

							
				</div>
			</div>
		</div>
<div class="container">


	<div class="card shadow-lg bg-white rounded m-4">
		<div class="card-body">
			<h4>Yearly Results Updates</h4>
			<div class="col-lg-12">
				<form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<div class="row">
						<div class="col-lg-12">
							<select  class="form-control form-control-lg" id="stu_search3" name="instructor" required>
								<option value="" disabled selected hidden>Students List</option>
								<?php



								$select_generate = $con->prepare('SELECT * FROM students WHERE instructor=?');
								$select_generate->bind_param('s',$val);
								$val = $_SESSION["id"];
								$select_generate->execute();
								$result = $select_generate->get_result();
								while ($row = $result->fetch_assoc()):


									?>

									<option value="<?=$row['id']?>"><?=$row['name']?> <?=$row['surname']?></option>
								<?php endwhile; $select_generate->close();

								?>
							</select>
						</div>

					</div>

					<div class="card shadow-lg bg-white rounded m-4">
						<div class="col-lg-12">
							<div class="card-body">
								<?php //Inserting data

										if (isset($_POST['examres'])) {
												// code...
											$stuID = sanitizer($_POST["instructor"]);
											$English = sanitizer($_POST["English"]);
											$Setswana = sanitizer($_POST["Setswana"]);
											$Mathematics = sanitizer($_POST["Mathematics"]);
											$Science = sanitizer($_POST["Science"]);
											$CAPA = sanitizer($_POST["CAPA"]);
											$Social_Studies = sanitizer($_POST["social"]);
											$Agriculture = sanitizer($_POST["Agriculture"]);
											$type = "Term 3";
											$month = date("d/F/Y");

											$remarks = sanitizer($_POST["remarks"]);
											$behaviour = sanitizer($_POST["behaviour"]);
											$activities = sanitizer($_POST["activities"]);
											$punctuality = sanitizer($_POST["punctuality"]);
											$neatness = sanitizer($_POST["neatness"]);
											$assmnt = sanitizer($_POST["assmnt"]);
											$health = sanitizer($_POST["health"]);

											$monthly = $con->prepare('INSERT INTO subjects(stuID,English,Setswana,Mathematics,Science,CAPA,Social_Studies,Agriculture,type,month) VALUES(?,?,?,?,?,?,?,?,?,?)') ;
											$monthly->bind_param("iiiiiiiiss", $sid,$eng,$sets,$math,$sci,$cp,$ss,$agr,$t,$mnt);
											$sid = $stuID;
											$eng = $English;
											$sets = $Setswana;
											$math = $Mathematics;
											$sci = $Science;
											$cp = $CAPA;
											$ss = $Social_Studies;
											$agr = $Agriculture;
											$t = $type;
											$mnt = $month;
											$res = $monthly->execute();

											if($res){

												$table = $con->prepare("SELECT * FROM subjects where stuID = ? AND type = ?");
												$table->bind_param('is',$id,$typ);
												$id = $_POST['instructor'];
												$typ = "Term 3";
												$table->execute();
												$result = $table->get_result();

												$get_resid = "";
												if ($result) {
												# code...
													while($row = $result->fetch_assoc()) {
														$get_resid = $row['resID'];
													}
												}

												$eval = $con->prepare('INSERT INTO evaluation (resID, behaviour, activities, punctuality, neatness, assignments, health, remarks) VALUES(?,?,?,?,?,?,?,?)' );
												echo $get_resid.$behaviour.$activities.$punctuality.$neatness.$assmnt.$assmnt.$remarks;
												$eval->bind_param('iiiiiiis',$get_resid,$behaviour,$activities,$punctuality,$neatness,$assmnt,$assmnt,$remarks);
												$evalres = $eval->execute();
												if ($evalres) {
													echo "<div class='alert alert-success  col-12 alert-dismissible'>
													<button type='button' class='close' data-dismiss='alert'>&times;</button>
													<strong>Success!</strong>Results Added</div>";
												}
											}else{


												echo "<div class='alert alert-danger  col-12 alert-dismissible'>
												<button type='button' class='close' data-dismiss='alert'>&times;</button>
												<strong>Results Invalid!</strong>Results already exist, if not try again</div>";
											}
											$monthly->close();
										} ?>
							<p class = "alert alert-success col-12">Enter Percentage (<b>XX</b>%) Marks <br>Only numerical values ranging 0 to 100</p>
							<br>
							<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="English" placeholder="English">
							<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Setswana" placeholder="Setswana">
							<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Mathematics" placeholder="Mathematics">
							<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Science" placeholder="Science">
							<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="CAPA" placeholder="CAPA">
							<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="social" placeholder="Social Studies">
							<input type="number" class="form-control form-control-lg m-1" pattern="[0-9]" min="0" max="100" title="Enter numerical percentage 1 to 100" name="Agriculture" placeholder="Agriculture">
							<div class="input-group">
								<span class="input-group-text">Teacher's Remarks</span>
								<textarea class="form-control" aria-label="With textarea" name="remarks"></textarea>
							</div>
						</div>
						</div>
						<div class="col-lg-12">
					<div class="card shadow-lg bg-white rounded m-4">
						<div class="card-body">
							<p class = "alert alert-success col-12">Student's Overall Yearly Assesment Ratings</p>
							<br>
							<i><strong class="text-danger text-center">5 Unsatisfactory 4 Average 3 Fair 2 Good 1 Excellent</strong></i>
							<div class="row m-2">
								Behaviour:
								<hr>
								<div class="btn-group btn-group-toggle" role="group" aria-label="Basic radio toggle button group 6">
									<label class="btn btn-outline-primary active" for="behaviour1">
									<input type="radio" name="behaviour" id="behaviour1" autocomplete="off" value="1" checked >
									1</label>
									<label class="btn btn-outline-primary" for="behaviour2">
									<input type="radio" name="behaviour" id="behaviour2" autocomplete="off" value="2">
									2</label>
									<label class="btn btn-outline-primary" for="behaviour3">
									<input type="radio" name="behaviour" id="behaviour3" autocomplete="off" value="3">
									3</label>
									<label class="btn btn-outline-primary" for="behaviour4">
									<input type="radio" name="behaviour" id="behaviour4" autocomplete="off"  value="4">
									4</label>
									<label class="btn btn-outline-primary" for="behaviour5">
									<input type="radio" name="behaviour" id="behaviour5" autocomplete="off" value="5">
									5</label>
								</div>
							</div>
							<div class="row m-2">
								School Activities:
								<hr>
								<div class="btn-group btn-group-toggle" data-toggle="buttons">
								  <label class="btn btn-outline-primary active">
								    <input type="radio" name="activities" id="Activities1" autocomplete="off" value="1" checked> 1
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="activities" id="Activities2" autocomplete="off" value="2"> 2
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="activities" id="Activities3" autocomplete="off" value="3"> 3
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="activities" id="Activities4" autocomplete="off" value="4"> 4
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="activities" id="Activities5" autocomplete="off" value="5"> 5
								  </label>
								</div>
							</div>
							<div class="row m-2">
								Panctuality:
								<hr>
								<div class="btn-group btn-group-toggle" data-toggle="buttons">
								  <label class="btn btn-outline-primary active">
								    <input type="radio" name="punctuality" id="punctuality1" autocomplete="off" value="1" checked> 1
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="punctuality" id="punctuality2" autocomplete="off" value="2"> 2
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="punctuality" id="punctuality3" autocomplete="off" value="3"> 3
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="punctuality" id="punctuality4" autocomplete="off" value="4"> 4
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="punctuality" id="punctuality5" autocomplete="off" value="5"> 5
								  </label>
								</div>
							</div>
							<div class="row m-2">
								Neatness:
								<hr>
								<div class="btn-group btn-group-toggle" data-toggle="buttons">
								  <label class="btn btn-outline-primary active">
								    <input type="radio" name="neatness" id="neatness1" autocomplete="off" value="1" checked> 1
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="neatness" id="neatness2" autocomplete="off" value="2"> 2
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="neatness" id="neatness3" autocomplete="off" value="3"> 3
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="neatness" id="neatness4" autocomplete="off" value="4"> 4
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="neatness" id="neatness5" autocomplete="off" value="5"> 5
								  </label>
								</div>
							</div>
							<div class="row m-2">
								Carrying Out Assignment:
								<hr>
								<div class="btn-group btn-group-toggle" data-toggle="buttons">
								  <label class="btn btn-outline-primary active">
								    <input type="radio" name="assmnt" id="assmnt1" autocomplete="off" value="1" checked> 1
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="assmnt" id="assmnt2" autocomplete="off" value="2"> 2
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="assmnt" id="assmnt3" autocomplete="off" value="3"> 3
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="assmnt" id="assmnt4" autocomplete="off" value="4"> 4
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="assmnt" id="assmnt5" autocomplete="off" value="5"> 5
								  </label>
								</div>
							</div>
							<div class="row m-2">
								Health:
								<hr>
								<div class="btn-group btn-group-toggle" data-toggle="buttons">
								  <label class="btn btn-outline-primary active">
								    <input type="radio" name="health" id="health1" autocomplete="off" value="1" checked> 1
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="health" id="health2" autocomplete="off" value="2"> 2
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="health" id="health3" autocomplete="off" value="3"> 3
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="health" id="health4" autocomplete="off" value="4"> 4
								  </label>
								  <label class="btn btn-outline-primary">
								    <input type="radio" name="health" id="health5" autocomplete="off" value="5"> 5
								  </label>
								</div>
							</div>

						</div>
					</div>
					<input type="submit" class="btn btn-primary form-control form-control-lg mb-3" name = "examres" value = "Send">
				</form>
					
				
			</div>
					</div>
				</div>
			</div>
			
		</div>

	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="../bootstrap/jsbootstrap.min.js"></script>
	<script>
		function openForm() {
			document.getElementById("resEdit").style.display = "block";
		}

		function closeForm() {
			document.getElementById("resEdit").style.display = "none";
		}
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
		</script>
	<script src="select2.min.js"></script>
		<script>
			$("#studentX").select2( {
				placeholder: "Search Student",
				allowClear: true
			} );
		</script>
		<script>
			$("#stu_search").select2( {
				placeholder: "Search Student",
				allowClear: true
			} );
		</script>
		<script>
			$("#stu_search2").select2( {
				placeholder: "Search Student",
				allowClear: true
			} );
		</script>
		<script>
			$("#stu_search3").select2( {
				placeholder: "Search Student",
				allowClear: true
			} );
		</script>
</body>
</html>
