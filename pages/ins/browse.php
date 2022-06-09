<?php include("../../auth_session.php");
require_once "../../db.php"; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

	<title>Content Managenent</title>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="../../dashboard_stu.php"><?php echo $_SESSION['username']; ?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a href="browse.php" class = "nav-link active">Learning Content</a>
				</li>
				<li class="nav-item">
					<a href="../annSTU.php" class = "nav-link">Announcements</a>
				</li>


			</ul>
			<a href="../../logout.php"><button class="btn btn-outline-primary my-2 my-sm-0" type="submit" >Logout</button></a>
		</div>
	</nav>

	<br>
	<br>
	<div class="container">
		<h3>Offline Learning Material Download</h3>
		<h5>Browsing Content Uploaded For : 
			<?php 
			$grd = $con->prepare("SELECT * FROM students WHERE id = ?");
			$grd->bind_param('i',$ses);
			$ses = $_SESSION['id'];
			$grd->execute();
			$grade = 0;
			$res = $grd->get_result();
			if($res){
				while ($row = $res->fetch_assoc()){
					if($row['grade'] > 0){
						echo "Standard - ".$row['grade'];
						$grade = $row['grade'];
					}else{
						echo "Kindergarten";
					}
				}
			}
			$grd->close();
			?>
		</h5>

		<div class=" card shadow-lg mb-4">
			<div class="card-body">
				<div class="container">



					<div class="row">


								<!-- Hover card filtering the table according to subject and the term it was uploaded on -->
								<div class="col-lg-12">
									<div class=" card shadow-lg mb-4">
										<div class="card-body">
											<h5>Select below the <span class="badge badge-info">subject</span> and the <span class="badge badge-info">term</span> to show content</h5>
											<b class="text-info"><i>Alternatively :</i>  
												<form action="" method="post" accept-charset="utf-8"  class="p-2">												
													<input type="submit" name="showAll" value="Show All Content" class="btn btn-outline-danger ">
												</form>
											</b>
											<form action="" method="post" accept-charset="utf-8">
												<div class="row">
													<div class="col-lg-4">
														<select name="sub" class="form-control form-control-lg" required>
															<option value="" disabled selected hidden>Select Subject</option>

															<option value="English" >English</option>
															<option value="Setswana" >Setswana</option>
															<option value="Mathematics" >Mathematics</option>
															<option value="Science" >Science</option>
															<option value="CAPA" >CAPA</option>
															<option value="Social Studies" >Social Studies</option>
															<option value="Agriculture" >Agriculture</option>
														</select>
													</div>
													<div class="col-lg-4">
														<select name="trm" class="form-control form-control-lg" required>
															<option value="" disabled selected hidden>Select Term</option>

															<option value="Term 1" >Term 1</option>
															<option value="Term 2" >Term 2</option>
															<option value="Term 3" >Term 3</option>
															
														</select>
													</div>
													<div class="col-lg-4">
														<input type="submit" name="tbl" value="Filter" class="btn btn-primary form-control form-control-lg">
													</div>

												</div>

											</form>

											<hr>
											<?php 
											# Cheking inputs and filtering

											if (isset($_POST['tbl'])) {
												# code...
												filteredTable($con,$grade,$_POST['sub'],$_POST['trm']);
											}

											if (isset($_POST['showAll'])) {
												# code...
												allFiles($con,$grade);
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
			<?php 
			# This is a table that has filtered file uploads
			# parameters $subject and $term are used to filter the files
			function filteredTable($c,$gr,$subject,$term){

				echo "<p class = 'text-center text-danger'>All ".$subject." material for ".$term."</p>";

				echo 
				'<div class="row">
				<div class="col-lg-12">
				<table class="table table-striped table-hover">
				<thead>
				<tr>
				<th>#</th>
				<th>File Name</th>
				<th>View</th>
				<th>Action</th>
				</tr>
				</thead>
				<tbody>';

			//Populaling The Table
				$table = $c->prepare("SELECT filename FROM uploads WHERE grade = ? and subject = ? and term = ?");
				$table->bind_param('iss',$g , $s,$t);
				$g = $gr;
				$s = $subject;
				$t = $term;
				$table->execute();
				$result = $table->get_result();
				$i = 1;
				if ($result) {
				# code...
					while($row = $result->fetch_assoc()) { 
						echo '<tr>
						<td>'; 
						echo $i++;
						echo '</td>';

						echo '<td>'; echo $row['filename'];
						echo '</td>
						<td><a target="_blank" href="../../uploads/';echo $row["filename"]; echo '">View</a></td>
						<td>
						<div class="row">
						<a href="../../uploads/'; echo '$row["filename"];'; echo '" download>
						<span class="badge badge-success p-1 m-2">
						Download</span></a>
						</div>

						</td>
						</tr>';
					} }


					echo	'</tbody>
					</table>';


					echo	'</div>
					</div>';

				}
				// this table contains all files
				function allFiles($c,$gr){

					echo "<p class = 'text-center text-danger'>All Files</p>";
					echo 
					'<div class="row">
					<div class="col-lg-12">
					<table class="table table-striped table-hover">
					<thead>
					<tr>
					<th>#</th>
					<th>File Name</th>
					<th>View</th>
					<th>Action</th>
					</tr>
					</thead>
					<tbody>';

			//Populaling The Table
					$table = $c->prepare("SELECT filename FROM uploads where grade = ?");
					$table->bind_param('i',$g);
					$g = $gr;
					$table->execute();
					$result = $table->get_result();
					$i = 1;
					if ($result) {
				# code...
						while($row = $result->fetch_assoc()) { 
							echo '<tr>
							<td>'; 
							echo $i++;
							echo '</td>';

							echo '<td>'; echo $row['filename'];
							echo '</td>
							<td><a target="_blank" href="../../uploads/';echo $row["filename"]; echo '">View</a></td>
							<td>
							<div class="row">
							<a href="../../uploads/'; echo '$row["filename"];'; echo '" download>
							<span class="badge badge-success p-1 m-2">
							Download</span></a>
							
							</div>

							</td>
							</tr>';
						} }


						echo	'</tbody>
						</table>

						<form action="" method="post" accept-charset="utf-8"  class="p-2">												<div class = "col-lg-12">
						<input type="submit" name="close" value="Close" class="btn btn-outline-danger "></div>
						</form> ';

						if (isset($_POST['close'])) {
							header("Location: content.php");
						}

						echo	'</div>
						</div>';

					}
					?>

				</body>
				</html>
