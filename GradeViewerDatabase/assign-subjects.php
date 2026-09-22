<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
	// updating account
	$sql = "UPDATE tblprofessors SET subject_1 = ?, subject_2 = ?, subject_3 = ?, subject_4 = ?, subject_5 = ?, subject_6 = ?, subject_7 = ?, subject_8 = ? WHERE ID_number = ?";
	if ($stmt = mysqli_prepare($link, $sql)) {
		mysqli_stmt_bind_param($stmt, "sssssssss", $_POST['txtsub1'],  $_POST['txtsub2'],  $_POST['txtsub3'], $_POST['txtsub4'],  $_POST['txtsub5'],  $_POST['txtsub6'], $_POST['txtsub7'],  $_POST['txtsub8'], $_GET['ID_number']);
		if (mysqli_stmt_execute($stmt)) {   
            $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
	            $date = date("m/d/Y");
	            $time = date("h:i:sa");
	            $module = "Professors";
	            $action = "Assign Subject";
	            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['ID_number'], $action, $_SESSION['username']);
	            if (mysqli_stmt_execute($stmt)) {

	    	        $_SESSION['updated'] = "Subject Assigned!";
		            echo "<script>location = 'assigned-subjects.php?ID_number=" . $_GET['ID_number'] . "';</script>";
		            exit();
	            }

	            else{
	            $_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
	            echo "<script>location = 'assigned-subjects.php?ID_number=" . $_GET['ID_number'] . "';</script>";
		    exit();
	            }
			}
        }
        else{
        	$_SESSION['error'] = "<font color = 'red'>Error on assigning subject. </font>";
        	echo "<script>location = 'assigned-subjects.php?ID_number=" . $_GET['ID_number'] . "';</script>";
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


$sql = "SELECT subject_code, subject_description FROM tblsubjects ORDER BY subject_code";
$subjects = [];
if ($stmt = mysqli_prepare($link, $sql)) {
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $subjects[] = $row; // Store each subject's data
        }
    } else {
        echo "Error fetching subjects.";
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

    <div class="topnav" id="myTopnav">

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="create-subject.php"  style = "background-color: white; color: black;">Assign Subject</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "PROFESSOR") : ?>
       <a href="subjects-grade.php">Subject Grades</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php">Student Grades</a>
    <?php endif; ?>
</div>

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




	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method = "POST" >

		
	    
	    <center><p>Change the value on this form and submit to update the professor account</p> <br>

	    ID # <b><?php echo $account['ID_number'];  ?></b> <br>

	    Name: <b><?php echo $account['Plastname'] . ", " . $account['Pfirstname'] . " " . $account['Psecondname'] ." " . $account['Pmiddlename'];  ?></b><br>

	    Faculty: <b><?php echo $account['faculty'];  ?></b><br><br>



 <label for="subject">Assign Subject 1:</label>

    <select name="txtsub1" id="subject_1" class="form-control" required>

      <option value="N/A" <?php echo empty($account['subject_1']) ? 'selected' : ''; ?>>Select Subject</option>
    <?php foreach ($subjects as $subject): ?>
        <option value="<?php echo $subject['subject_code']; ?>" <?php echo ($account['subject_1'] == $subject['subject_code']) ? 'selected' : ''; ?>>
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>
    <?php endforeach; ?>

    </select> <br>


 <label for="subject">Assign Subject 2:</label>

    <select name="txtsub2" id="subject_2" class="form-control" >

         <option value="N/A" <?php echo empty($account['subject_2']) ? 'selected' : ''; ?>>Select Subject</option>
    <?php foreach ($subjects as $subject): ?>
        <option value="<?php echo $subject['subject_code']; ?>" <?php echo ($account['subject_2'] == $subject['subject_code']) ? 'selected' : ''; ?>>
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>
    <?php endforeach; ?>

    </select> <br>



 <label for="subject">Assign Subject 3:</label>

    <select name="txtsub3" id="subject_3" class="form-control" >

        <option value="N/A" <?php echo empty($account['subject_3']) ? 'selected' : ''; ?>>Select Subject</option>
    <?php foreach ($subjects as $subject): ?>
        <option value="<?php echo $subject['subject_code']; ?>" <?php echo ($account['subject_3'] == $subject['subject_code']) ? 'selected' : ''; ?>>
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>
    <?php endforeach; ?>

    </select> <br>


 <label for="subject">Assign Subject 4:</label>

    <select name="txtsub4" id="subject_4" class="form-control" >

        <option value="N/A" <?php echo empty($account['subject_4']) ? 'selected' : ''; ?>>Select Subject</option>
    <?php foreach ($subjects as $subject): ?>
        <option value="<?php echo $subject['subject_code']; ?>" <?php echo ($account['subject_4'] == $subject['subject_code']) ? 'selected' : ''; ?>>
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>
    <?php endforeach; ?>

    </select> <br>


 <label for="subject">Assign Subject 5:</label>

    <select name="txtsub5" id="subject_5" class="form-control" >

        <option value="N/A" <?php echo empty($account['subject_5']) ? 'selected' : ''; ?>>Select Subject</option>
    <?php foreach ($subjects as $subject): ?>
        <option value="<?php echo $subject['subject_code']; ?>" <?php echo ($account['subject_5'] == $subject['subject_code']) ? 'selected' : ''; ?>>
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>
    <?php endforeach; ?>

    </select> <br>


 <label for="subject">Assign Subject 6:</label>

    <select name="txtsub6" id="subject_6" class="form-control" >

        <option value="N/A" <?php echo empty($account['subject_6']) ? 'selected' : ''; ?>>Select Subject</option>
    <?php foreach ($subjects as $subject): ?>
        <option value="<?php echo $subject['subject_code']; ?>" <?php echo ($account['subject_6'] == $subject['subject_code']) ? 'selected' : ''; ?>>
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>
    <?php endforeach; ?>

    </select> <br>


 <label for="subject">Assign Subject 7:</label>

    <select name="txtsub7" id="subject_7" class="form-control" >

        <option value="N/A" <?php echo empty($account['subject_7']) ? 'selected' : ''; ?>>Select Subject</option>
    <?php foreach ($subjects as $subject): ?>
        <option value="<?php echo $subject['subject_code']; ?>" <?php echo ($account['subject_7'] == $subject['subject_code']) ? 'selected' : ''; ?>>
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>
    <?php endforeach; ?>

    </select> <br>



 <label for="subject">Assign Subject 8:</label>

    <select name="txtsub8" id="subject_8" class="form-control" >

        <option value="N/A" <?php echo empty($account['subject_8']) ? 'selected' : ''; ?>>Select Subject</option>
    <?php foreach ($subjects as $subject): ?>
        <option value="<?php echo $subject['subject_code']; ?>" <?php echo ($account['subject_8'] == $subject['subject_code']) ? 'selected' : ''; ?>>
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>
    <?php endforeach; ?>

    </select> <br>

</center>


        <br>
        <input type="submit" name="btnsubmit" class = 'btn btn-success' value="Assign" >

<?php

echo "<a href = 'assigned-subjects.php?ID_number=" . $account['ID_number']. "' class = 'btn btn-default' style='float: right;'>Cancel</a>";
?>
	</form>

	<script src="password-script.js"></script>

</body>
</html>