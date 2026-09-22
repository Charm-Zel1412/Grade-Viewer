<?php  
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btndelete'])) {
	$sql = "DELETE FROM tblgrades WHERE studentnumber = ? AND subject_code = ? AND grade = ?";
	if ($stmt = mysqli_prepare($link, $sql)) {
		mysqli_stmt_bind_param($stmt, "sss", trim($_POST['txtstudentnum']), trim($_POST['txtcode']), trim($_POST['txtgrade']));
		if (mysqli_stmt_execute($stmt)) {
			$sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
			if ($stmt = mysqli_prepare($link, $sql)) {
				$date = date("m/d/Y");
				$time = date("h:i:sa");
				$module = "Subject List";
				$action = "Delete";
				mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtstudentnum'], $action, $_SESSION['username']);
				if (mysqli_stmt_execute($stmt)) {

					$_SESSION['deleted'] = "Subject REMOVED!";
					echo "<script>location = 'list-subjects.php?studentnumber=" . $_POST['txtstudentnum'] . "';</script>";
					exit();
				}

				else{
					$_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
					echo "<script>location = 'list-subjects.php?studentnumber=" . $_POST['txtstudentnum'] . "';</script>";
					exit();
				}
			}
		}
		else{
			$_SESSION['error'] = "<font color = 'red'> Error on delete grade. </font>";
			echo "<script>location = 'list-subjects.php?studentnumber=" . $_POST['txtstudentnum'] . "';</script>";
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

	<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

		<input type="hidden" name="txtcode" value="<?php echo trim($_GET['subject_code']); ?>">
		<input type="hidden" name="txtstudentnum" value="<?php echo trim($_GET['studentnumber']); ?>">
		<input type="hidden" name="txtgrade" value="<?php echo trim($_GET['grade']); ?>">
		
		<center>

		<p>Are you sure you want to delete this Subject? </p> <br>
		<input type="submit" name="btndelete" class="btn btn-Danger" value="Yes">
		<?php
echo "<a href = 'list-subjects.php?studentnumber=" . $_GET['studentnumber'] . "' class = 'btn btn-default'>No</a>";
?>
		
	    </center>

	</form>
	
</body>
</html>