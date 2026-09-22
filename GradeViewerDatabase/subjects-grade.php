<html>
<title>Students Grade - Arellano University Subject Advising - AUSMS </title>

<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="design.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<header>
	<img src="new-au-logo.png" style="width:100px;height:100px;">
  Students Grade - Arellano University Subject Advising - AUSMS 
</header>


<style>
    
    td{
        font-size: 15px;
    }

    /* Toast container */
    .toast-container {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1050;
    }

    .custom-toast {
        min-width: 800px;
        margin-bottom: 10px;
        text-align: center;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
    }

</style>

<body>
	<?php

	session_start();

	//check if there is a session
	if(isset($_SESSION['username'])){

		if($_SESSION['usertype'] == "PROFESSOR"){

        }
	}

	else{
		//redirect to the login page
		$_SESSION['error'] = "User does not have the right to access this management/module!";
		header("location: main.php");
		exit();
	}
	?>

		<div class="topnav" id="myTopnav">

			<a href="main.php">Home</a>

			<a href="logout.php" style="float: right;" class="active">Log Out</a>

		<?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a>Manage Grade</a>
    <?php endif; ?>

	<?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="accounts-management.php">Accounts Management</a>
    <?php endif; ?>

      <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="students-management.php" >Students Management</a>
    <?php endif; ?>

     <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="professors-management.php">Professors Management</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="subjects-management.php">Subjects Management</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" ||  $_SESSION['usertype'] == "DEAN") : ?>
       <a href="grades-management.php">Grades Management</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "PROFESSOR") : ?>
       <a href="subjects-grade.php"style = "background-color: white; color: black;">Subject Grades</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php">Student Grades</a>
    <?php endif; ?>

</div>

		<div class="container" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

		<!-- Modal Create-->
  <div class="modal fade" id="modalCreate" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      </div>
      
    </div>
  </div>

		<!-- Modal Update-->
  <div class="modal fade" id="modalUpdate" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      </div>
      
    </div>
  </div>

  <!-- Modal Delete-->
  <div class="modal fade" id="modalDelete" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      </div>
      
    </div>
  </div>

  
</div>


<div class="toast-container">
    <?php  
    function displayCustomToast($type, $message) {
        $bgColor = $type === 'success' ? 'alert-success' : ($type === 'warning' ? 'alert-warning' : 'alert-danger');
        echo "
        <div class='alert $bgColor custom-toast' role='alert'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            <strong>" . ucfirst($type) . ":</strong> $message
        </div>";
    }

    if (isset($_SESSION['created'])) {
        displayCustomToast('success', $_SESSION['created']);
        unset($_SESSION['created']);
    }
    if (isset($_SESSION['updated'])) {
        displayCustomToast('success', $_SESSION['updated']);
        unset($_SESSION['updated']);
    }
    if (isset($_SESSION['deleted'])) {
        displayCustomToast('warning', $_SESSION['deleted']);
        unset($_SESSION['deleted']);
    }
    if (isset($_SESSION['error'])) {
        displayCustomToast('danger', $_SESSION['error']);
        unset($_SESSION['error']);
    }
    ?>
</div>


<div class="container">
        <center>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        Search: <input type="text" name="txtsearch" placeholder="Enter Student#, Last Name, or Subject Code" style="width: 50%;" >
        <input type="submit" name="btnsearch" value="Search" class="btn btn-primary">
        </form>
        </center>
    
</div>



<script>
$(document).ready(function() {
    // Automatically dismiss all toast messages after 5 seconds
    setTimeout(function() {
        $('.custom-toast').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
});
</script>

<footer>
    <p>Arellano University - Jose Rizal Campus</p>
</footer>
</body>





</html>


<?php
require_once "config.php";

$searchTerm = "";
if (isset($_POST['btnsearch']) && !empty($_POST['txtsearch'])) {
    // Get and sanitize the search term
    $searchTerm = mysqli_real_escape_string($link, $_POST['txtsearch']);
}

$sql = "SELECT tblprofessors.Plastname, tblprofessors.Pfirstname, tblprofessors.Psecondname, tblprofessors.Pmiddlename, tblprofessors.ID_number, 
               tblstudents.studentnumber, tblstudents.lastname, tblstudents.firstname, 
               tblstudents.course, tblstudents.yearlevel, tblgrades.grade, tblgrades.subject_code, 
               tblsubjects.unit, tblsubjects.subject_description
        FROM tblprofessors
        LEFT JOIN tblgrades ON tblgrades.subject_code IN 
            (tblprofessors.subject_1, tblprofessors.subject_2, tblprofessors.subject_3, tblprofessors.subject_4, 
             tblprofessors.subject_5, tblprofessors.subject_6, tblprofessors.subject_7, tblprofessors.subject_8)
        LEFT JOIN tblstudents ON tblgrades.studentnumber = tblstudents.studentnumber
        LEFT JOIN tblsubjects ON tblsubjects.subject_code = tblgrades.subject_code
        WHERE tblgrades.ID_number = ?
        GROUP BY tblstudents.studentnumber, tblgrades.subject_code";

// Add search filtering if a term was entered
if (!empty($searchTerm)) {
    $sql .= " AND (tblstudents.studentnumber LIKE ? 
                 OR tblstudents.lastname LIKE ? 
                 OR tblgrades.subject_code LIKE ?)";
}



if ($stmt = mysqli_prepare($link, $sql)) {
    $professorID = $_SESSION['username']; // assuming username is the professor's ID
    if (!empty($searchTerm)) {
        $searchParam = "%$searchTerm%";
        mysqli_stmt_bind_param($stmt, "ssss", $professorID, $searchParam, $searchParam, $searchParam);
    } else {
        mysqli_stmt_bind_param($stmt, "s", $professorID);
    }

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        buildTableWithProfessor($result);
    }
}

?>



<?php

function buildTableWithProfessor($result) {
    if(mysqli_num_rows($result) > 0){
        //create a table using html

$firstrow = true;
        // display
    
    while ($account = mysqli_fetch_array($result)) {
    echo "<form>";

    if ($firstrow) {
        // code...
   
        echo "<center>";
        echo "List of Students";
		    echo "</center>";

        echo"<br> <br>";
        echo "</center>";
        

        echo "<table>";

        //create the header of the table
        echo "<tr>";
        echo "<th>Student#</th><th>Subject Code</th><th>Description</th><th>Unit</th><th>Grade</th><th>Action</th>";
        echo "</tr>";

        $firstrow = false;

    }
    

            if (!empty($account['subject_code']) && !empty($account['subject_description']) && !empty($account['unit'])) {

            echo "<tr>";
            echo "<td>" . $account['studentnumber'] . "</td>";
            echo "<td>" . $account['subject_code'] . "</td>";
            echo "<td>" . $account['subject_description'] . "</td>";
            echo "<td>" . $account['unit'] . "</td>";
            echo "<td>" . $account['grade'] . "</td>";

            
            echo "<td>";

			      echo "<a href = 'add-grade.php?studentnumber=" . $account['studentnumber'] . "&subject_code=" . $account['subject_code'] . "&grade=" . $account['grade'] . "' class = 'btn btn-warning' data-toggle='modal' data-target='#modalUpdate'>Grade</a>";
			      echo "</td>";

            }

            echo "</tr>";
        }

        echo "</table>";

        echo "<br><br><br><br>";
    }

    else{

     echo "<form>";

      echo "<center>";
        echo "List of Students";

        echo"<br> <br>";

        echo "</center>";
        

        echo "<table>";

        //create the header of the table
        echo "<tr>";
        echo "<th>Student#</th><th>Code</th><th>Description</th><th>Unit</th><th>Grade</th>";
        echo "</tr>";

      echo "</form>";
    }

    echo "</form>";

}
?>
