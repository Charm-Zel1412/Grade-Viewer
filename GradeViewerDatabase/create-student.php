<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
$sql = "SELECT * FROM tblstudents WHERE studentnumber = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {

        mysqli_stmt_bind_param($stmt, "s", $_POST['txtstudentnum']);
        if (mysqli_execute($stmt)) {
            
            $result = mysqli_stmt_get_result($stmt);
        
            if (mysqli_num_rows($result) == 0) { 
                //create account
                $sql = "INSERT INTO tblstudents (studentnumber, lastname, firstname, secondname, middlename, course, yearlevel, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    $date = date("d/m/Y");

                    mysqli_stmt_bind_param($stmt, "sssssssss", $_POST['txtstudentnum'], $_POST['txtlastname'], $_POST['txtfirstname'],  $_POST['txtsecondname'], $_POST['txtmiddlename'], $_POST['course'], $_POST['yearlevel'], $_SESSION['username'], $date);

                    if (mysqli_stmt_execute($stmt)) {
                        
                        $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
			            if ($stmt = mysqli_prepare($link, $sql)) {
				            $date = date("m/d/Y");
				            $time = date("h:i:sa");
				            $module = "Students";
				            $action = "Create";
				            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtstudentnum'], $action, $_SESSION['username']);
				            if (mysqli_stmt_execute($stmt)) {

				    	        $sql = "INSERT INTO tblaccounts (username, password, lastname, firstname, secondname, middlename, usertype, status, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $password = $_POST['txtstudentnum'] . $_POST['txtlastname'];
                            $usertype = "STUDENT";
                            $status = "ACTIVE";
                            $date = date("d/m/Y");
                            
                            mysqli_stmt_bind_param($stmt, "ssssssssss", $_POST['txtstudentnum'], $password, $_POST['txtlastname'], $_POST['txtfirstname'],  $_POST['txtsecondname'], $_POST['txtmiddlename'], $usertype, $status, $_SESSION['username'], $date);
                            if (mysqli_stmt_execute($stmt)) {

                                $_SESSION['created'] = "Student account CREATED!";
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
			            }

                    }
                    else{
                        $_SESSION['error'] = "<font color = 'red'> Error on inserting student.</font>";
                        header("location: students-management.php");
                        exit();
                        }
                }
            }
            else{
                $_SESSION['error'] = "<font color ='red'>Student ID already in use.</font>";
                header("location: students-management.php");
                exit();
                }
        }

        else{
            $_SESSION['error'] = "<font color = 'red'>Error on checking username.</font>";
            header("location: students-management.php");
            exit();
            }
    }
}
?>

<!DOCTYPE html>
<html>
<title>Create New Student - Arellano Subject Advising System</title>

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
        header("location: main.php");
        exit();
    }
    ?>


    <div class="topnav" id="myTopnav">

    
    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="create-student.php"  style = "background-color: white; color: black;">Create Student</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "PROFESSOR") : ?>
       <a href="subjects-grade.php">Subject Grades</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php">Student Grades</a>
    <?php endif; ?>
</div>




<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST" >

 <center>  <p style="text-align: center;">Fill up this form and submit to create a new student account.</p>  

    <br>
Student #: <input type="text" name="txtstudentnum" required><br><br>
First Name: <input type="text" name="txtfirstname" required><br><br>
Second Name: <input type="text" name="txtsecondname"><br><br>

Middle Name: <input type="text" name="txtmiddlename"><br><br>
Last Name: <input type="text" name="txtlastname" required><br><br>

Course: <select name="course" id="course" required>

<option value="">--Select Course--</option>
<option value="Bachelor of Arts in English, Political Science, Psychology & History">Bachelor of Arts in English, Political Science, Psychology & History</option>
<option value="Bachelor of Performing Arts">Bachelor of Performing Arts</option>
<option value="Bachelor of Science in Criminology">Bachelor of Science in Criminology</option>
<option value="Bachelor of Science in Accountancy">Bachelor of Science in Accountancy</option>
<option value="Bachelor of Science in Computer Science">Bachelor of Science in Computer Science</option>
<option value="Bachelor of Science in Business Administration">Bachelor of Science in Business Administration</option>
<option value="Bachelor of Elementary Education">Bachelor of Elementary Education</option>
<option value="Bachelor of Secondary Education">Bachelor of Secondary Education</option>
<option value="Bachelor of Physical Education - Sports & Wellness Management">Bachelor of Physical Education - Sports & Wellness Management</option>
<option value="Bachelor of Physical Education">Bachelor of Physical Education</option>
<option value="Bachelor of Library and Information Science">Bachelor of Library and Information Science</option>
<option value="Teacher Certificate Program">Teacher Certificate Program</option>
<option value="Bachelor of Science in Nursing">Bachelor of Science in Nursing</option>
<option value="Bachelor of Science in Physical Therapy">Bachelor of Science in Physical Therapy</option>
<option value="Bachelor of Science in Radiologic Technology">Bachelor of Science in Radiologic Technology</option>
<option value="Bachelor of Science Medical Technology/ Medical Laboratory Science">Bachelor of Science Medical Technology/ Medical Laboratory Science</option>
<option value="Bachelor of Science in Pharmacy">Bachelor of Science in Pharmacy</option>
<option value="Bachelor of Science in Psychology">Bachelor of Science in Psychology</option>
<option value="Bachelor of Science in Midwifery; Diploma in Midwifery">Bachelor of Science in Midwifery; Diploma in Midwifery</option>
<option value="Bachelor of Science in Hospitality Management">Bachelor of Science in Hospitality Management</option>
<option value="Bachelor of Science in Tourism Management">Bachelor of Science in Tourism Management</option>

</select><br><br>

Year level: <select name="yearlevel" id="yearlevel" required>

<option value="">--Select Year Level--</option>
<option value="1ST">1st Year</option>
<option value="2ND">2nd Year</option>
<option value="3RD">3rd Year</option>
<option value="4TH">4th Year</option>

</select><br><br>
</center>  

<input type="submit" name="btnsubmit" class="btn btn-success" value="Submit">
<a href="students-management.php" class="btn btn-default" style="float: right;">Cancel</a>

</form>

</body>
</html>
