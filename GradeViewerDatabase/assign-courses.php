<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
    // updating account
    $sql = "UPDATE tblsubjects SET course_1 = ? , course_2 = ? , course_3 = ?, course_4 = ?, course_5 = ? , course_6 = ? , course_7 = ?, course_8 = ? WHERE subject_code = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssssss",$_POST['course_1'], $_POST['course_2'], $_POST['course_3'], $_POST['course_4'], $_POST['course_5'], $_POST['course_6'], $_POST['course_7'], $_POST['course_8'] , $_GET['subject_code']);
        if (mysqli_stmt_execute($stmt)) {   
            $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                $date = date("m/d/Y");
                $time = date("h:i:sa");
                $module = "Subjects";
                $action = "Update";
                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['subject_code'], $action, $_SESSION['username']);
                if (mysqli_stmt_execute($stmt)) {

                    $_SESSION['updated'] = "Subject UPDATED!";
                echo "<script>location = 'subject-courses.php?subject_code=" . $_GET['subject_code'] . "';</script>";
                    exit();
                }

                else{
                $_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
                echo "<script>location = 'subject-courses.php?subject_code=" . $_GET['subject_code'] . "';</script>";
            exit();
                }
            }
        }
        else{
            $_SESSION['error'] = "<font color = 'red'>Error on updating subject. </font>";
                echo "<script>location = 'subject-courses.php?subject_code=" . $_GET['subject_code'] . "';</script>";
        exit();
        }
    }

}

else{ // loading the current values of the account

    if (isset($_GET['subject_code']) && !empty(trim($_GET['subject_code']))) {
        $sql = "SELECT * FROM tblsubjects WHERE subject_code = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $_GET['subject_code']);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $subject = mysqli_fetch_array($result, MYSQLI_ASSOC);
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

    <title>Update Subject - Arellano Subject Advising System</title>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="design.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<div class="topnav" id="myTopnav">

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="create-subject.php"  style = "background-color: white; color: black;">Assign Course</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "PROFESSOR") : ?>
       <a href="subjects-grade.php">Subject Grades</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php">Student Grades</a>
    <?php endif; ?>
</div>

<style>
    form{
        font-family: times-new-roman;
    }
</style>

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
        header("location: subjects-management.php");
        exit();
    }
    ?>



    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method = "POST">

        <center>
        
        <p>Change the value on this form and submit to update Courses</p> <br>

        Code: <b><?php echo $subject['subject_code'];  ?> </b> <br> <br>

        Description: <b> <?php echo $subject['subject_description'];  ?> </b> <br> <br>

        Unit: <b><?php echo $subject['unit'];  ?> </b> <br> <br><br>

        
        
      


        1st Course : <select name="course_1" id="course_1">

        <option value="N/A" <?php echo ($subject['course_1'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>

        <option value="Bachelor of Arts in English, Political Science, Psychology & History" <?php echo ($subject['course_1'] == 'Bachelor of Arts in English, Political Science, Psychology & History') ? 'selected' : ''; ?>>Bachelor of Arts in English, Political Science, Psychology & History</option>

        <option value="Bachelor of Performing Arts" <?php echo ($subject['course_1'] == 'Bachelor of Performing Arts') ? 'selected' : ''; ?>>Bachelor of Performing Arts</option>

        <option value="Bachelor of Science in Criminology" <?php echo ($subject['course_1'] == 'Bachelor of Science in Criminology') ? 'selected' : ''; ?>>Bachelor of Science in Criminology</option>

        <option value="Bachelor of Science in Accountancy" <?php echo ($subject['course_1'] == 'Bachelor of Science in Accountancy') ? 'selected' : ''; ?>>Bachelor of Science in Accountancy</option>

        <option value="Bachelor of Science in Computer Science"<?php echo ($subject['course_1'] == 'Bachelor of Science in Computer Science') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>

        <option value="Bachelor of Science in Business Administration"<?php echo ($subject['course_1'] == 'Bachelor of Science in Business Administration') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>

        <option value="Bachelor of Elementary Education"<?php echo ($subject['course_1'] == 'Bachelor of Elementary Education') ? 'selected' : ''; ?>>Bachelor of Elementary Education</option>

        <option value="Bachelor of Secondary Education"<?php echo ($subject['course_1'] == 'Bachelor of Secondary Education') ? 'selected' : ''; ?>>Bachelor of Secondary Education</option>

        <option value="Bachelor of Physical Education - Sports & Wellness Management" <?php echo ($subject['course_1'] == 'Bachelor of Physical Education - Sports & Wellness Management') ? 'selected' : ''; ?>>Bachelor of Physical Education - Sports & Wellness Management</option>

        <option value="Bachelor of Physical Education" <?php echo ($subject['course_1'] == 'Bachelor of Physical Education') ? 'selected' : ''; ?>>Bachelor of Physical Education</option>

        <option value="Bachelor of Library and Information Science" <?php echo ($subject['course_1'] == 'Bachelor of Library and Information Science') ? 'selected' : ''; ?>>Bachelor of Library and Information Science</option>

        <option value="Teacher Certificate Program" <?php echo ($subject['course_1'] == 'Teacher Certificate Program') ? 'selected' : ''; ?>>Teacher Certificate Program</option>

        <option value="Bachelor of Science in Nursing" <?php echo ($subject['course_1'] == 'Bachelor of Science in Nursing') ? 'selected' : ''; ?>>Bachelor of Science in Nursing</option>

        <option value="Bachelor of Science in Physical Therapy"<?php echo ($subject['course_1'] == 'Bachelor of Science in Physical Therapy') ? 'selected' : ''; ?>>Bachelor of Science in Physical Therapy</option>

        <option value="Bachelor of Science in Radiologic Technology"<?php echo ($subject['course_1'] == 'Bachelor of Science in Radiologic Technology') ? 'selected' : ''; ?>>Bachelor of Science in Radiologic Technology</option>

        <option value="Bachelor of Science Medical Technology/ Medical Laboratory Science" <?php echo ($subject['course_1'] == 'Bachelor of Science Medical Technology/ Medical Laboratory Science') ? 'selected' : ''; ?>>Bachelor of Science Medical Technology/ Medical Laboratory Science</option>

        <option value="Bachelor of Science in Pharmacy" <?php echo ($subject['course_1'] == 'Bachelor of Science in Pharmacy') ? 'selected' : ''; ?>>Bachelor of Science in Pharmacy</option>

        <option value="Bachelor of Science in Psychology" <?php echo ($subject['course_1'] == 'Bachelor of Science in Psychology') ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>

        <option value="Bachelor of Science in Midwifery; Diploma in Midwifery" <?php echo ($subject['course_1'] == 'Bachelor of Science in Midwifery; Diploma in Midwifery') ? 'selected' : ''; ?>>Bachelor of Science in Midwifery; Diploma in Midwifery</option>

        <option value="Bachelor of Science in Hospitality Management" <?php echo ($subject['course_1'] == 'Bachelor of Science in Hospitality Management') ? 'selected' : ''; ?>>Bachelor of Science in Hospitality Management</option>

        <option value="Bachelor of Science in Tourism Management"<?php echo ($subject['course_1'] == 'Bachelor of Science in Tourism Management') ? 'selected' : ''; ?>>Bachelor of Science in Tourism Management</option>

        </select><br><br>



        2nd Course : <select name="course_2" id="course_2">

        <option value="N/A" <?php echo ($subject['course_2'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>

        <option value="Bachelor of Arts in English, Political Science, Psychology & History" <?php echo ($subject['course_2'] == 'Bachelor of Arts in English, Political Science, Psychology & History') ? 'selected' : ''; ?>>Bachelor of Arts in English, Political Science, Psychology & History</option>

        <option value="Bachelor of Performing Arts" <?php echo ($subject['course_2'] == 'Bachelor of Performing Arts') ? 'selected' : ''; ?>>Bachelor of Performing Arts</option>

        <option value="Bachelor of Science in Criminology" <?php echo ($subject['course_2'] == 'Bachelor of Science in Criminology') ? 'selected' : ''; ?>>Bachelor of Science in Criminology</option>

        <option value="Bachelor of Science in Accountancy" <?php echo ($subject['course_2'] == 'Bachelor of Science in Accountancy') ? 'selected' : ''; ?>>Bachelor of Science in Accountancy</option>

        <option value="Bachelor of Science in Computer Science"<?php echo ($subject['course_2'] == 'Bachelor of Science in Computer Science') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>

        <option value="Bachelor of Science in Business Administration"<?php echo ($subject['course_2'] == 'Bachelor of Science in Business Administration') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>

        <option value="Bachelor of Elementary Education"<?php echo ($subject['course_2'] == 'Bachelor of Elementary Education') ? 'selected' : ''; ?>>Bachelor of Elementary Education</option>

        <option value="Bachelor of Secondary Education"<?php echo ($subject['course_2'] == 'Bachelor of Secondary Education') ? 'selected' : ''; ?>>Bachelor of Secondary Education</option>

        <option value="Bachelor of Physical Education - Sports & Wellness Management" <?php echo ($subject['course_2'] == 'Bachelor of Physical Education - Sports & Wellness Management') ? 'selected' : ''; ?>>Bachelor of Physical Education - Sports & Wellness Management</option>

        <option value="Bachelor of Physical Education" <?php echo ($subject['course_2'] == 'Bachelor of Physical Education') ? 'selected' : ''; ?>>Bachelor of Physical Education</option>

        <option value="Bachelor of Library and Information Science" <?php echo ($subject['course_2'] == 'Bachelor of Library and Information Science') ? 'selected' : ''; ?>>Bachelor of Library and Information Science</option>

        <option value="Teacher Certificate Program" <?php echo ($subject['course_2'] == 'Teacher Certificate Program') ? 'selected' : ''; ?>>Teacher Certificate Program</option>

        <option value="Bachelor of Science in Nursing" <?php echo ($subject['course_2'] == 'Bachelor of Science in Nursing') ? 'selected' : ''; ?>>Bachelor of Science in Nursing</option>

        <option value="Bachelor of Science in Physical Therapy"<?php echo ($subject['course_2'] == 'Bachelor of Science in Physical Therapy') ? 'selected' : ''; ?>>Bachelor of Science in Physical Therapy</option>

        <option value="Bachelor of Science in Radiologic Technology"<?php echo ($subject['course_2'] == 'Bachelor of Science in Radiologic Technology') ? 'selected' : ''; ?>>Bachelor of Science in Radiologic Technology</option>

        <option value="Bachelor of Science Medical Technology/ Medical Laboratory Science" <?php echo ($subject['course_2'] == 'Bachelor of Science Medical Technology/ Medical Laboratory Science') ? 'selected' : ''; ?>>Bachelor of Science Medical Technology/ Medical Laboratory Science</option>

        <option value="Bachelor of Science in Pharmacy" <?php echo ($subject['course_2'] == 'Bachelor of Science in Pharmacy') ? 'selected' : ''; ?>>Bachelor of Science in Pharmacy</option>

        <option value="Bachelor of Science in Psychology" <?php echo ($subject['course_2'] == 'Bachelor of Science in Psychology') ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>

        <option value="Bachelor of Science in Midwifery; Diploma in Midwifery" <?php echo ($subject['course_2'] == 'Bachelor of Science in Midwifery; Diploma in Midwifery') ? 'selected' : ''; ?>>Bachelor of Science in Midwifery; Diploma in Midwifery</option>

        <option value="Bachelor of Science in Hospitality Management" <?php echo ($subject['course_2'] == 'Bachelor of Science in Hospitality Management') ? 'selected' : ''; ?>>Bachelor of Science in Hospitality Management</option>

        <option value="Bachelor of Science in Tourism Management"<?php echo ($subject['course_2'] == 'Bachelor of Science in Tourism Management') ? 'selected' : ''; ?>>Bachelor of Science in Tourism Management</option>


        </select><br><br>




        3rd Course : <select name="course_3" id="course_3">

        <option value="N/A" <?php echo ($subject['course_3'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>

        <option value="Bachelor of Arts in English, Political Science, Psychology & History" <?php echo ($subject['course_3'] == 'Bachelor of Arts in English, Political Science, Psychology & History') ? 'selected' : ''; ?>>Bachelor of Arts in English, Political Science, Psychology & History</option>

        <option value="Bachelor of Performing Arts" <?php echo ($subject['course_3'] == 'Bachelor of Performing Arts') ? 'selected' : ''; ?>>Bachelor of Performing Arts</option>

        <option value="Bachelor of Science in Criminology" <?php echo ($subject['course_3'] == 'Bachelor of Science in Criminology') ? 'selected' : ''; ?>>Bachelor of Science in Criminology</option>

        <option value="Bachelor of Science in Accountancy" <?php echo ($subject['course_3'] == 'Bachelor of Science in Accountancy') ? 'selected' : ''; ?>>Bachelor of Science in Accountancy</option>

        <option value="Bachelor of Science in Computer Science"<?php echo ($subject['course_3'] == 'Bachelor of Science in Computer Science') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>

        <option value="Bachelor of Science in Business Administration"<?php echo ($subject['course_3'] == 'Bachelor of Science in Business Administration') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>

        <option value="Bachelor of Elementary Education"<?php echo ($subject['course_3'] == 'Bachelor of Elementary Education') ? 'selected' : ''; ?>>Bachelor of Elementary Education</option>

        <option value="Bachelor of Secondary Education"<?php echo ($subject['course_3'] == 'Bachelor of Secondary Education') ? 'selected' : ''; ?>>Bachelor of Secondary Education</option>

        <option value="Bachelor of Physical Education - Sports & Wellness Management" <?php echo ($subject['course_3'] == 'Bachelor of Physical Education - Sports & Wellness Management') ? 'selected' : ''; ?>>Bachelor of Physical Education - Sports & Wellness Management</option>

        <option value="Bachelor of Physical Education" <?php echo ($subject['course_3'] == 'Bachelor of Physical Education') ? 'selected' : ''; ?>>Bachelor of Physical Education</option>

        <option value="Bachelor of Library and Information Science" <?php echo ($subject['course_3'] == 'Bachelor of Library and Information Science') ? 'selected' : ''; ?>>Bachelor of Library and Information Science</option>

        <option value="Teacher Certificate Program" <?php echo ($subject['course_3'] == 'Teacher Certificate Program') ? 'selected' : ''; ?>>Teacher Certificate Program</option>

        <option value="Bachelor of Science in Nursing" <?php echo ($subject['course_3'] == 'Bachelor of Science in Nursing') ? 'selected' : ''; ?>>Bachelor of Science in Nursing</option>

        <option value="Bachelor of Science in Physical Therapy"<?php echo ($subject['course_3'] == 'Bachelor of Science in Physical Therapy') ? 'selected' : ''; ?>>Bachelor of Science in Physical Therapy</option>

        <option value="Bachelor of Science in Radiologic Technology"<?php echo ($subject['course_3'] == 'Bachelor of Science in Radiologic Technology') ? 'selected' : ''; ?>>Bachelor of Science in Radiologic Technology</option>

        <option value="Bachelor of Science Medical Technology/ Medical Laboratory Science" <?php echo ($subject['course_3'] == 'Bachelor of Science Medical Technology/ Medical Laboratory Science') ? 'selected' : ''; ?>>Bachelor of Science Medical Technology/ Medical Laboratory Science</option>

        <option value="Bachelor of Science in Pharmacy" <?php echo ($subject['course_3'] == 'Bachelor of Science in Pharmacy') ? 'selected' : ''; ?>>Bachelor of Science in Pharmacy</option>

        <option value="Bachelor of Science in Psychology" <?php echo ($subject['course_3'] == 'Bachelor of Science in Psychology') ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>

        <option value="Bachelor of Science in Midwifery; Diploma in Midwifery" <?php echo ($subject['course_3'] == 'Bachelor of Science in Midwifery; Diploma in Midwifery') ? 'selected' : ''; ?>>Bachelor of Science in Midwifery; Diploma in Midwifery</option>

        <option value="Bachelor of Science in Hospitality Management" <?php echo ($subject['course_3'] == 'Bachelor of Science in Hospitality Management') ? 'selected' : ''; ?>>Bachelor of Science in Hospitality Management</option>

        <option value="Bachelor of Science in Tourism Management"<?php echo ($subject['course_3'] == 'Bachelor of Science in Tourism Management') ? 'selected' : ''; ?>>Bachelor of Science in Tourism Management</option>

        </select><br><br>

    


        4th Course : <select name="course_4" id="course_4">

       <option value="N/A" <?php echo ($subject['course_4'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>

        <option value="Bachelor of Arts in English, Political Science, Psychology & History" <?php echo ($subject['course_4'] == 'Bachelor of Arts in English, Political Science, Psychology & History') ? 'selected' : ''; ?>>Bachelor of Arts in English, Political Science, Psychology & History</option>

        <option value="Bachelor of Performing Arts" <?php echo ($subject['course_4'] == 'Bachelor of Performing Arts') ? 'selected' : ''; ?>>Bachelor of Performing Arts</option>

        <option value="Bachelor of Science in Criminology" <?php echo ($subject['course_4'] == 'Bachelor of Science in Criminology') ? 'selected' : ''; ?>>Bachelor of Science in Criminology</option>

        <option value="Bachelor of Science in Accountancy" <?php echo ($subject['course_4'] == 'Bachelor of Science in Accountancy') ? 'selected' : ''; ?>>Bachelor of Science in Accountancy</option>

        <option value="Bachelor of Science in Computer Science"<?php echo ($subject['course_4'] == 'Bachelor of Science in Computer Science') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>

        <option value="Bachelor of Science in Business Administration"<?php echo ($subject['course_4'] == 'Bachelor of Science in Business Administration') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>

        <option value="Bachelor of Elementary Education"<?php echo ($subject['course_4'] == 'Bachelor of Elementary Education') ? 'selected' : ''; ?>>Bachelor of Elementary Education</option>

        <option value="Bachelor of Secondary Education"<?php echo ($subject['course_4'] == 'Bachelor of Secondary Education') ? 'selected' : ''; ?>>Bachelor of Secondary Education</option>

        <option value="Bachelor of Physical Education - Sports & Wellness Management" <?php echo ($subject['course_4'] == 'Bachelor of Physical Education - Sports & Wellness Management') ? 'selected' : ''; ?>>Bachelor of Physical Education - Sports & Wellness Management</option>

        <option value="Bachelor of Physical Education" <?php echo ($subject['course_4'] == 'Bachelor of Physical Education') ? 'selected' : ''; ?>>Bachelor of Physical Education</option>

        <option value="Bachelor of Library and Information Science" <?php echo ($subject['course_4'] == 'Bachelor of Library and Information Science') ? 'selected' : ''; ?>>Bachelor of Library and Information Science</option>

        <option value="Teacher Certificate Program" <?php echo ($subject['course_4'] == 'Teacher Certificate Program') ? 'selected' : ''; ?>>Teacher Certificate Program</option>

        <option value="Bachelor of Science in Nursing" <?php echo ($subject['course_4'] == 'Bachelor of Science in Nursing') ? 'selected' : ''; ?>>Bachelor of Science in Nursing</option>

        <option value="Bachelor of Science in Physical Therapy"<?php echo ($subject['course_4'] == 'Bachelor of Science in Physical Therapy') ? 'selected' : ''; ?>>Bachelor of Science in Physical Therapy</option>

        <option value="Bachelor of Science in Radiologic Technology"<?php echo ($subject['course_4'] == 'Bachelor of Science in Radiologic Technology') ? 'selected' : ''; ?>>Bachelor of Science in Radiologic Technology</option>

        <option value="Bachelor of Science Medical Technology/ Medical Laboratory Science" <?php echo ($subject['course_4'] == 'Bachelor of Science Medical Technology/ Medical Laboratory Science') ? 'selected' : ''; ?>>Bachelor of Science Medical Technology/ Medical Laboratory Science</option>

        <option value="Bachelor of Science in Pharmacy" <?php echo ($subject['course_4'] == 'Bachelor of Science in Pharmacy') ? 'selected' : ''; ?>>Bachelor of Science in Pharmacy</option>

        <option value="Bachelor of Science in Psychology" <?php echo ($subject['course_4'] == 'Bachelor of Science in Psychology') ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>

        <option value="Bachelor of Science in Midwifery; Diploma in Midwifery" <?php echo ($subject['course_4'] == 'Bachelor of Science in Midwifery; Diploma in Midwifery') ? 'selected' : ''; ?>>Bachelor of Science in Midwifery; Diploma in Midwifery</option>

        <option value="Bachelor of Science in Hospitality Management" <?php echo ($subject['course_4'] == 'Bachelor of Science in Hospitality Management') ? 'selected' : ''; ?>>Bachelor of Science in Hospitality Management</option>

        <option value="Bachelor of Science in Tourism Management"<?php echo ($subject['course_4'] == 'Bachelor of Science in Tourism Management') ? 'selected' : ''; ?>>Bachelor of Science in Tourism Management</option>

        </select><br><br>


 5th Course : <select name="course_5" id="course_5">

        <option value="N/A" <?php echo ($subject['course_5'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>

        <option value="Bachelor of Arts in English, Political Science, Psychology & History" <?php echo ($subject['course_5'] == 'Bachelor of Arts in English, Political Science, Psychology & History') ? 'selected' : ''; ?>>Bachelor of Arts in English, Political Science, Psychology & History</option>

        <option value="Bachelor of Performing Arts" <?php echo ($subject['course_5'] == 'Bachelor of Performing Arts') ? 'selected' : ''; ?>>Bachelor of Performing Arts</option>

        <option value="Bachelor of Science in Criminology" <?php echo ($subject['course_5'] == 'Bachelor of Science in Criminology') ? 'selected' : ''; ?>>Bachelor of Science in Criminology</option>

        <option value="Bachelor of Science in Accountancy" <?php echo ($subject['course_5'] == 'Bachelor of Science in Accountancy') ? 'selected' : ''; ?>>Bachelor of Science in Accountancy</option>

        <option value="Bachelor of Science in Computer Science"<?php echo ($subject['course_5'] == 'Bachelor of Science in Computer Science') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>

        <option value="Bachelor of Science in Business Administration"<?php echo ($subject['course_5'] == 'Bachelor of Science in Business Administration') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>

        <option value="Bachelor of Elementary Education"<?php echo ($subject['course_5'] == 'Bachelor of Elementary Education') ? 'selected' : ''; ?>>Bachelor of Elementary Education</option>

        <option value="Bachelor of Secondary Education"<?php echo ($subject['course_5'] == 'Bachelor of Secondary Education') ? 'selected' : ''; ?>>Bachelor of Secondary Education</option>

        <option value="Bachelor of Physical Education - Sports & Wellness Management" <?php echo ($subject['course_5'] == 'Bachelor of Physical Education - Sports & Wellness Management') ? 'selected' : ''; ?>>Bachelor of Physical Education - Sports & Wellness Management</option>

        <option value="Bachelor of Physical Education" <?php echo ($subject['course_5'] == 'Bachelor of Physical Education') ? 'selected' : ''; ?>>Bachelor of Physical Education</option>

        <option value="Bachelor of Library and Information Science" <?php echo ($subject['course_5'] == 'Bachelor of Library and Information Science') ? 'selected' : ''; ?>>Bachelor of Library and Information Science</option>

        <option value="Teacher Certificate Program" <?php echo ($subject['course_5'] == 'Teacher Certificate Program') ? 'selected' : ''; ?>>Teacher Certificate Program</option>

        <option value="Bachelor of Science in Nursing" <?php echo ($subject['course_5'] == 'Bachelor of Science in Nursing') ? 'selected' : ''; ?>>Bachelor of Science in Nursing</option>

        <option value="Bachelor of Science in Physical Therapy"<?php echo ($subject['course_5'] == 'Bachelor of Science in Physical Therapy') ? 'selected' : ''; ?>>Bachelor of Science in Physical Therapy</option>

        <option value="Bachelor of Science in Radiologic Technology"<?php echo ($subject['course_5'] == 'Bachelor of Science in Radiologic Technology') ? 'selected' : ''; ?>>Bachelor of Science in Radiologic Technology</option>

        <option value="Bachelor of Science Medical Technology/ Medical Laboratory Science" <?php echo ($subject['course_5'] == 'Bachelor of Science Medical Technology/ Medical Laboratory Science') ? 'selected' : ''; ?>>Bachelor of Science Medical Technology/ Medical Laboratory Science</option>

        <option value="Bachelor of Science in Pharmacy" <?php echo ($subject['course_5'] == 'Bachelor of Science in Pharmacy') ? 'selected' : ''; ?>>Bachelor of Science in Pharmacy</option>

        <option value="Bachelor of Science in Psychology" <?php echo ($subject['course_5'] == 'Bachelor of Science in Psychology') ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>

        <option value="Bachelor of Science in Midwifery; Diploma in Midwifery" <?php echo ($subject['course_5'] == 'Bachelor of Science in Midwifery; Diploma in Midwifery') ? 'selected' : ''; ?>>Bachelor of Science in Midwifery; Diploma in Midwifery</option>

        <option value="Bachelor of Science in Hospitality Management" <?php echo ($subject['course_5'] == 'Bachelor of Science in Hospitality Management') ? 'selected' : ''; ?>>Bachelor of Science in Hospitality Management</option>

        <option value="Bachelor of Science in Tourism Management"<?php echo ($subject['course_5'] == 'Bachelor of Science in Tourism Management') ? 'selected' : ''; ?>>Bachelor of Science in Tourism Management</option>

        </select><br><br>




 6th Course : <select name="course_6" id="course_6">

        <option value="N/A" <?php echo ($subject['course_6'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>

        <option value="Bachelor of Arts in English, Political Science, Psychology & History" <?php echo ($subject['course_6'] == 'Bachelor of Arts in English, Political Science, Psychology & History') ? 'selected' : ''; ?>>Bachelor of Arts in English, Political Science, Psychology & History</option>

        <option value="Bachelor of Performing Arts" <?php echo ($subject['course_6'] == 'Bachelor of Performing Arts') ? 'selected' : ''; ?>>Bachelor of Performing Arts</option>

        <option value="Bachelor of Science in Criminology" <?php echo ($subject['course_6'] == 'Bachelor of Science in Criminology') ? 'selected' : ''; ?>>Bachelor of Science in Criminology</option>

        <option value="Bachelor of Science in Accountancy" <?php echo ($subject['course_6'] == 'Bachelor of Science in Accountancy') ? 'selected' : ''; ?>>Bachelor of Science in Accountancy</option>

        <option value="Bachelor of Science in Computer Science"<?php echo ($subject['course_6'] == 'Bachelor of Science in Computer Science') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>

        <option value="Bachelor of Science in Business Administration"<?php echo ($subject['course_6'] == 'Bachelor of Science in Business Administration') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>

        <option value="Bachelor of Elementary Education"<?php echo ($subject['course_6'] == 'Bachelor of Elementary Education') ? 'selected' : ''; ?>>Bachelor of Elementary Education</option>

        <option value="Bachelor of Secondary Education"<?php echo ($subject['course_6'] == 'Bachelor of Secondary Education') ? 'selected' : ''; ?>>Bachelor of Secondary Education</option>

        <option value="Bachelor of Physical Education - Sports & Wellness Management" <?php echo ($subject['course_6'] == 'Bachelor of Physical Education - Sports & Wellness Management') ? 'selected' : ''; ?>>Bachelor of Physical Education - Sports & Wellness Management</option>

        <option value="Bachelor of Physical Education" <?php echo ($subject['course_6'] == 'Bachelor of Physical Education') ? 'selected' : ''; ?>>Bachelor of Physical Education</option>

        <option value="Bachelor of Library and Information Science" <?php echo ($subject['course_6'] == 'Bachelor of Library and Information Science') ? 'selected' : ''; ?>>Bachelor of Library and Information Science</option>

        <option value="Teacher Certificate Program" <?php echo ($subject['course_6'] == 'Teacher Certificate Program') ? 'selected' : ''; ?>>Teacher Certificate Program</option>

        <option value="Bachelor of Science in Nursing" <?php echo ($subject['course_6'] == 'Bachelor of Science in Nursing') ? 'selected' : ''; ?>>Bachelor of Science in Nursing</option>

        <option value="Bachelor of Science in Physical Therapy"<?php echo ($subject['course_6'] == 'Bachelor of Science in Physical Therapy') ? 'selected' : ''; ?>>Bachelor of Science in Physical Therapy</option>

        <option value="Bachelor of Science in Radiologic Technology"<?php echo ($subject['course_6'] == 'Bachelor of Science in Radiologic Technology') ? 'selected' : ''; ?>>Bachelor of Science in Radiologic Technology</option>

        <option value="Bachelor of Science Medical Technology/ Medical Laboratory Science" <?php echo ($subject['course_6'] == 'Bachelor of Science Medical Technology/ Medical Laboratory Science') ? 'selected' : ''; ?>>Bachelor of Science Medical Technology/ Medical Laboratory Science</option>

        <option value="Bachelor of Science in Pharmacy" <?php echo ($subject['course_6'] == 'Bachelor of Science in Pharmacy') ? 'selected' : ''; ?>>Bachelor of Science in Pharmacy</option>

        <option value="Bachelor of Science in Psychology" <?php echo ($subject['course_6'] == 'Bachelor of Science in Psychology') ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>

        <option value="Bachelor of Science in Midwifery; Diploma in Midwifery" <?php echo ($subject['course_6'] == 'Bachelor of Science in Midwifery; Diploma in Midwifery') ? 'selected' : ''; ?>>Bachelor of Science in Midwifery; Diploma in Midwifery</option>

        <option value="Bachelor of Science in Hospitality Management" <?php echo ($subject['course_6'] == 'Bachelor of Science in Hospitality Management') ? 'selected' : ''; ?>>Bachelor of Science in Hospitality Management</option>

        <option value="Bachelor of Science in Tourism Management"<?php echo ($subject['course_6'] == 'Bachelor of Science in Tourism Management') ? 'selected' : ''; ?>>Bachelor of Science in Tourism Management</option>

        </select><br><br>




 7th Course : <select name="course_7" id="course_7">

        <option value="N/A" <?php echo ($subject['course_7'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>

        <option value="Bachelor of Arts in English, Political Science, Psychology & History" <?php echo ($subject['course_7'] == 'Bachelor of Arts in English, Political Science, Psychology & History') ? 'selected' : ''; ?>>Bachelor of Arts in English, Political Science, Psychology & History</option>

        <option value="Bachelor of Performing Arts" <?php echo ($subject['course_7'] == 'Bachelor of Performing Arts') ? 'selected' : ''; ?>>Bachelor of Performing Arts</option>

        <option value="Bachelor of Science in Criminology" <?php echo ($subject['course_7'] == 'Bachelor of Science in Criminology') ? 'selected' : ''; ?>>Bachelor of Science in Criminology</option>

        <option value="Bachelor of Science in Accountancy" <?php echo ($subject['course_7'] == 'Bachelor of Science in Accountancy') ? 'selected' : ''; ?>>Bachelor of Science in Accountancy</option>

        <option value="Bachelor of Science in Computer Science"<?php echo ($subject['course_7'] == 'Bachelor of Science in Computer Science') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>

        <option value="Bachelor of Science in Business Administration"<?php echo ($subject['course_7'] == 'Bachelor of Science in Business Administration') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>

        <option value="Bachelor of Elementary Education"<?php echo ($subject['course_7'] == 'Bachelor of Elementary Education') ? 'selected' : ''; ?>>Bachelor of Elementary Education</option>

        <option value="Bachelor of Secondary Education"<?php echo ($subject['course_7'] == 'Bachelor of Secondary Education') ? 'selected' : ''; ?>>Bachelor of Secondary Education</option>

        <option value="Bachelor of Physical Education - Sports & Wellness Management" <?php echo ($subject['course_7'] == 'Bachelor of Physical Education - Sports & Wellness Management') ? 'selected' : ''; ?>>Bachelor of Physical Education - Sports & Wellness Management</option>

        <option value="Bachelor of Physical Education" <?php echo ($subject['course_7'] == 'Bachelor of Physical Education') ? 'selected' : ''; ?>>Bachelor of Physical Education</option>

        <option value="Bachelor of Library and Information Science" <?php echo ($subject['course_7'] == 'Bachelor of Library and Information Science') ? 'selected' : ''; ?>>Bachelor of Library and Information Science</option>

        <option value="Teacher Certificate Program" <?php echo ($subject['course_7'] == 'Teacher Certificate Program') ? 'selected' : ''; ?>>Teacher Certificate Program</option>

        <option value="Bachelor of Science in Nursing" <?php echo ($subject['course_7'] == 'Bachelor of Science in Nursing') ? 'selected' : ''; ?>>Bachelor of Science in Nursing</option>

        <option value="Bachelor of Science in Physical Therapy"<?php echo ($subject['course_7'] == 'Bachelor of Science in Physical Therapy') ? 'selected' : ''; ?>>Bachelor of Science in Physical Therapy</option>

        <option value="Bachelor of Science in Radiologic Technology"<?php echo ($subject['course_7'] == 'Bachelor of Science in Radiologic Technology') ? 'selected' : ''; ?>>Bachelor of Science in Radiologic Technology</option>

        <option value="Bachelor of Science Medical Technology/ Medical Laboratory Science" <?php echo ($subject['course_7'] == 'Bachelor of Science Medical Technology/ Medical Laboratory Science') ? 'selected' : ''; ?>>Bachelor of Science Medical Technology/ Medical Laboratory Science</option>

        <option value="Bachelor of Science in Pharmacy" <?php echo ($subject['course_7'] == 'Bachelor of Science in Pharmacy') ? 'selected' : ''; ?>>Bachelor of Science in Pharmacy</option>

        <option value="Bachelor of Science in Psychology" <?php echo ($subject['course_7'] == 'Bachelor of Science in Psychology') ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>

        <option value="Bachelor of Science in Midwifery; Diploma in Midwifery" <?php echo ($subject['course_7'] == 'Bachelor of Science in Midwifery; Diploma in Midwifery') ? 'selected' : ''; ?>>Bachelor of Science in Midwifery; Diploma in Midwifery</option>

        <option value="Bachelor of Science in Hospitality Management" <?php echo ($subject['course_7'] == 'Bachelor of Science in Hospitality Management') ? 'selected' : ''; ?>>Bachelor of Science in Hospitality Management</option>

        <option value="Bachelor of Science in Tourism Management"<?php echo ($subject['course_7'] == 'Bachelor of Science in Tourism Management') ? 'selected' : ''; ?>>Bachelor of Science in Tourism Management</option>

        </select><br><br>




 8th Course : <select name="course_8" id="course_8">

        <option value="N/A" <?php echo ($subject['course_8'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>

        <option value="Bachelor of Arts in English, Political Science, Psychology & History" <?php echo ($subject['course_8'] == 'Bachelor of Arts in English, Political Science, Psychology & History') ? 'selected' : ''; ?>>Bachelor of Arts in English, Political Science, Psychology & History</option>

        <option value="Bachelor of Performing Arts" <?php echo ($subject['course_8'] == 'Bachelor of Performing Arts') ? 'selected' : ''; ?>>Bachelor of Performing Arts</option>

        <option value="Bachelor of Science in Criminology" <?php echo ($subject['course_8'] == 'Bachelor of Science in Criminology') ? 'selected' : ''; ?>>Bachelor of Science in Criminology</option>

        <option value="Bachelor of Science in Accountancy" <?php echo ($subject['course_8'] == 'Bachelor of Science in Accountancy') ? 'selected' : ''; ?>>Bachelor of Science in Accountancy</option>

        <option value="Bachelor of Science in Computer Science"<?php echo ($subject['course_8'] == 'Bachelor of Science in Computer Science') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>

        <option value="Bachelor of Science in Business Administration"<?php echo ($subject['course_8'] == 'Bachelor of Science in Business Administration') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>

        <option value="Bachelor of Elementary Education"<?php echo ($subject['course_8'] == 'Bachelor of Elementary Education') ? 'selected' : ''; ?>>Bachelor of Elementary Education</option>

        <option value="Bachelor of Secondary Education"<?php echo ($subject['course_8'] == 'Bachelor of Secondary Education') ? 'selected' : ''; ?>>Bachelor of Secondary Education</option>

        <option value="Bachelor of Physical Education - Sports & Wellness Management" <?php echo ($subject['course_8'] == 'Bachelor of Physical Education - Sports & Wellness Management') ? 'selected' : ''; ?>>Bachelor of Physical Education - Sports & Wellness Management</option>

        <option value="Bachelor of Physical Education" <?php echo ($subject['course_8'] == 'Bachelor of Physical Education') ? 'selected' : ''; ?>>Bachelor of Physical Education</option>

        <option value="Bachelor of Library and Information Science" <?php echo ($subject['course_8'] == 'Bachelor of Library and Information Science') ? 'selected' : ''; ?>>Bachelor of Library and Information Science</option>

        <option value="Teacher Certificate Program" <?php echo ($subject['course_8'] == 'Teacher Certificate Program') ? 'selected' : ''; ?>>Teacher Certificate Program</option>

        <option value="Bachelor of Science in Nursing" <?php echo ($subject['course_8'] == 'Bachelor of Science in Nursing') ? 'selected' : ''; ?>>Bachelor of Science in Nursing</option>

        <option value="Bachelor of Science in Physical Therapy"<?php echo ($subject['course_8'] == 'Bachelor of Science in Physical Therapy') ? 'selected' : ''; ?>>Bachelor of Science in Physical Therapy</option>

        <option value="Bachelor of Science in Radiologic Technology"<?php echo ($subject['course_8'] == 'Bachelor of Science in Radiologic Technology') ? 'selected' : ''; ?>>Bachelor of Science in Radiologic Technology</option>

        <option value="Bachelor of Science Medical Technology/ Medical Laboratory Science" <?php echo ($subject['course_8'] == 'Bachelor of Science Medical Technology/ Medical Laboratory Science') ? 'selected' : ''; ?>>Bachelor of Science Medical Technology/ Medical Laboratory Science</option>

        <option value="Bachelor of Science in Pharmacy" <?php echo ($subject['course_8'] == 'Bachelor of Science in Pharmacy') ? 'selected' : ''; ?>>Bachelor of Science in Pharmacy</option>

        <option value="Bachelor of Science in Psychology" <?php echo ($subject['course_8'] == 'Bachelor of Science in Psychology') ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>

        <option value="Bachelor of Science in Midwifery; Diploma in Midwifery" <?php echo ($subject['course_8'] == 'Bachelor of Science in Midwifery; Diploma in Midwifery') ? 'selected' : ''; ?>>Bachelor of Science in Midwifery; Diploma in Midwifery</option>

        <option value="Bachelor of Science in Hospitality Management" <?php echo ($subject['course_8'] == 'Bachelor of Science in Hospitality Management') ? 'selected' : ''; ?>>Bachelor of Science in Hospitality Management</option>

        <option value="Bachelor of Science in Tourism Management"<?php echo ($subject['course_8'] == 'Bachelor of Science in Tourism Management') ? 'selected' : ''; ?>>Bachelor of Science in Tourism Management</option>

        </select><br><br>

</center>

        <br>
        <input type="submit" name="btnsubmit" class = 'btn btn-success' value="Assign">
        <?php

        echo "<a href = 'subject-courses.php?subject_code=" . $_GET['subject_code']. "' class = 'btn btn-default' style='float: right;'>Cancel</a>";

        ?>

    </form>

    <script src="password-script.js"></script>

</body>
</html>