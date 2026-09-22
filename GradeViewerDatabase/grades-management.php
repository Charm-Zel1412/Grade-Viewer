<html>
<title>Grades management - Arellano University Subject Advising - AUSMS </title>

<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="design.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<header>
	<img src="new-au-logo.png" style="width:100px;height:100px;">
Grades management - Arellano University Subject Advising - AUSMS 
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
	if($_SESSION['usertype'] == "ADMINISTRATOR"){
	}

	else if($_SESSION['usertype'] == "PROFESSOR"){
	}
  
	else if($_SESSION['usertype'] == "DEAN"){
	}

	else{
		//redirect to the login page
		$_SESSION['error'] = "User does not have the right to access this management/module!";
		header("location: index.php");
		exit();
	}
	?>

	<div class="topnav" id="myTopnav">

		<a href="main.php">Home</a>

			<a href="logout.php" style="float: right;" class="active">Log Out</a>

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

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="grades-management.php" style = "background-color: white; color: black;">Grades Management</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "PROFESSOR") : ?>
       <a href="subjects-grade.php">Subject Grades</a>   
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
       <a href="student-grades.php">Student Grades</a>
    <?php endif; ?>

</div>

		<div class="container" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

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
	<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

		Search: <input type="text" name="txtsearch" placeholder="Enter Student#, Lastname, Firstname, Yearlevel or Course" style="width: 50%;" >
        <button name="btnsearch" class="btn btn-info">
        	<i class="fas fa-search"></i> Search
        </button>

	</form>
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

</body>

<footer>
	<p>Arellano University - Jose Rizal Campus</p>
</footer>

</html>

<?php

function buildtable($result) {
	if(mysqli_num_rows($result) > 0){
		//create a table using html

		echo "<table>";

		//create the header of the table
		echo "<tr>";
		echo "<th>Student #</th><th>Last name</th><th>First name</th><th>Second name</th><th>Middle name</th><th>Course</th><th>Year level</th><th>Grades</th>";
		echo "</tr>";
		// display

		echo "<br>";

		while ($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>" . $row['studentnumber'] . "</td>";
			echo "<td>" . $row['lastname'] . "</td>";
			echo "<td>" . $row['firstname'] . "</td>";
			echo "<td>" . $row['secondname'] . "</td>";
			echo "<td>" . $row['middlename'] . "</td>";
			echo "<td>" . $row['course'] . "</td>";
			echo "<td>" . $row['yearlevel'] . "</td>";

			echo "<td>";
			echo "<a href = 'manage-grade.php?studentnumber=" . $row['studentnumber']. "' class = 'btn btn-warning'>Grade</a>";
			echo "</td>";

			echo "</tr>";
		}

		echo "</table>";

		echo "<br><br><br><br>";
	}
	else{
		echo "No record/s found";
	}
}


//display the data on the table

require_once "config.php";

//search
if (isset($_POST['btnsearch'])) {
	$sql = "SELECT * FROM tblstudents WHERE studentnumber LIKE ? OR firstname LIKE ? OR lastname LIKE ? OR course LIKE ? OR yearlevel LIKE ? ORDER BY studentnumber";
	if ($stmt = mysqli_prepare($link, $sql)) {
		$searchvalue = '%' . $_POST['txtsearch'] . '%';
		mysqli_stmt_bind_param($stmt, "sssss", $searchvalue, $searchvalue, $searchvalue, $searchvalue, $searchvalue);
		if (mysqli_stmt_execute($stmt)) {
			$result = mysqli_stmt_get_result($stmt);
			buildtable($result);
		}
	}
	else{
		echo "Error on search";
	}
}

else{ // load the data
	$sql = "SELECT * FROM tblstudents ORDER BY studentnumber";
	if ($stmt = mysqli_prepare($link, $sql)) {
		if (mysqli_stmt_execute($stmt)) {
			$result = mysqli_stmt_get_result($stmt);
			buildtable($result);
		}
	}
	else{
		echo "Error on accounts load";
	}
}

?>