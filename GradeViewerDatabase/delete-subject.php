<?php  
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
	$sql = "DELETE FROM tblsubjects WHERE subject_code = ?";
	if ($stmt = mysqli_prepare($link, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", trim($_POST['txtcode']));
		if (mysqli_stmt_execute($stmt)) {
			$sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
			if ($stmt = mysqli_prepare($link, $sql)) {
				$date = date("m/d/Y");
				$time = date("h:i:sa");
				$module = "Subjects";
				$action = "Delete";
				mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtcode'], $action, $_SESSION['username']);
				if (mysqli_stmt_execute($stmt)) {

					$_SESSION['deleted'] = "Subject DELETED!";
					header("location: subjects-management.php");
					exit();
				}

				else{
					$_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
					header("location: subjects-management.php");
					exit();
				}
			}
		}
		else{
			$_SESSION['error'] = "<font color = 'red'> Error on delete subject. </font>";
			header("location: subjects-management.php");
		    exit();
		}
	}
}

?>



<!DOCTYPE html>
<html>
<head>
	<title>Delete Subject - Arellano Subject Advising System</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body>

	<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

		<input type="hidden" name="txtcode" value="<?php echo trim($_GET['subject_code']); ?>">
		
		<center>

		<p>Are you sure you want to delete this subject? </p> <br>
		<input type="submit" name="btnsubmit" class="btn btn-Danger" value="Yes">
		<a href="subjects-management.php" class="btn btn-Default" >No</a>
		
	    </center>

	</form>
	
</body>
</html>