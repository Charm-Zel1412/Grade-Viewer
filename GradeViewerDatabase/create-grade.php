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
                $sql = "INSERT INTO tblgrades (studentnumber, subject_code, grade, gradedby, datecreated) VALUES (?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($link, $sql)) {

                    $date = date("d/m/Y");

                    mysqli_stmt_bind_param($stmt, "sssss", $_POST['txtstudentnum'], $_POST['subject_code'], $_POST['grade'], $_SESSION['username'] , $date);

                    if (mysqli_stmt_execute($stmt)) {
                        
                        $sql = "INSERT INTO tbllogs (datelog, timelog, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $date = date("m/d/Y");
                            $time = date("h:i:sa");
                            $module = "Grades";
                            $action = "Create";
                            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtstudentnum'], $action, $_SESSION['username']);
                            if (mysqli_stmt_execute($stmt)) {

                                $_SESSION['created'] = "Grade CREATED!";
                                echo "<script>location = 'manage-grade.php?studentnumber=" . $_GET['studentnumber'] . "';</script>";
                                exit();
                            }

                            else{
                                $_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
                                echo "<script>location = 'manage-grade.php?studentnumber=" . $_GET['studentnumber'] . "';</script>";
                                exit();
                            }
                        }
                    }
                    else{
                        $_SESSION['error'] = "<font color = 'red'> Error on inserting grade.</font>";
                        echo "<script>location = 'manage-grade.php?studentnumber=" . $_GET['studentnumber'] . "';</script>";
                        exit();
                        }
                }
            }
            else{
                $_SESSION['error'] = "<font color ='red'>Student already has this Subject code in use.</font>";
                echo "<script>location = 'manage-grade.php?studentnumber=" . $_GET['studentnumber'] . "';</script>";
                exit();
                }
        }

        else{
            $_SESSION['error'] = "<font color = 'red'>Error on checking grade.</font>";
            echo "<script>location = 'manage-grade.php?studentnumber=" . $_GET['studentnumber'] . "';</script>";
            exit();
            }
    }
}

else{ // loading the current values of the account

    if (isset($_GET['studentnumber']) && !empty(trim($_GET['studentnumber']))) {
        $sql = "SELECT tblstudents.* FROM tblstudents INNER JOIN tblsubjects WHERE studentnumber = ?";
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

Student #: <?php echo $account['studentnumber'];?> 
<input type="hidden" name="txtstudentnum" value="<?php echo $account['studentnumber'];?>" required> <br>

Name: <?php echo $account['lastname'] . ", " . $account['firstname'] . " " . $account['secondname'] ." " . $account['middlename'];  ?><br>

Course: <?php echo $account['course'];  ?><br>

Year level: <?php echo $account['yearlevel'];  ?><br><br>

Select Subject : <select class="subject_code" name="subject_code" id="subject_code" required>

        <option value="">--Select Subject--</option>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Arts in English, Political Science, Psychology & History") {
            ?>
        <option value="SubCode1(BAEPSPH)">SubCode1(BAEPSPH)</option>
        <option value="SubCode2(BAEPSPH)">SubCode2(BAEPSPH)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Performing Arts") {
            ?>
        <option value="SubCode1(BPA)">SubCode1(BPA)</option>
        <option value="SubCode2(BPA)">SubCode2(BPA)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Criminology") {
            ?>
        <option value="SubCode1(BSC)">SubCode1(BSC)</option>
        <option value="SubCode2(BSC)">SubCode2(BSC)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Accountancy") {
            ?>
        <option value="SubCode1(BSA)">SubCode1(BSA)</option>
        <option value="SubCode2(BSA)">SubCode2(BSA)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Computer Science") {
            ?>
        <option value="ITC127LAB">ITC127LAB</option>
        <option value="ITC127LEC">ITC127LEC</option>
        <option value="CS_221LAB">CS_221LAB</option>
        <option value="CS_221LEC">CS_221LEC</option>
        <option value="CS_222LAB">CS_222LAB</option>
        <option value="CS_222LEC">CS_222LEC</option>
        <option value="CS_223">CS_223</option>
        <option value="CS_224LAB">CS_224LAB</option>
        <option value="CS_224LEC">CS_224LEC</option>
        <option value="GCAS 14">GCAS 14</option>
        <option value="GCAS 18">GCAS 18</option>
        <option value="ITC 126">ITC 126</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Business Administration") {
            ?>
        <option value="SubCode1(BSBA)">SubCode1(BSBA)</option>
        <option value="SubCode2(BSBA)">SubCode2(BSBA)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Elementary Education") {
            ?>
        <option value="SubCode1(BEE)">SubCode1(BEE)</option>
        <option value="SubCode2(BEE)">SubCode2(BEE)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Secondary Education") {
            ?>
        <option value="SubCode1(2ndEd)">SubCode1(2ndEd)</option>
        <option value="SubCode2(2ndEd)">SubCode2(2ndEd)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Physical Education - Sports & Wellness Management") {
            ?>
        <option value="SubCode1(BPE-SWM)">SubCode1(BPE-SWM)</option>
        <option value="SubCode2(BPE-SWM)">SubCode2(BPE-SWM)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Physical Education") {
            ?>
        <option value="SubCode1(BPE)">SubCode1(BPE)</option>
        <option value="SubCode2(BPE)">SubCode2(BPE)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Library and Information Science") {
            ?>
        <option value="SubCode1(BLIS)">SubCode1(BLIS)</option>
        <option value="SubCode2(BLIS)">SubCode2(BLIS)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Teacher Certificate Program") {
            ?>
        <option value="SubCode1(TCP)">SubCode1(TCP)</option>
        <option value="SubCode2(TCP)">SubCode2(TCP)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Nursing") {
            ?>
        <option value="SubCode1(BSN)">SubCode1(BSN)</option>
        <option value="SubCode2(BSN)">SubCode2(BSN)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Physical Therapy") {
            ?>
        <option value="SubCode1(BSPT)">SubCode1(BSPT)</option>
        <option value="SubCode2(BSPT)">SubCode2(BSPT)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Radiologic Technology") {
            ?>
        <option value="SubCode1(BSRT)">SubCode1(BSRT)</option>
        <option value="SubCode2(BSRT)">SubCode2(BSRT)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science Medical Technology/ Medical Laboratory Science") {
            ?>
        <option value="SubCode1(BSMT/MLS)">SubCode1(BSMT/MLS)</option>
        <option value="SubCode2(BSMT/MLS)">SubCode2(BSMT/MLS)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Pharmacy") {
            ?>
        <option value="SubCode1(BSP)">SubCode1(BSP)</option>
        <option value="SubCode2(BSP)">SubCode2(BSP)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Psychology") {
            ?>
        <option value="SubCode1(BSPsycho)">SubCode1(BSPsycho)</option>
        <option value="SubCode2(BSPsycho)">SubCode2(BSPsycho)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Midwifery; Diploma in Midwifery") {
            ?>
        <option value="SubCode1(BSM;DM)">SubCode1(BSM;DM)</option>
        <option value="SubCode2(BSM;DM)">SubCode2(BSM;DM)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Hospitality Management") {
            ?>
        <option value="SubCode1(BSHM)">SubCode1(BSHM)</option>
        <option value="SubCode2(BSHM)">SubCode2(BSHM)</option>

        <?php
        }
        ?>

        <?php  
        $course = $account['course'];
        if ($course == "Bachelor of Science in Tourism Management") {
            ?>
        <option value="SubCode1(BSTM)">SubCode1(BSTM)</option>
        <option value="SubCode2(BSTM)">SubCode2(BSTM)</option>

        <?php
        }
        ?>



        </select><br>

<div class="result"></div> <br> 

    

<input type="submit" name="btncreate" class="btn btn-success" value="Submit">
<?php

echo "<a href = 'manage-grade.php?studentnumber=" . $account['studentnumber']. "' class = 'btn btn-default' style='float: right;'>Cancel</a>";
?>

</form>

<script src="description.js"></script>

</body>
</html>
