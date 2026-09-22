<?php  
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
    $studentNumber = trim($_POST['txtstudentnum']);

    // Check if there are records in tblgrades for the student
    $sqlCheckGrades = "SELECT * FROM tblgrades WHERE studentnumber = ?";
    if ($stmtCheckGrades = mysqli_prepare($link, $sqlCheckGrades)) {
        mysqli_stmt_bind_param($stmtCheckGrades, "s", $studentNumber);
        mysqli_stmt_execute($stmtCheckGrades);
        mysqli_stmt_store_result($stmtCheckGrades);
        $numRowsGrades = mysqli_stmt_num_rows($stmtCheckGrades);
        mysqli_stmt_close($stmtCheckGrades);
    } else {
        $_SESSION['error'] = "Error checking grades: " . mysqli_error($link);
        header("location: students-management.php");
        exit();
    }

    // Delete records
    if ($numRowsGrades == 0) {
        // No records in grades, delete from tblstudents and tblaccounts only
        $sql = "DELETE acc, stu 
                FROM tblstudents stu 
                JOIN tblaccounts acc ON acc.username = stu.studentnumber
                WHERE stu.studentnumber = ?";
    } else {
        // Records found in grades, delete from tblstudents, tblaccounts, and tblgrades
        $sql = "DELETE acc, stu, gra 
                FROM tblstudents stu 
                JOIN tblaccounts acc ON acc.username = stu.studentnumber
                JOIN tblgrades gra ON gra.studentnumber = stu.studentnumber
                WHERE stu.studentnumber = ?";
    }

    // Execute delete query
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $studentNumber);
        if (mysqli_stmt_execute($stmt)) {
            // Insert log
            $date = date("m/d/Y");
            $time = date("h:i:sa");
            $module = "Students";
            $action = "Delete";
            $performedBy = $_SESSION['username'];
            $sqlLog = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmtLog = mysqli_prepare($link, $sqlLog)) {
                mysqli_stmt_bind_param($stmtLog, "ssssss", $date, $time, $module, $studentNumber, $action, $performedBy);
                if (mysqli_stmt_execute($stmtLog)) {
                    $_SESSION['deleted'] = "Student account DELETED!";
                } else {
                    $_SESSION['error'] = "Error on inserting log: " . mysqli_error($link);
                }
                mysqli_stmt_close($stmtLog);
            } else {
                $_SESSION['error'] = "Error on preparing log statement: " . mysqli_error($link);
            }
        } else {
            $_SESSION['error'] = "Error on delete account: " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Error on preparing delete statement: " . mysqli_error($link);
    }

    // Redirect to appropriate page
    header("location: students-management.php");
    exit();
}
?>



<!DOCTYPE html>
<html>
<head>
	<title>Delete Student - Arellano Subject Advising System</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body>

	<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

		<input type="hidden" name="txtstudentnum" value="<?php echo trim($_GET['studentnumber']); ?>">
		
		<center>

		<p>Are you sure you want to delete this student account? </p> <br>
		<input type="submit" name="btnsubmit" class="btn btn-Danger" value="Yes">
		<a href="students-management.php" class="btn btn-Default" >No</a>
		
	    </center>

	</form>
	
</body>
</html>