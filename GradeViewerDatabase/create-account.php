<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
$sql = "SELECT * FROM tblaccounts WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {

        mysqli_stmt_bind_param($stmt, "s", $_POST['txtusername']);
        if (mysqli_execute($stmt)) {
            
            $result = mysqli_stmt_get_result($stmt);
        
            if (mysqli_num_rows($result) == 0) { 
                //create account
                $sql = "INSERT INTO tblaccounts (username, password, firstname, middlename, lastname, secondname, usertype, status, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    $status = "ACTIVE";
                    $date = date("d/m/Y");

                    mysqli_stmt_bind_param($stmt, "ssssssssss", $_POST['txtusername'], $_POST['txtpassword'], $_POST['txtfirstname'], $_POST['txtmiddlename'], $_POST['txtlastname'], $_POST['txtsecondname'], $_POST['cmbtype'], $status, $_SESSION['username'], $date);

                    if (mysqli_stmt_execute($stmt)) {
                        
                        $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
			            if ($stmt = mysqli_prepare($link, $sql)) {
				            $date = date("m/d/Y");
				            $time = date("h:i:sa");
				            $module = "Accounts";
				            $action = "Create";
				            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtusername'], $action, $_SESSION['username']);
				            if (mysqli_stmt_execute($stmt)) {
				    	        $_SESSION['created'] = "User account CREATED!";
					            header("location: accounts-management.php");
					            exit();
				            }

				            else{
					            $_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
                                header("location: accounts-management.php");
                                exit();
				            }
			            }
                    }
                    else{
                        $_SESSION['error'] = "<font color = 'red'> Error on inserting account.</font>";
                        header("location: accounts-management.php");
                        exit();
                        }
                }
            }
            else{
                $_SESSION['error'] = "<font color ='red'>Username already in use.</font>";
                header("location: accounts-management.php");
                exit();;

                }
        }

        else{
            $_SESSION['error'] = "<font color = 'red'>Error on checking username.</font>";
            header("location: accounts-management.php");
            exit();
            }
    }
}
?>

<!DOCTYPE html>
<html>

<title>Create new account - Arellano Subject Advising System</title>


<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="design.css">
  <link rel="stylesheet" href="dropdown.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


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
       <a href="create-account.php"  style = "background-color: white; color: black;">Create Account</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "PROFESSOR") : ?>
       <a href="subjects-grade.php">Subject Grades</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php">Student Grades</a>
    <?php endif; ?>
</div>




<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

    <p style="text-align: center;">Fill up this form and submit to create a new account.</p>

    <br>

    <center>
        
        Username: <input type="text" name="txtusername" required><br><br>

First name: <input type="text" name="txtfirstname" required><br><br>
Second name: <input type="text" name="txtsecondname"><br><br>
Middle name: <input type="text" name="txtmiddlename" required><br><br>
Last name: <input type="text" name="txtlastname" required><br><br>

Password: <input type="password" id = "password" name="txtpassword" required>
<span class="password-toggle-icon"><i class="fas fa-eye"></i></span><br><br>
Account Type: <select name="cmbtype" id="cmbtype" required>

<option value="">--Select Account type--</option>
<option value="ADMINISTRATOR">Administrator</option>
<option value="DEAN">Dean</option>
<option value="PROFESSOR">Professor</option>

</select><br>

 <br><br>
 </center>

<script src="password-script.js"></script>

<input type="submit" name="btnsubmit" class="btn btn-success" value="Submit">
<a href="accounts-management.php" class="btn btn-default" style='float: right;'>Cancel</a>
  </form>




</body>
</html>




