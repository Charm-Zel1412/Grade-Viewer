<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btncreate'])) {
$sql = "UPDATE tblprofessors SET subject_1 = ?, subject_2 = ?, subject_3 = ?, subject_4 = ?, subject_5 = ?, subject_6 = ?, subject_7 = ?, subject_8 = ? WHERE ID_number = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {

        mysqli_stmt_bind_param($stmt, "sssssssss", $_POST['subject_1'], $_POST['subject_2'], $_POST['subject_3'], $_POST['subject_4'], $_POST['subject_5'], $_POST['subject_6'], $_POST['subject_7'], $_POST['subject_8'], $_GET['ID_number']);
        if (mysqli_execute($stmt)) {
            
            if (mysqli_stmt_execute($stmt)) {
                        
                        $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $date = date("m/d/Y");
                            $time = date("h:i:sa");
                            $module = "Assign Subject";
                            $action = "Create";
                            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['ID_number'], $action, $_SESSION['username']);
                            if (mysqli_stmt_execute($stmt)) {

                                $_SESSION['created'] = "Subject Added!";
                                echo "<script>location = 'assigned-subjects.php?ID_number=" . $_GET['ID_number'] . "';</script>";
                            }

                            else{
                                $_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
                                echo "<script>location = 'assigned-subjects.php?ID_number=" . $_GET['ID_number'] . "';</script>";
                                exit();
                            }
                        }
                    }
                    else{
                        $_SESSION['error'] = "<font color = 'red'> Error on adding grade.</font>";
                        echo "<script>location = 'assigned-subjects.php?ID_number=" . $_GET['ID_number'] . "';</script>";
                        exit();
                        }

        
            
        }

        else{
            $_SESSION['error'] = "<font color = 'red'>Error on checking subject.</font>";
            echo "<script>location = 'assigned-subjects.php?ID_number=" . $_GET['ID_number'] . "';</script>";
            exit();
            }
    }
}

else{ // loading the current values of the account

    if (isset($_GET['ID_number']) && !empty(trim($_GET['ID_number']))) {
        $sql = "SELECT tblprofessors.* FROM tblprofessors INNER JOIN tblsubjects WHERE ID_number = ?";
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
<head>
<title>Create New Student - Arellano Subject Advising System</title>
</head>

<body>

<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

    <p style="text-align: center;">Fill up this form and submit to create a grade.</p>

    <br>

ID # <?php echo $account['ID_number'];  ?> <br>

Name: <?php echo $account['Plastname'] . ", " . $account['Pfirstname'] . " " . $account['Psecondname'] ." " . $account['Pmiddlename'];  ?><br>

Faculty: <?php echo $account['faculty'];  ?><br><br>



 <label for="subject">Assign Subject 1:</label>

    <select name="subject_1" id="subject_1" class="form-control" required>

        <option value="N/A">Select Subject</option>

        <?php foreach ($subjects as $subject): ?>

            <option value="<?php echo $subject['subject_code']; ?>">
                <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
            </option>

        <?php endforeach; ?>

    </select> <br>


 <label for="subject">Assign Subject 2:</label>

    <select name="subject_2" id="subject_2" class="form-control" >

        <option value="N/A">Select Subject</option>

        <?php foreach ($subjects as $subject): ?>

            <option value="<?php echo $subject['subject_code']; ?>">
                <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
            </option>

        <?php endforeach; ?>

    </select> <br>



 <label for="subject">Assign Subject 3:</label>

    <select name="subject_3" id="subject_3" class="form-control" >

        <option value="N/A">Select Subject</option>

        <?php foreach ($subjects as $subject): ?>

            <option value="<?php echo $subject['subject_code']; ?>">
                <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
            </option>

        <?php endforeach; ?>

    </select> <br>


 <label for="subject">Assign Subject 4:</label>

    <select name="subject_4" id="subject_4" class="form-control" >

        <option value="N/A">Select Subject</option>

        <?php foreach ($subjects as $subject): ?>

            <option value="<?php echo $subject['subject_code']; ?>">
                <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
            </option>

        <?php endforeach; ?>

    </select> <br>


 <label for="subject">Assign Subject 5:</label>

    <select name="subject_5" id="subject_5" class="form-control" >

        <option value="N/A">Select Subject</option>

        <?php foreach ($subjects as $subject): ?>

            <option value="<?php echo $subject['subject_code']; ?>">
                <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
            </option>

        <?php endforeach; ?>

    </select> <br>


 <label for="subject">Assign Subject 6:</label>

    <select name="subject_6" id="subject_6" class="form-control" >

        <option value="N/A">Select Subject</option>

        <?php foreach ($subjects as $subject): ?>

            <option value="<?php echo $subject['subject_code']; ?>">
                <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
            </option>

        <?php endforeach; ?>

    </select> <br>


 <label for="subject">Assign Subject 7:</label>

    <select name="subject_7" id="subject_7" class="form-control" >

        <option value="N/A">Select Subject</option>

        <?php foreach ($subjects as $subject): ?>

            <option value="<?php echo $subject['subject_code']; ?>">
                <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
            </option>

        <?php endforeach; ?>

    </select> <br>



 <label for="subject">Assign Subject 8:</label>

    <select name="subject_8" id="subject_8" class="form-control" >

        <option value="N/A">Select Subject</option>

        <?php foreach ($subjects as $subject): ?>

            <option value="<?php echo $subject['subject_code']; ?>">
                <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
            </option>

        <?php endforeach; ?>

    </select> <br>



<div class="result"></div> <br> 


<input type="submit" name="btncreate" class="btn btn-success" value="Submit">
<?php

echo "<a href = 'assigned-subjects.php?ID_number=" . $account['ID_number']. "' class = 'btn btn-default' style='float: right;'>Cancel</a>";
?>

</form>

<script src="description.js"></script>

</body>
</html>
