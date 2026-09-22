<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
	// updating account
	$sql = "UPDATE tblaccounts SET password = ?, firstname = ?, secondname = ?, middlename = ?, lastname = ?, usertype = ?, status = ? WHERE username = ?";
	if ($stmt = mysqli_prepare($link, $sql)) {
		mysqli_stmt_bind_param($stmt, "ssssssss", $_POST['txtpassword'], $_POST['txtfirstname'], $_POST['txtsecondname'], $_POST['txtmiddlename'], $_POST['txtlastname'], $_POST['cmbtype'], $_POST['rbstatus'], $_GET['username']);
		if (mysqli_stmt_execute($stmt)) {   
            $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
	            $date = date("m/d/Y");
	            $time = date("h:i:sa");
	            $module = "Accounts";
	            $action = "Update";
	            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['username'], $action, $_SESSION['username']);
	            if (mysqli_stmt_execute($stmt)) {

	    	        $_SESSION['updated'] = "User account UPDATED!";
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
        	$_SESSION['error'] = "<font color = 'red'>Error on updating account. </font>";
        	header("location: accounts-management.php");
			exit();
        }
	}

}

else{ // loading the current values of the account

	if (isset($_GET['username']) && !empty(trim($_GET['username']))) {
		$sql = "SELECT * FROM tblaccounts WHERE username = ?";
		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $_GET['username']);
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
<title>Update account - Arellano Subject Advising System</title>

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
       <a href="update-account.php"  style = "background-color: white; color: black;">Update Account</a>
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
	    
	    <p>Change the value on this form and submit to update the account</p>
        </center>

<center>		
		Username: <?php echo $account['username'];  ?> <br> <br>

		First name: <input type="text" name="txtfirstname" value="<?php echo $account['firstname'];  ?>"  required><br><br>
        Second name: <input type="text" name="txtsecondname" value="<?php echo $account['secondname'];  ?>" ><br><br>
        Middle name: <input type="text" name="txtmiddlename" value="<?php echo $account['middlename'];  ?>"  required><br><br>
        Last name: <input type="text" name="txtlastname" value="<?php echo $account['lastname'];  ?>"  required><br><br>

		Password: <input type="password" id="password" name="txtpassword" value="<?php echo $account['password'];  ?>" required> 
		<span class="password-toggle-icon"><i class="fas fa-eye"></i></span>		
		<br><br>

		
		Current User Type: <?php echo $account['usertype'];  ?> <br><br>

		<input type="hidden" name="cmbtype" value="<?php echo $account['usertype'];  ?>" >

        Current Status: <br>
        <?php  
        $status = $account['status'];
        if ($status == "ACTIVE") {
        	?> <input type="radio" name="rbstatus" value="ACTIVE" checked> Active <br>
        	<input type="radio" name="rbstatus" value="INACTIVE"> Inactive <br> <?php
        }

        else{
            ?> <input type="radio" name="rbstatus" value="ACTIVE"> Active <br>
        	<input type="radio" name="rbstatus" value="INACTIVE" checked> Inactive <br> <?php
        }

        ?>

        <br>
</center>
        <input type="submit" name="btnsubmit" class = 'btn btn-success' value="Update">
        <a href="accounts-management.php" class = 'btn btn-default' style='float: right;'>Cancel</a>

	</form>

	<script src="password-script.js"></script>

</body>
</html>