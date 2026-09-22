<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
	// updating account
	$sql = "UPDATE tblstudents SET firstname = ?, secondname = ?, lastname = ?, middlename = ?, course = ?, yearlevel = ? WHERE studentnumber = ?";
	if ($stmt = mysqli_prepare($link, $sql)) {
		mysqli_stmt_bind_param($stmt, "sssssss", $_POST['txtfirstname'], $_POST['txtsecondname'], $_POST['txtlastname'], $_POST['txtmiddlename'], $_POST['course'], $_POST['yearlevel'], $_GET['studentnumber']);
		if (mysqli_stmt_execute($stmt)) {   
            $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
	            $date = date("m/d/Y");
	            $time = date("h:i:sa");
	            $module = "Students";
	            $action = "Update";
	            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['studentnumber'], $action, $_SESSION['username']);
	            if (mysqli_stmt_execute($stmt)) {

	    	        $_SESSION['updated'] = "Student account UPDATED!";
		            header("location: students-management.php");
		            exit();
	            }

	            else{
	            $_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
	            header("location: students-management.php");
		    exit();
	            }
			}
        }
        else{
        	$_SESSION['error'] = "<font color = 'red'>Error on updating student. </font>";
        	header("location: students-management.php");
		exit();
        }
	}

}

else{ // loading the current values of the account

	if (isset($_GET['studentnumber']) && !empty(trim($_GET['studentnumber']))) {
		$sql = "SELECT * FROM tblstudents WHERE studentnumber = ?";
		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $_GET['studentnumber']);
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

	<title>Update Student Account - Arellano Subject Advising System</title>
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
        header("location: main.php");
        exit();
    }
    ?>


    <div class="topnav" id="myTopnav">

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="update-student.php"  style = "background-color: white; color: black;">Update Student</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "PROFESSOR") : ?>
       <a href="subjects-grade.php">Subject Grades</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php">Student Grades</a>
    <?php endif; ?>
</div>



	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method = "POST" >

		<center>
	    
	    <p>Change the value on this form and submit to update the student account</p> <br>

	    Student # <?php echo $account['studentnumber'];  ?> <br> <br>

      
		First Name: <input type="text" id="firstname" name="txtfirstname" value="<?php echo $account['firstname'];  ?>" required>  <br> <br>

		Second Name: <input type="text" id="secondname" name="txtsecondname" value="<?php echo $account['secondname'];  ?>">  <br> <br>

		Last Name: <input type="text" id="lastname" name="txtlastname" value="<?php echo $account['lastname'];  ?>" required>  <br> <br>

		Middle Name: <input type="text" id="middlename" name="txtmiddlename" value="<?php echo $account['middlename'];  ?>">  <br> <br>

	Course : <select name="course" id="course"required>

        <option value="N/A" <?php echo ($account['course'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>

        <option value="Bachelor of Arts in English, Political Science, Psychology & History" <?php echo ($account['course'] == 'Bachelor of Arts in English, Political Science, Psychology & History') ? 'selected' : ''; ?>>Bachelor of Arts in English, Political Science, Psychology & History</option>

        <option value="Bachelor of Performing Arts" <?php echo ($account['course'] == 'Bachelor of Performing Arts') ? 'selected' : ''; ?>>Bachelor of Performing Arts</option>

        <option value="Bachelor of Science in Criminology" <?php echo ($account['course'] == 'Bachelor of Science in Criminology') ? 'selected' : ''; ?>>Bachelor of Science in Criminology</option>

        <option value="Bachelor of Science in Accountancy" <?php echo ($account['course'] == 'Bachelor of Science in Accountancy') ? 'selected' : ''; ?>>Bachelor of Science in Accountancy</option>

        <option value="Bachelor of Science in Computer Science"<?php echo ($account['course'] == 'Bachelor of Science in Computer Science') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>

        <option value="Bachelor of Science in Business Administration"<?php echo ($account['course'] == 'Bachelor of Science in Business Administration') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>

        <option value="Bachelor of Elementary Education"<?php echo ($account['course'] == 'Bachelor of Elementary Education') ? 'selected' : ''; ?>>Bachelor of Elementary Education</option>

        <option value="Bachelor of Secondary Education"<?php echo ($account['course'] == 'Bachelor of Secondary Education') ? 'selected' : ''; ?>>Bachelor of Secondary Education</option>

        <option value="Bachelor of Physical Education - Sports & Wellness Management" <?php echo ($account['course'] == 'Bachelor of Physical Education - Sports & Wellness Management') ? 'selected' : ''; ?>>Bachelor of Physical Education - Sports & Wellness Management</option>

        <option value="Bachelor of Physical Education" <?php echo ($account['course'] == 'Bachelor of Physical Education') ? 'selected' : ''; ?>>Bachelor of Physical Education</option>

        <option value="Bachelor of Library and Information Science" <?php echo ($account['course'] == 'Bachelor of Library and Information Science') ? 'selected' : ''; ?>>Bachelor of Library and Information Science</option>

        <option value="Teacher Certificate Program" <?php echo ($account['course'] == 'Teacher Certificate Program') ? 'selected' : ''; ?>>Teacher Certificate Program</option>

        <option value="Bachelor of Science in Nursing" <?php echo ($account['course'] == 'Bachelor of Science in Nursing') ? 'selected' : ''; ?>>Bachelor of Science in Nursing</option>

        <option value="Bachelor of Science in Physical Therapy"<?php echo ($account['course'] == 'Bachelor of Science in Physical Therapy') ? 'selected' : ''; ?>>Bachelor of Science in Physical Therapy</option>

        <option value="Bachelor of Science in Radiologic Technology"<?php echo ($account['course'] == 'Bachelor of Science in Radiologic Technology') ? 'selected' : ''; ?>>Bachelor of Science in Radiologic Technology</option>

        <option value="Bachelor of Science Medical Technology/ Medical Laboratory Science" <?php echo ($account['course'] == 'Bachelor of Science Medical Technology/ Medical Laboratory Science') ? 'selected' : ''; ?>>Bachelor of Science Medical Technology/ Medical Laboratory Science</option>

        <option value="Bachelor of Science in Pharmacy" <?php echo ($account['course'] == 'Bachelor of Science in Pharmacy') ? 'selected' : ''; ?>>Bachelor of Science in Pharmacy</option>

        <option value="Bachelor of Science in Psychology" <?php echo ($account['course'] == 'Bachelor of Science in Psychology') ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>

        <option value="Bachelor of Science in Midwifery; Diploma in Midwifery" <?php echo ($account['course'] == 'Bachelor of Science in Midwifery; Diploma in Midwifery') ? 'selected' : ''; ?>>Bachelor of Science in Midwifery; Diploma in Midwifery</option>

        <option value="Bachelor of Science in Hospitality Management" <?php echo ($account['course'] == 'Bachelor of Science in Hospitality Management') ? 'selected' : ''; ?>>Bachelor of Science in Hospitality Management</option>

        <option value="Bachelor of Science in Tourism Management"<?php echo ($account['course'] == 'Bachelor of Science in Tourism Management') ? 'selected' : ''; ?>>Bachelor of Science in Tourism Management</option>

        </select><br><br>


        Year level: <select name="yearlevel" id="yearlevel" required>

        <option value="">--Select Year Level--</option>
        <option value="1ST"<?php echo ($account['yearlevel'] == '1ST') ? 'selected' : ''; ?>>1st Year</option>
        <option value="2ND"<?php echo ($account['yearlevel'] == '2ND') ? 'selected' : ''; ?>>2nd Year</option>
        <option value="3RD"<?php echo ($account['yearlevel'] == '3RD') ? 'selected' : ''; ?>>3rd Year</option>
        <option value="4TH"<?php echo ($account['yearlevel'] == '4TH') ? 'selected' : ''; ?>>4th Year</option>

        </select><br><br>

</center>
        <br>

        <input type="submit" name="btnsubmit" class = 'btn btn-success' value="Update">
        <a href="students-management.php" class = 'btn btn-default'style='float: right;'>Cancel</a>

	</form>

	<script src="password-script.js"></script>

</body>
</html>