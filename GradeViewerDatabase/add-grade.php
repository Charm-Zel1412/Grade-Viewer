<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnupdate'])) {
    // updating account
    $sql = "UPDATE tblgrades SET grade = ?, gradedby = ? WHERE studentnumber = ? AND subject_code = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssss", $_POST['grade'], $_SESSION['username'], $_GET['studentnumber'], $_GET['subject_code']);
        if (mysqli_stmt_execute($stmt)) {   
            $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                $date = date("m/d/Y");
                $time = date("h:i:sa");
                $module = "Grades";
                $action = "Update";
                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['studentnumber'], $action, $_SESSION['username']);
                if (mysqli_stmt_execute($stmt)) {

                    $_SESSION['updated'] = "Student Grade UPDATED!";
                    
                    header("location: subjects-grade.php");

                    exit();
                }

                else{
                $_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
                header("location: subjects-grade.php");
            exit();
                }
            }
        }
        else{
            $_SESSION['error'] = "<font color = 'red'>Error on grading student. </font>";
            header("location: subjects-grade.php");
        exit();
        }
    }

}

else{ // loading the current values of the account

    if (isset($_GET['studentnumber']) && !empty(trim($_GET['studentnumber']))) {
        $sql = "SELECT tblstudents.*, tblgrades.subject_code, tblgrades.grade, tblsubjects.subject_description 
                FROM tblstudents 
                LEFT JOIN tblgrades 
                ON tblgrades.studentnumber = tblstudents.studentnumber
                LEFT JOIN tblsubjects 
                    ON tblsubjects.subject_code = tblgrades.subject_code 
                WHERE tblstudents.studentnumber = ? AND tblgrades.grade = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $_GET['studentnumber'], $_GET['grade']);
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
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Update Grade - Arellano Subject Advising System</title>
</head>
<style>
    form{
        font-family: times-new-roman;
    }
</style>

<body>

    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method = "POST">

        <center>
        
        <p>Change the value on this form and submit to update the Grade</p> <br>
        

        <input type="hidden" name="txtcode" value="<?php echo trim($_GET['subject_code']); ?>">
        <input type="hidden" name="txtstudentnum" value="<?php echo trim($_GET['studentnumber']); ?>">

Student #:<b> <?php echo $account['studentnumber'];?> </b><br>

Name: <b><?php echo $account['lastname'] . ", " . $account['firstname'] . " " . $account['middlename'];  ?></b><br>

Course:<b> <?php echo $account['course'];  ?></b><br>

Year level: <b><?php echo $account['yearlevel'];  ?></b><br><br>

Select Subject :<b> <?php echo $account['subject_code'];  ?></b><br>

Description :<b> <?php echo $account['subject_description'];  ?></b><br><br>

Current Grade :<b> <?php echo $account['grade'];  ?></b><br> 

Select Grade: <select name="grade" id="grade" required>

        <option value="">--Select Grade--</option>

      <option value="INC"<?php echo ($account['grade'] == 'INC') ? 'selected' : ''; ?>>Incomplete</option>>
      <option value="3.00"<?php echo ($account['grade'] == '3.00') ? 'selected' : ''; ?>>3.00</option>>
      <option value="2.75"<?php echo ($account['grade'] == '2.75') ? 'selected' : ''; ?>>2.75</option>>
      <option value="2.50"<?php echo ($account['grade'] == '2.50') ? 'selected' : ''; ?>>2.50</option>>
      <option value="2.25"<?php echo ($account['grade'] == '2.25') ? 'selected' : ''; ?>>2.25</option>>
      <option value="2.00"<?php echo ($account['grade'] == '2.00') ? 'selected' : ''; ?>>2.00</option>>
      <option value="1.75"<?php echo ($account['grade'] == '1.75') ? 'selected' : ''; ?>>1.75</option>>
      <option value="1.50"<?php echo ($account['grade'] == '1.50') ? 'selected' : ''; ?>>1.50</option>>
      <option value="1.25"<?php echo ($account['grade'] == '1.25') ? 'selected' : ''; ?>>1.25</option>>
      <option value="1.00"<?php echo ($account['grade'] == '1.00') ? 'selected' : ''; ?>>1.00</option>>
      


    </select><br>

</center>
        <br>

        <input type="submit" name="btnupdate" class = 'btn btn-success' value="Grade">
        <a href="subjects-grade.php" class="btn btn-default" style="float: right;">Cancel</a>


    </form>

    <script src="password-script.js"></script>
    <script src="description-script.js"></script>

</body>
</html>