<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnupdate'])) {
    // updating account
    $sql = "UPDATE tblgrades SET instructor = ? WHERE studentnumber = ? AND subject_code = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $_POST['txtinstructor'], $_GET['studentnumber'], $_GET['subject_code']);
        if (mysqli_stmt_execute($stmt)) {   
            $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                $date = date("m/d/Y");
                $time = date("h:i:sa");
                $module = "Subject List";
                $action = "Update";
                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['studentnumber'], $action, $_SESSION['username']);
                if (mysqli_stmt_execute($stmt)) {

                    $_SESSION['updated'] = "Subject list UPDATED!";
                    
                    echo "<script>location = 'list-subjects.php?studentnumber=" . $_GET['studentnumber'] . "';</script>";

                    exit();
                }

                else{
                $_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
                echo "<script>location = 'list-subjects.php?studentnumber=" . $_GET['studentnumber'] . "';</script>";
            exit();
                }
            }
        }
        else{
            $_SESSION['error'] = "<font color = 'red'>Error on updating list. </font>";
            echo "<script>location = 'list-subjects.php?studentnumber=" . $_GET['studentnumber'] . "';</script>";
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



$sql = "SELECT 
            Plastname, 
            Pfirstname, 
            subject_1, 
            subject_2, 
            subject_3, 
            subject_4, 
            subject_5, 
            subject_6, 
            subject_7, 
            subject_8 
        FROM tblprofessors";

$professors = [];
if ($stmt = mysqli_prepare($link, $sql)) {
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            // Loop through each subject field in the row
            foreach (['subject_1', 'subject_2', 'subject_3', 'subject_4', 'subject_5', 'subject_6', 'subject_7', 'subject_8'] as $subject_field) {
                $subject_code = $row[$subject_field];
                if (!empty($subject_code)) {
                    // Add the professor to the respective subject's list
                    if (!isset($professors[$subject_code])) {
                        $professors[$subject_code] = [];
                    }
                    $professors[$subject_code][] = $row['Plastname'] . ", " . $row['Pfirstname'];
                }
            }
        }
    } else {
        echo "Error fetching professors.";
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

        </center>
        
        <br>

        <input type="hidden" name="txtcode" value="<?php echo trim($_GET['subject_code']); ?>">
        <input type="hidden" name="txtstudentnum" value="<?php echo trim($_GET['studentnumber']); ?>">


Subject : <?php echo $account['subject_code'];  ?><br>

Description : <?php echo $account['subject_description'];  ?><br><br>



Select Professor : <select name="txtinstructor" id="instructor" class="form-control" >

<option value="">Select Professor</option>

<?php   foreach ($professors as $professor):

        $instructor = $account['subject_code'];

        if ($instructor == $professor['subject_1']) 
        {
            ?>
        <option value="<?php echo $professor['Plastname']. ", " . $professor['Pfirstname']; ?>">
            <?php echo $professor['Plastname'] . " - " . $professor['Pfirstname']; ?>
        </option>

        <?php
        }

        ?>

    <?php endforeach; ?>

        </select><br>



        <br>
<center>
        <input type="submit" name="btnupdate" class = 'btn btn-success' value="Update">
        <?php

        echo "<a href = 'list-subjects.php?studentnumber=" . $account['studentnumber']. "' class = 'btn btn-default'>Cancel</a>"; 
        ?>


</center>
    </form>

    <script src="password-script.js"></script>
    <script src="description-script.js"></script>


    <script>
    // Professors mapping (generated by PHP)
    const professorsMapping = <?php echo json_encode($professors); ?>;

    document.getElementById('subject_code').addEventListener('change', function () {
        const subjectCode = this.value; // Get selected subject code
        const professorDropdown = document.getElementById('instructor');
        
        // Clear the professor dropdown
        professorDropdown.innerHTML = '<option value="">Select Professor</option>';

        if (professorsMapping[subjectCode]) {
            // Populate professor options for the selected subject code
            professorsMapping[subjectCode].forEach(professor => {
                const option = document.createElement('option');
                option.value = professor;
                option.textContent = professor;
                professorDropdown.appendChild(option);
            });
        }
    });
</script>

</body>
</html>