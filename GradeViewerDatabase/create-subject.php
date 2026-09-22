<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
$sql = "SELECT * FROM tblsubjects WHERE subject_code = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {

        mysqli_stmt_bind_param($stmt, "s", $_POST['txtcode']);
        if (mysqli_execute($stmt)) {
            
            $result = mysqli_stmt_get_result($stmt);
        
            if (mysqli_num_rows($result) == 0) { 
                //create account
                $sql = "INSERT INTO tblsubjects (subject_code, subject_description, unit, createdby, datecreated) VALUES (?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    
                    $date = date("d/m/Y");

                    mysqli_stmt_bind_param($stmt, "sssss", $_POST['txtcode'], $_POST['txtdescription'], $_POST['unit'], $_SESSION['username'], $date);

                    if (mysqli_stmt_execute($stmt)) {
                        
                        $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
			            if ($stmt = mysqli_prepare($link, $sql)) {
				            $date = date("m/d/Y");
				            $time = date("h:i:sa");
				            $module = "Subjects";
				            $action = "Create";
				            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtcode'], $action, $_SESSION['username']);
				            if (mysqli_stmt_execute($stmt)) {

				    	        $_SESSION['created'] = "Subject CREATED!";
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
                        $_SESSION['error'] = "<font color = 'red'> Error on inserting account.</font>";
                        header("location: subjects-management.php");
                        exit();
                        }
                }
            }
            else{
                $_SESSION['error'] = "<font color ='red'>Subject code already in use.</font>";
                header("location: subjects-management.php");
                exit();
                }
        }

        else{
            $_SESSION['error'] = "<font color = 'red'>Error on checking username.</font>";
            header("location: subjects-management.php");
            exit();
            }
    }
}
?>

<!DOCTYPE html>
<html>
<title>Create Subject - Arellano Subject Advising System</title>

<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="design.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


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
       <a href="create-subject.php"  style = "background-color: white; color: black;">Create Subject</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "PROFESSOR") : ?>
       <a href="subjects-grade.php">Subject Grades</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php">Student Grades</a>
    <?php endif; ?>
</div>

<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST" >

    <center>   <p style="text-align: center;">Fill up this form and submit to create a new subject code.</p>

    <br>
Code: <input type="text" name="txtcode" required><br><br>
Description: <input type="text" name="txtdescription" required><br><br>

Unit: <select name="unit" id="unit" required>

<option value="">--Select Unit--</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>

</select><br><br>


</center>


<input type="submit" name="btnsubmit" class="btn btn-success" value="Submit">
<a href="subjects-management.php" class="btn btn-default" style="float: right;">Cancel</a>

</form>

</body>
</html>
