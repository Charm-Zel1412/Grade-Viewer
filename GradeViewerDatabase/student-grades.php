<html>
<title> Student Grades - Arellano University Subject Advising - AUSMS </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="design.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<header>
    <img src="new-au-logo.png" style="width:100px;height:100px;">
Arellano University Subject Advising - AUSMS 
</header>

<body>
	<?php

	session_start();

	//check if there is a session
	if(isset($_SESSION['username'])){
		if($_SESSION['usertype'] == "STUDENT"){

        }
	}

	else{
		//redirect to the login page
		header("location: login.php");
	}
	?>

	<div class="topnav" id="myTopnav">

            <a href="main.php">Home</a>

            <a href="logout.php" style="float: right;" class="active">Log Out</a>

        <?php if ($_SESSION['usertype'] == "ADMINISTRATOR") : ?>
       <a href="accounts-management.php">Accounts Management</a>
    <?php endif; ?>

      <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "STAFF" || $_SESSION['usertype'] == "REGISTRAR") : ?>
       <a href="students-management.php" >Students Management</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "STAFF" || $_SESSION['usertype'] == "REGISTRAR") : ?>
       <a href="subjects-management.php">Subjects Management</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "REGISTRAR") : ?>
       <a href="grades-management.php">Grades Management</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php" style = "background-color: white; color: black;">Student Grades</a>
    <?php endif; ?>
      
      </div>

	</body>

<footer>

	<p>Arellano University - Jose Rizal Campus</p>
</footer>

</html>
<?php

require_once "config.php";

    $sql = "SELECT tblstudents.*, tblstudents.studentnumber, tblgrades.grade, tblgrades.subject_code, tblsubjects.unit, tblsubjects.subject_description, tblgrades.instructor
            FROM tblstudents
            LEFT JOIN tblgrades ON tblgrades.studentnumber = tblstudents.studentnumber
            LEFT JOIN tblsubjects ON tblsubjects.subject_code = tblgrades.subject_code
            WHERE tblstudents.studentnumber LIKE ?";


      if ($stmt = mysqli_prepare($link, $sql)) {
        $searchvalue =  $_SESSION['username'] ;
        mysqli_stmt_bind_param($stmt, "s", $searchvalue);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            buildtable($result);
        }
    }
    else{
        echo "Error on search";
      }
 




function buildtable($result) {
    if(mysqli_num_rows($result) > 0){
        //create a table using html

$firstrow = true;
        // display
    
    while ($account = mysqli_fetch_array($result)) {
    echo "<form>";

    if ($firstrow) {
        // code...
    

        echo "Student Number: <b>" . $account['studentnumber'] . "</b><br>";
        echo "Name: <b>" . $account['lastname'] . ", " . $account['firstname'] . " " . $account['secondname'] . " " . $account['middlename'] . "</b><br>";
        echo "Course: <b>" . $account['course'] . "</b><br>";
        echo "Year level: <b>" . $account['yearlevel'] . "</b><br><br>";



      echo "<center>";
        echo "List of Grades";

        echo"<br> <br>";
        echo "</center>";
        

        echo "<table>";

        //create the header of the table
        echo "<tr>";
        echo "<th>Code</th><th>Description</th><th>Unit</th><th>Instructor</th><th>Grade</th>";
        echo "</tr>";

        $firstrow = false;

    }
    

            if (!empty($account['subject_code']) && !empty($account['subject_description']) && !empty($account['unit']) && !empty($account['grade'])) {

            echo "<tr>";
            echo "<td>" . $account['subject_code'] . "</td>";
            echo "<td>" . $account['subject_description'] . "</td>";
            echo "<td>" . $account['unit'] . "</td>";
            echo "<td>" . $account['instructor'] . "</td>";
            echo "<td><b>" . $account['grade'] . "</b></td>";
            }

            echo "</tr>";
        }

        echo "</table>";

        echo "<br><br><br><br>";
    }

    else{

     echo "<form>";

        echo "Student Number: " . "<br>";
        echo "Name: " . "<br>";
        echo "Course: " . "<br>";
      echo "Year level: " . "<br><br>";

      echo "<center>";
        echo "List of Grades";

        echo "</center>";
        

        echo "<table>";

        //create the header of the table
        echo "<tr>";
        echo "<th>Code</th><th>Description</th><th>Unit</th><th>Grade</th>";
        echo "</tr>";

      echo "</form>";
    }

    echo "</form>";

}
?>
