<?php  
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
    $username = trim($_POST['txtusername']);

    // Check if there are records in tblgrades for the user
    $sqlCheckGrades = "SELECT * FROM tblgrades WHERE studentnumber = ?";
    if ($stmtCheckGrades = mysqli_prepare($link, $sqlCheckGrades)) {
        mysqli_stmt_bind_param($stmtCheckGrades, "s", $username);
        mysqli_stmt_execute($stmtCheckGrades);
        mysqli_stmt_store_result($stmtCheckGrades);
        $numRowsGrades = mysqli_stmt_num_rows($stmtCheckGrades);
        mysqli_stmt_close($stmtCheckGrades);
    } else {
        $_SESSION['error'] = "Error checking grades: " . mysqli_error($link);
        header("location: accounts-management.php");
        exit();
    }

     $sqlCheckStudents = "SELECT * FROM tblstudents WHERE studentnumber = ?";
    if ($stmtCheckStudents = mysqli_prepare($link, $sqlCheckStudents)) {
        mysqli_stmt_bind_param($stmtCheckStudents, "s", $username);
        mysqli_stmt_execute($stmtCheckStudents);
        mysqli_stmt_store_result($stmtCheckStudents);
        $numRowsStudents = mysqli_stmt_num_rows($stmtCheckStudents);
        mysqli_stmt_close($stmtCheckStudents);
    } else {
        $_SESSION['error'] = "Error checking students: " . mysqli_error($link);
        header("location: accounts-management.php");
        exit();
    }

    $sqlCheckProfs = "SELECT * FROM tblprofessors WHERE ID_number = ?";
    if ($stmtCheckProfs = mysqli_prepare($link, $sqlCheckProfs)) {
        mysqli_stmt_bind_param($stmtCheckProfs, "s", $username);
        mysqli_stmt_execute($stmtCheckProfs);
        mysqli_stmt_store_result($stmtCheckProfs);
        $numRowsProfs = mysqli_stmt_num_rows($stmtCheckProfs);
        mysqli_stmt_close($stmtCheckProfs);
    } else {
        $_SESSION['error'] = "Error checking professors: " . mysqli_error($link);
        header("location: accounts-management.php");
        exit();
    }


    // Delete records
    // Construct the DELETE query based on the presence of records

    if ($numRowsGrades == 0 && $numRowsProfs == 0 && $numRowsStudents == 0) {
    // No records in tblgrades, tblprofessors, or tblstudents; delete from tblaccounts only
        $sql = "DELETE FROM tblaccounts WHERE username = ?";
    } 

    elseif ($numRowsGrades > 0 && $numRowsProfs == 0 && $numRowsStudents == 0) {
    // Records only in tblgrades, delete from tblaccounts, tblgrades, and tblstudents
        $sql = "DELETE acc, stu, gra 
            FROM tblaccounts acc 
            JOIN tblstudents stu ON acc.username = stu.studentnumber
            JOIN tblgrades gra ON acc.username = gra.studentnumber
            WHERE acc.username = ?";
        } 

    elseif ($numRowsGrades == 0 && $numRowsProfs > 0 && $numRowsStudents == 0) {
    // Records only in tblprofessors, delete from tblaccounts and tblprofessors
            $sql = "DELETE acc, pro 
            FROM tblaccounts acc 
            JOIN tblprofessors pro ON acc.username = pro.ID_number
            WHERE acc.username = ?";
        } 

    elseif ($numRowsGrades == 0 && $numRowsProfs == 0 && $numRowsStudents > 0) {
    // Records only in tblstudents, delete from tblaccounts and tblstudents
        $sql = "DELETE acc, stu 
            FROM tblaccounts acc 
            JOIN tblstudents stu ON acc.username = stu.studentnumber
            WHERE acc.username = ?";
        } 

    else {
    // Records in multiple tables, delete from tblaccounts, tblstudents, tblgrades, and tblprofessors as needed
    $sql = "DELETE acc, stu, gra, pro 
            FROM tblaccounts acc 
            LEFT JOIN tblstudents stu ON acc.username = stu.studentnumber
            LEFT JOIN tblgrades gra ON acc.username = gra.studentnumber
            LEFT JOIN tblprofessors pro ON acc.username = pro.ID_number
            WHERE acc.username = ?";
        }

    // Execute delete query
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        if (mysqli_stmt_execute($stmt)) {
            // Insert log
            $date = date("m/d/Y");
            $time = date("h:i:sa");
            $module = "Accounts";
            $action = "Delete";
            $performedBy = $_SESSION['username'];
            $sqlLog = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmtLog = mysqli_prepare($link, $sqlLog)) {
                mysqli_stmt_bind_param($stmtLog, "ssssss", $date, $time, $module, $username, $action, $performedBy);
                if (mysqli_stmt_execute($stmtLog)) {
                    $_SESSION['deleted'] = "User account DELETED!";
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
    header("location: accounts-management.php");
    exit();
}
?>



<!DOCTYPE html>
<html>
<head>
	<title>Delete account - Arellano Subject Advising System</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body>

	<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

		<input type="hidden" name="txtusername" value="<?php echo trim($_GET['username']); ?>" >
		
		<center>

		<p>Are you sure you want to delete this account? </p> <br>
		<input type="submit" name="btnsubmit" class="btn btn-Danger" value="Yes">
		<a href="accounts-management.php" class="btn btn-Default" >No</a>
		
	    </center>

	</form>
	
</body>
</html>