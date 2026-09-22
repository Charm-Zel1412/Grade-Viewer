<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
$sql = "SELECT * FROM tblprofessors WHERE ID_number = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {

        mysqli_stmt_bind_param($stmt, "s", $_POST['txtidnum']);
        if (mysqli_execute($stmt)) {
            
            $result = mysqli_stmt_get_result($stmt);
        
            if (mysqli_num_rows($result) == 0) { 
                //create account
                $sql = "INSERT INTO tblprofessors (ID_number, Plastname, Pfirstname, Psecondname, Pmiddlename, faculty, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    $date = date("d/m/Y");

                    mysqli_stmt_bind_param($stmt, "ssssssss", $_POST['txtidnum'], $_POST['txtlastname'], $_POST['txtfirstname'],  $_POST['txtsecondname'], $_POST['txtmiddlename'], $_POST['txtfaculty'], $_SESSION['username'], $date);

                    if (mysqli_stmt_execute($stmt)) {
                        
                        $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
			            if ($stmt = mysqli_prepare($link, $sql)) {
				            $date = date("m/d/Y");
				            $time = date("h:i:sa");
				            $module = "Professors";
				            $action = "Create";
				            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtidnum'], $action, $_SESSION['username']);
				            if (mysqli_stmt_execute($stmt)) {

				    	        $sql = "INSERT INTO tblaccounts (username, password, lastname, firstname, secondname, middlename, usertype, status, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $password = $_POST['txtidnum'] . $_POST['txtlastname'];
                            $usertype = "PROFESSOR";
                            $status = "ACTIVE";
                            $date = date("d/m/Y");
                            
                            mysqli_stmt_bind_param($stmt, "ssssssssss", $_POST['txtidnum'], $password, $_POST['txtlastname'], $_POST['txtfirstname'],  $_POST['txtsecondname'], $_POST['txtmiddlename'], $usertype, $status, $_SESSION['username'], $date);
                            if (mysqli_stmt_execute($stmt)) {

                                $_SESSION['created'] = "Professor account CREATED!";
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
			            }

                    }
                    else{
                        $_SESSION['error'] = "<font color = 'red'> Error on inserting professor.</font>";
                        header("location: professors-management.php");
                        exit();
                        }
                }
            }
            else{
                $_SESSION['error'] = "<font color ='red'>ID # already in use.</font>";
                header("location: professors-management.php");
                exit();
                }
        }

        else{
            $_SESSION['error'] = "<font color = 'red'>Error on checking ID #.</font>";
            header("location: professors-management.php");
            exit();
            }
    }
}
?>

<!DOCTYPE html>
<html>
<title>Create New Professor - Arellano Subject Advising System</title><meta charset="utf-8">
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
        header("location: professors-management.php");
        exit();
    }
    ?>


    <div class="topnav" id="myTopnav">

    
    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="create-prof.php"  style = "background-color: white; color: black;">Create Professor</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "PROFESSOR") : ?>
       <a href="subjects-grade.php">Subject Grades</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php">Student Grades</a>
    <?php endif; ?>
</div>


    
<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

    <center>
        <p>Fill up this form and submit to create a new professor account.</p>

    <br>
ID #: <input type="text" name="txtidnum" required><br><br>
First Name: <input type="text" name="txtfirstname" required><br><br>
Second Name: <input type="text" name="txtsecondname"><br><br>
Middle Name: <input type="text" name="txtmiddlename"><br><br>
Last Name: <input type="text" name="txtlastname" required><br><br>
Faculty: <input type="text" name="txtfaculty" required><br><br>
</center>

<input type="submit" name="btnsubmit" class="btn btn-success" value="Submit">
<a href="professors-management.php" class="btn btn-default" style="float: right;">Cancel</a> 

</form>



</body>
</html>
