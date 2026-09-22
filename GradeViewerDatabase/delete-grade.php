<?php  
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btndelete'])) {
	// Prepare the SQL delete statement
	$sql = "UPDATE tblgrades SET grade = ?, gradedby = ? WHERE studentnumber = ? AND subject_code = ? AND grade = ?";
	if ($stmt = mysqli_prepare($link, $sql)) {
		// Bind parameters
		$delgrade = "";
		mysqli_stmt_bind_param($stmt, "sssss", $delgrade, $_SESSION['username'], $_POST['txtstudentnum'], $_POST['txtcode'], $_POST['txtgrade']);
		
		// Execute delete query
		if (mysqli_stmt_execute($stmt)) {
			// Log the deletion action
			$sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
			if ($stmt = mysqli_prepare($link, $sql)) {
				$date = date("m/d/Y");
				$time = date("h:i:sa");
				$module = "Grades";
				$action = "Delete";
				
				// Bind log parameters and execute
				mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtstudentnum'], $action, $_SESSION['username']);
				if (mysqli_stmt_execute($stmt)) {
					// Success message and redirection
					$_SESSION['deleted'] = "Student grade DELETED!";
					echo "<script>location = 'manage-grade.php?studentnumber=" . $_POST['txtstudentnum'] . "';</script>";
					exit();
				} else {
					$_SESSION['error'] = "<font color='red'>Error on insert log.</font>";
					echo "<script>location = 'manage-grade.php?studentnumber=" . $_POST['txtstudentnum'] . "';</script>";
					exit();
				}
			}
		} else {
			$_SESSION['error'] = "<font color='red'>Error on deleting grade.</font>";
			echo "<script>location = 'manage-grade.php?studentnumber=" . $_POST['txtstudentnum'] . "';</script>";
			exit();
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Delete Grade - Arellano Subject Advising System</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
		<input type="hidden" name="txtcode" value="<?php echo htmlspecialchars($_GET['subject_code']); ?>">
		<input type="hidden" name="txtstudentnum" value="<?php echo htmlspecialchars($_GET['studentnumber']); ?>">
		<input type="hidden" name="txtgrade" value="<?php echo htmlspecialchars($_GET['grade']); ?>">
		
		<center>
			<p>Are you sure you want to delete this Grade?</p><br>
			<input type="submit" name="btndelete" class="btn btn-danger" value="Yes">
			<?php echo "<a href='manage-grade.php?studentnumber=" . htmlspecialchars($_GET['studentnumber']) . "' class='btn btn-default'>No</a>"; ?>
	    </center>
	</form>
</body>
</html>


