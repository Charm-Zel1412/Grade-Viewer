<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btncreate'])) {
$sql = "SELECT * FROM tblgrades WHERE subject_code = ? AND studentnumber = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {

        mysqli_stmt_bind_param($stmt, "ss", $_POST['subject_code'], $_POST['txtstudentnum']);
        if (mysqli_execute($stmt)) {
            
            $result = mysqli_stmt_get_result($stmt);
        
            if (mysqli_num_rows($result) == 0) { 
                //create account
                $sql = "INSERT INTO tblgrades (studentnumber, subject_code, instructor, ID_number, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($link, $sql)) {

                    $date = date("d/m/Y");

                     $instructor_name = $_POST['txtinstructor']; // This should be the name of the professor
                     $instructor_id = $_POST['instructor_id']; // This should be the professor's ID number


                    mysqli_stmt_bind_param($stmt, "ssssss", $_POST['txtstudentnum'], $_POST['subject_code'], $instructor_name, $instructor_id, $_SESSION['username'] , $date);

                    if (mysqli_stmt_execute($stmt)) {
                        
                        $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $date = date("m/d/Y");
                            $time = date("h:i:sa");
                            $module = "Subject List";
                            $action = "Create";
                            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtstudentnum'], $action, $_SESSION['username']);
                            if (mysqli_stmt_execute($stmt)) {

                                $_SESSION['created'] = "Subject Added!";
                                echo "<script>location = 'list-subjects.php?studentnumber=" . $_POST['txtstudentnum'] . "';</script>";
                            }

                            else{
                                $_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
                                echo "<script>location = 'list-subjects.php?studentnumber=" . $_POST['txtstudentnum'] . "';</script>";
                                exit();
                            }
                        }
                    }
                    else{
                        $_SESSION['error'] = "<font color = 'red'> Error on adding grade.</font>";
                        echo "<script>location = 'list-subjects.php?studentnumber=" . $_POST['txtstudentnum'] . "';</script>";
                        exit();
                        }
                }
            }
            else{
                $_SESSION['error'] = "<font color ='red'>Student already has this Subject code in use.</font>";
                echo "<script>location = 'list-subjects.php?studentnumber=" . $_POST['txtstudentnum'] . "';</script>";
                exit();
                }
        }

        else{
            $_SESSION['error'] = "<font color = 'red'>Error on checking subject.</font>";
            echo "<script>location = 'list-subjects.php?studentnumber=" . $_POST['txtstudentnum'] . "';</script>";
            exit();
            }
    }
}

else{ // loading the current values of the account

    if (isset($_GET['studentnumber']) && !empty(trim($_GET['studentnumber']))) {
        $sql = "SELECT tblstudents.* FROM tblstudents INNER JOIN tblsubjects INNER JOIN tblprofessors WHERE studentnumber = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $_GET['studentnumber']);
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





$sql = "SELECT subject_code, subject_description, course_1, course_2, course_3, course_4, course_5, course_6, course_7, course_8 FROM tblsubjects ORDER BY subject_code";
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


$sql = "SELECT 
            Plastname, 
            Pfirstname, 
            ID_number,
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
                    // Add the professor and ID_number to the respective subject's list
                    if (!isset($professors[$subject_code])) {
                        $professors[$subject_code] = [];
                    }
                    $professors[$subject_code][] = [
                        'name' => $row['Plastname'] . ", " . $row['Pfirstname'],
                        'id_number' => $row['ID_number']
                    ];
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
<title>Create New Student - Arellano Subject Advising System</title>
</head>

    <div class="topnav" id="myTopnav">

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="create-subject.php"  style = "background-color: white; color: black;">Add Subject</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "PROFESSOR") : ?>
       <a href="subjects-grade.php">Subject Grades</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php">Student Grades</a>
    <?php endif; ?>
</div>

<body>

<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

    <p style="text-align: center;">Fill up this form and submit to add subject.</p>

    <br>

Student #: <?php echo $account['studentnumber'];?> 
<input type="hidden" name="txtstudentnum" value="<?php echo $account['studentnumber'];?>" required> <br>

Name: <?php echo $account['lastname'] . ", " . $account['firstname'] . " " . $account['secondname'] ." " . $account['middlename'];  ?><br>

Course: <?php echo $account['course'];  ?><br>

Year level: <?php echo $account['yearlevel'];  ?><br><br>

Select Subject : <select name="subject_code" id="subject_code" class="form-control"  required>

<option value="">Select Subject</option>


<?php   foreach ($subjects as $subject):

        $course = $account['course'];

        if ($course == $subject['course_1']) 
        {
            ?>
        <option value="<?php echo $subject['subject_code']; ?>">
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>

        <?php
        }

        if ($course == $subject['course_2']) 
        {
            ?>
        <option value="<?php echo $subject['subject_code']; ?>">
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>

        <?php
        }

        if ($course == $subject['course_3']) 
        {
            ?>
        <option value="<?php echo $subject['subject_code']; ?>">
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>

        <?php
        }

        if ($course == $subject['course_4']) 
        {
            ?>
        <option value="<?php echo $subject['subject_code']; ?>">
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>

        <?php
        }


        if ($course == $subject['course_5']) 
        {
            ?>
        <option value="<?php echo $subject['subject_code']; ?>">
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>

        <?php
        }


        if ($course == $subject['course_6']) 
        {
            ?>
        <option value="<?php echo $subject['subject_code']; ?>">
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>

        <?php
        }


        if ($course == $subject['course_7']) 
        {
            ?>
        <option value="<?php echo $subject['subject_code']; ?>">
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>

        <?php
        }


        if ($course == $subject['course_8']) 
        {
            ?>
        <option value="<?php echo $subject['subject_code']; ?>">
            <?php echo $subject['subject_code'] . " - " . $subject['subject_description']; ?>
        </option>

        <?php
        }


        ?>

    <?php endforeach; ?>

        </select><br>







Select Professor : <select name="txtinstructor" id="instructor" class="form-control" disabled>

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



        if ($instructor == $professor['subject_2']) 
        {
            ?>
        <option value="<?php echo $professor['Plastname']. ", " . $professor['Pfirstname']; ?>">
            <?php echo $professor['Plastname'] . " - " . $professor['Pfirstname']; ?>
        </option>

        <?php
        }


        if ($instructor == $professor['subject_3']) 
        {
            ?>
        <option value="<?php echo $professor['Plastname']. ", " . $professor['Pfirstname']; ?>">
            <?php echo $professor['Plastname'] . " - " . $professor['Pfirstname']; ?>
        </option>

        <?php
        }


        if ($instructor == $professor['subject_4']) 
        {
            ?>
        <option value="<?php echo $professor['Plastname']. ", " . $professor['Pfirstname']; ?>">
            <?php echo $professor['Plastname'] . " - " . $professor['Pfirstname']; ?>
        </option>

        <?php
        }


        if ($instructor == $professor['subject_5']) 
        {
            ?>
        <option value="<?php echo $professor['Plastname']. ", " . $professor['Pfirstname']; ?>">
            <?php echo $professor['Plastname'] . " - " . $professor['Pfirstname']; ?>
        </option>

        <?php
        }


        if ($instructor == $professor['subject_6']) 
        {
            ?>
        <option value="<?php echo $professor['Plastname']. ", " . $professor['Pfirstname']; ?>">
            <?php echo $professor['Plastname'] . " - " . $professor['Pfirstname']; ?>
        </option>

        <?php
        }


        if ($instructor == $professor['subject_7']) 
        {
            ?>
        <option value="<?php echo $professor['Plastname']. ", " . $professor['Pfirstname']; ?>">
            <?php echo $professor['Plastname'] . " - " . $professor['Pfirstname']; ?>
        </option>

        <?php
        }


        if ($instructor == $professor['subject_8']) 
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


       <input type="hidden" name="instructor_id" id="instructor_id">
<input type="hidden" name="txtinstructor" id="txtinstructor"> 

<script>
    professorDropdown.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('instructor_id').value = selectedOption.value;
    });
</script>


<div class="result"></div> <br> 


<input type="submit" name="btncreate" class="btn btn-success" value="Submit">
<?php

echo "<a href = 'list-subjects.php?studentnumber=" . $account['studentnumber']. "' class = 'btn btn-default' style='float: right;'>Cancel</a>";
?>

</form>

<script src="description.js"></script>




<script>
    // Professors mapping (generated by PHP)
const professorsMapping = <?php echo json_encode($professors); ?>;

// Get dropdown elements
const subjectDropdown = document.getElementById('subject_code');
const professorDropdown = document.getElementById('instructor');
const instructorIdField = document.getElementById('instructor_id'); // The hidden field to store the ID
const instructorNameField = document.getElementById('txtinstructor'); // Hidden field for professor's name

// Add event listener for subject dropdown
subjectDropdown.addEventListener('change', function () {
    const subjectCode = this.value; // Get selected subject code

    // Clear the professor dropdown
    professorDropdown.innerHTML = '<option value="">Select Professor</option>';

    if (subjectCode) {
        // Populate professor options for the selected subject code
        if (professorsMapping[subjectCode]) {
            professorsMapping[subjectCode].forEach(professor => {
                const option = document.createElement('option');
                option.value = professor.id_number; // Use the professor's ID_number as the value
                option.textContent = `${professor.name}`; // Display the professor's name
                professorDropdown.appendChild(option);
            });
        }
        // Enable the professor dropdown
        professorDropdown.disabled = false;
    } else {
        // Disable the professor dropdown if no subject is selected
        professorDropdown.disabled = true;
    }
});

// When the professor is selected, populate the hidden instructor_id and txtinstructor field
professorDropdown.addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    instructorIdField.value = selectedOption.value; // Set the ID_number in the hidden field
    instructorNameField.value = selectedOption.textContent; // Set the professor's name in the hidden field
});
</script>



</body>
</html>
