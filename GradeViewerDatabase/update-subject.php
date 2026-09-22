<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
	// updating account
	$sql = "UPDATE tblsubjects SET subject_description = ?, unit = ? WHERE subject_code = ?";
	if ($stmt = mysqli_prepare($link, $sql)) {
		mysqli_stmt_bind_param($stmt, "sss", $_POST['txtdescription'], $_POST['unit'], $_GET['subject_code']);
		if (mysqli_stmt_execute($stmt)) {   
            $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
	            $date = date("m/d/Y");
	            $time = date("h:i:sa");
	            $module = "Subjects";
	            $action = "Update";
	            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['subject_code'], $action, $_SESSION['username']);
	            if (mysqli_stmt_execute($stmt)) {

	    	        $_SESSION['updated'] = "Subject UPDATED!";
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
        	$_SESSION['error'] = "<font color = 'red'>Error on updating subject. </font>";
        	header("location: subjects-management.php");
		exit();
        }
	}

}

else{ // loading the current values of the account

	if (isset($_GET['subject_code']) && !empty(trim($_GET['subject_code']))) {
		$sql = "SELECT * FROM tblsubjects WHERE subject_code = ?";
		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $_GET['subject_code']);
			if (mysqli_stmt_execute($stmt)) {
				$result = mysqli_stmt_get_result($stmt);
				$subject = mysqli_fetch_array($result, MYSQLI_ASSOC);
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

	<title>Update Subject - Arellano Subject Advising System</title>
<meta charset="utf-8">
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
        header("location: subjects-management.php");
        exit();
    }
    ?>

    <div class="topnav" id="myTopnav">

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="create-subject.php"  style = "background-color: white; color: black;">Update Subject</a>
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
	    
	    <p>Change the value on this form and submit to update the subject code</p> <br>

	    Code: <?php echo $subject['subject_code'];  ?> <br> <br>

        		
		Description: <input type="text" id="subject_description" name="txtdescription" value="<?php echo $subject['subject_description'];  ?>" required>  <br> <br>

                Unit: <select name="unit" id="unit" required>
                <option value="1" <?php echo ($subject['unit'] == '1') ? 'selected' : ''; ?>>1</option>
                <option value="2" <?php echo ($subject['unit'] == '2') ? 'selected' : ''; ?>>2</option>
                <option value="3" <?php echo ($subject['unit'] == '3') ? 'selected' : ''; ?>>3</option>
                <option value="4" <?php echo ($subject['unit'] == '4') ? 'selected' : ''; ?>>4</option>
                <option value="5" <?php echo ($subject['unit'] == '5') ? 'selected' : ''; ?>>5</option>

                </select>
                <br><br>
</center>


        <br>
        <input type="submit" name="btnsubmit" class = 'btn btn-success' value="Update">
        <a href="subjects-management.php" class = 'btn btn-default' style="float: right;">Cancel</a>


        </form>

	<script src="password-script.js"></script>

</body>
</html>