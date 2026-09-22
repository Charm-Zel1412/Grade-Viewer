<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
	// updating account
	$sql = "UPDATE tblprofessors SET Plastname = ?, Pfirstname = ?, Psecondname = ?,  Pmiddlename = ?, faculty = ? WHERE ID_number = ?";
	if ($stmt = mysqli_prepare($link, $sql)) {
		mysqli_stmt_bind_param($stmt, "ssssss", $_POST['txtlastname'], $_POST['txtfirstname'],  $_POST['txtsecondname'], $_POST['txtmiddlename'], $_POST['txtfaculty'], $_GET['ID_number']);
		if (mysqli_stmt_execute($stmt)) {   
            $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
	            $date = date("m/d/Y");
	            $time = date("h:i:sa");
	            $module = "Professors";
	            $action = "Update";
	            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['ID_number'], $action, $_SESSION['username']);
	            if (mysqli_stmt_execute($stmt)) {

	    	        $_SESSION['updated'] = "Professor account UPDATED!";
		            header("location: professors-management.php");
		            exit();
	            }

	            else{
	            $_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
	            header("location: professors-management.php");
		    exit();
	            }
			}
        }
        else{
        	$_SESSION['error'] = "<font color = 'red'>Error on updating Professor. </font>";
        	header("location: professors-management.php");
		exit();
        }
	}

}

else{ // loading the current values of the account

	if (isset($_GET['ID_number']) && !empty(trim($_GET['ID_number']))) {
		$sql = "SELECT * FROM tblprofessors WHERE ID_number = ?";
		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $_GET['ID_number']);
			if (mysqli_stmt_execute($stmt)) {
				$result = mysqli_stmt_get_result($stmt);
				$account = mysqli_fetch_array($result, MYSQLI_ASSOC);
			}
			else{
				echo "<font color = 'red'> Error on loading th current account values</font>";
			}
		}
	}
}

?>

<!DOCTYPE html>
<html>
	<title>Update Professor Account - Arellano Subject Advising System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="design.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>



<style>
	form{
		font-family: times-new-roman;
	}
</style>

<body>

	<?php

    //check if there is a session (note: Administrators can only access this management)
    if($_SESSION['usertype'] == "ADMINISTRATOR"){
        
    }
  
    else if($_SESSION['usertype'] == "DEAN"){
    }

    else{
        //redirect to the login page (now index page)
        $_SESSION['error'] = "User does not have the right to access this management/module!";
        header("location: professors-management.php");
        exit();
    }
    ?>


    <div class="topnav" id="myTopnav">

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="update-prof.php"  style = "background-color: white; color: black;">Update Professor</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "PROFESSOR") : ?>
       <a href="subjects-grade.php">Subject Grades</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php">Student Grades</a>
    <?php endif; ?>
</div>


	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method = "POST">

	<center>	
	    
	    <p>Change the value on this form and submit to update the professor account</p> <br>

	    ID # <?php echo $account['ID_number'];  ?> <br> <br>

		
		First Name: <input type="text" id="firstname" name="txtfirstname" value="<?php echo $account['Pfirstname'];  ?>" required>  <br> <br>

		Second Name: <input type="text" id="secondname" name="txtsecondname" value="<?php echo $account['Psecondname'];  ?>">  <br> <br>

		Last Name: <input type="text" id="lastname" name="txtlastname" value="<?php echo $account['Plastname'];  ?>" required>  <br> <br>

		Middle Name: <input type="text" id="middlename" name="txtmiddlename" value="<?php echo $account['Pmiddlename'];  ?>">  <br> <br>

		Faculty: <input type="text" id="faculty" name="txtfaculty" value="<?php echo $account['faculty'];?>"><br><br>

</center>
        <br>
        <input type="submit" name="btnsubmit" class = 'btn btn-success' value="Update">
        <a href="professors-management.php" class = 'btn btn-default' style="float: right;">Cancel</a>

	</form>

	<script src="password-script.js"></script>

</body>
</html>