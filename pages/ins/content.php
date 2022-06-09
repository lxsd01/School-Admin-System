<?php include("../../auth_session.php");
require_once "../../db.php"; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

<link rel="stylesheet" href="select2.min.css" />
	<title>Content Managenent</title>
</head>
<body style="background-image: url(../../pictures/bg_log.jpg);no-repeat center center fixed; 
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
              background-size: cover;">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="../../dashboard_ins.php">  <?php echo $_SESSION['username']; ?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">

				<li class="nav-item">
					<a href="stud.php" class = "nav-link">My Students</a>
				</li>
				<li class="nav-item">
					<a href="content.php" class = "nav-link active">Learning Content</a>
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
		<h3>Offline Learning Material Upload/Download</h3>
		<h5>Managing Content For : 
			<?php 
			$grd = $con->prepare("SELECT * FROM instructor WHERE id = ?");
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
		<?php 

//check if form is submitted
		if (isset($_POST['submit']))
		{
			$filename = $_FILES['file1']['name'];

//upload file
			if($filename != '')
			{
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$allowed = ['pdf', 'txt', 'doc', 'docx','xls','xlsx','ppt','pptx', 'png', 'jpg', 'jpeg',  'gif'];

//check if file type is valid
				if (in_array($ext, $allowed))
				{
// get last record id
					$sql = 'select max(id) as id from uploads';
					$result = mysqli_query($con, $sql);
					if (count($result) > 0)
					{
						$row = mysqli_fetch_array($result);
						$filename = ($row['id']+1) . '-' . $filename;
					}
					else
						$filename = '1' . '-' . $filename;

//set target directory
					$path = '../../uploads/';

					$created = @date('Y-F-d H:i:s');
					move_uploaded_file($_FILES['file1']['tmp_name'],($path . $filename));

// insert file details into database
					$sql = $con->prepare("INSERT INTO uploads(grade, subject, term, filename, created) VALUES(?,?,?,?,?)");
					$sql->bind_param("issss",$gr,$sub,$trm,$fn,$cr);
					$gr = $grade;

					$currentMonth = date("F");
					$trm = "";
					if($currentMonth == "January" || $currentMonth == "February" || $currentMonth == "March" || $currentMonth == "April"){
						$trm = "Term 1";
					}elseif($currentMonth == "May" || $currentMonth == "June" || $currentMonth == "July" || $currentMonth == "August"){
						$trm = "Term 2";
					}else{
						$trm = "Term 3";
					}

					$sub = sanitizer($_POST['subject']);
					$fn = $filename;
					$cr = $created;
					$res = $sql->execute();

					if($res){
						header("Location: content.php?st=success");
					}
					else{
						echo $gr." ".$sub." ".$trm." ".$fn." ".$cr;
						header("Location: content.php?st=error");
					}

				}
				else
				{
					header("Location: content.php?st=error");
				}
			}
			else
				header("Location: content.php");
		}
		function sanitizer($data){
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		?>

		<div class=" card shadow-lg mb-4">
			<div class="card-body">
				<div class="container">


					<form action="" method="post" enctype="multipart/form-data">
						<legend>Select File to Upload:</legend>
						<div class="row p-2">
							<div class="col-lg-4">
								<input type="file" class="form-control form-control-lg" name="file1" aria-label="file example" required>
							</div>
							<div class="col-lg-3">
								<select name="subject" id="subs" class="form-control form-control-lg" >
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
							<div class="col-lg-3">
								<select name="subject" class="form-control form-control-lg" >
									<option value="" disabled selected hidden>Type</option>

									<option value="ca" >Exercise/Homework</option>
									<option value="content" >Learning Content</option>
									
								</select>
							</div>
							<div class="col-lg-2 p-1">
								<input type="submit" name="submit" value="Upload" class="btn btn-primary form-control form-control-lg">
							</div>
						</div>




						<?php if(isset($_GET['st'])) { ?>

							<?php if ($_GET['st'] == 'success') {
								echo '<div class="alert alert-success text-center">';
								echo "File Uploaded Successfully!";
								echo "</div>";
							}
							else
							{
								echo '<div class="alert alert-danger text-center">';
								echo 'Invalid File Extension!';
								echo "</div>";
							} 
						}?>


					</form>

					<!-- Hover card filtering the table according to subject and the term it was uploaded on -->
					<div class="col-lg-12">
						<div class=" card shadow-lg mb-4">
							<div class="card-body">
								<h5>Select below the subject and the term to show content</h5>
								<b class="text-info"><i>Options :</i>  
									<form action="" method="post" accept-charset="utf-8"  class="p-2">
										<div class="row">
										<div class="col-lg-8">
											<select name="file_search" id="file_search" class="form-control form-control-lg" required>
												<option value="" disabled selected hidden>Select File</option>
												<?php
												$select_generate = $con->prepare('SELECT * FROM uploads');
												$select_generate->execute();
												$result = $select_generate->get_result();
												while ($row = $result->fetch_assoc()):
													?>

													<option value="<?=$row['id']?>"><?=$row['filename']?></option>
												<?php endwhile; ?>
											</select>
										</div>
										<div class="col-4">
											<input type="submit" name="showAll" value="Search" class="form-control form-control-sm btn-warning ">
										</div>

									</div>
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
			<form action="" method="post" accept-charset="utf-8">';

			$to_del = $row['filename'];
			echo '<span class="badge badge-danger p-1 m-2">
			<input type="submit"  name="delete" value="Delete" style="text-decoration: none; background-color: none; border: none;"></span>
			</form>
			</div>

			</td>
			</tr>';
		} }


		echo	'</tbody>
		</table>';

	//Deleting File
		if (isset($_POST['delete'])) {
		# code...
			$fdel = $con->prepare("DELETE FROM uploads WHERE filename = ?");
			$fdel->bind_param("s",$delValue);
			$delValue = $to_del;
			$fdel->execute();
			$delRes = $fdel->get_result();
			if ($delRes) {
						# code...
				header("Location: content.php");
			}
		}

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
				<form action="" method="post" accept-charset="utf-8">';

				$to_del = $row['filename'];
				echo '<span class="badge badge-danger p-1 m-2">
				<input type="submit"  name="delete" value="Delete" style="text-decoration: none; background-color: none; border: none;"></span>
				</form>
				</div>

				</td>
				</tr>';
			} }


			echo	'</tbody>
			</table>

			<form action="" method="post" accept-charset="utf-8"  class="p-2">												<div class = "col-lg-12">
			<input type="submit" name="close" value="Close" class="btn btn-outline-danger "></div>
			</form> ';


	//Deleting File
			if (isset($_POST['delete'])) {
		# code...
				$fdel = $con->prepare("DELETE FROM uploads WHERE filename = ?");
				$fdel->bind_param("s",$delValue);
				$delValue = $to_del;
				$fdel->execute();
				$delRes = $fdel->get_result();
				if ($delRes) {
						# code...
					header("Location: content.php");
				}
			}

			if (isset($_POST['close'])) {
				header("Location: content.php");
			}

			echo	'</div>
			</div>';

		}
		?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
		</script>
		<script src="select2.min.js"></script>
		<script>
			$("#file_search").select2( {
				placeholder: "Search File",
				allowClear: true
			} );
		</script>
	</body>
	</html>
