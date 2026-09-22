<?php  
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
    $sql = "DELETE acc, pro 
                FROM tblprofessors pro 
                JOIN tblaccounts acc ON acc.username = pro.ID_number
                WHERE pro.ID_number = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", trim($_POST['txtidnum']));
        if (mysqli_stmt_execute($stmt)) {
            $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                $date = date("m/d/Y");
                $time = date("h:i:sa");
                $module = "Professors";
                $action = "Delete";
                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtidnum'], $action, $_SESSION['username']);
                if (mysqli_stmt_execute($stmt)) {

                    $_SESSION['deleted'] = "PROFESSOR DELETED!";
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
        else{
            $_SESSION['error'] = "<font color = 'red'> Error on delete Professor. </font>";
            header("location: professors-management.php");
            exit();
        }
    }
}

?>



<!DOCTYPE html>
<html>
<head>
    <title>Delete Professor - Arellano Subject Advising System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body>


    <div>
        
        <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

        <input type="hidden" name="txtidnum" value="<?php echo trim($_GET['ID_number']); ?>">
        
        <center>

        <p>Are you sure you want to delete this professor? </p> <br>
        <input type="submit" name="btnsubmit" class="btn btn-Danger" value="Yes">
        <a href="professors-management.php" class="btn btn-Default" >No</a>
        
        </center>

    </form>



    </div>
    
</body>
</html>