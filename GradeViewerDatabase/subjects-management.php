<html>
<title>Subjects management - Arellano University Subject Advising - AUSMS </title>

<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="design.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<header>
	<img src="new-au-logo.png" style="width:100px;height:100px;">
Subjects management - Arellano University Subject Advising - AUSMS 
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
       <a href="subjects-management.php"  style = "background-color: white; color: black;">Subjects Management</a>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "PROFESSOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a href="grades-management.php">Grades Management</a>   
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



       <!-- Modal Update-->
  <div class="modal fade" id="modalUpdate" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      </div>
      
    </div>
  </div>


<div class="modal fade" id="modalCreate" role="dialog" >
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
	<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST" >

		Search: <input type="text" name="txtsearch" placeholder="Enter Subject Code, Subject Description, Unit or Course" style="width: 50%;" >
        <button name="btnsearch" class="btn btn-info">
        	<i class="fas fa-search"></i> Search
        </button>

		<a href="create-subject.php" style="float: right;" class = 'btn btn-primary' data-toggle='modal' data-target='#modalCreate'>Create new subject</a>
		
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

<footer>
	<p>Arellano University - Jose Rizal Campus</p>
</footer>

</body>


</html>

<?php

function buildtable($result) {
	if(mysqli_num_rows($result) > 0){
		//create a table using html

		echo "<table>";

		//create the header of the table
		echo "<tr>";
		echo "<th>Code</th><th>Description</th><th>Unit</th><th>Courses</th><th>Action</th>";
		echo "</tr>";
		// display

		echo "<br>";

		while ($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>" . $row['subject_code'] . "</td>";
			echo "<td>" . $row['subject_description'] . "</td>";
			echo "<td>" . $row['unit'] . "</td>";

			echo "<td>";
			echo "<a href = 'subject-courses.php?subject_code=" . $row['subject_code'] . "' class = 'btn btn-default' >Courses</a>";
			echo "</td>";

			echo "<td>";
			echo "<a href = 'update-subject.php?subject_code=" . $row['subject_code'] . "' class = 'btn btn-success' data-toggle='modal' data-target='#modalUpdate'>Update</a>";
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
	$sql = "SELECT * FROM  tblsubjects WHERE subject_code LIKE ? OR subject_description LIKE ? OR unit LIKE ?  OR course_1 LIKE ? OR course_2 LIKE ? OR course_3 LIKE ? OR course_4 LIKE ? ORDER BY subject_code";
	if ($stmt = mysqli_prepare($link, $sql)) {
		$searchvalue = '%' . $_POST['txtsearch'] . '%';
		mysqli_stmt_bind_param($stmt, "sssssss", $searchvalue, $searchvalue, $searchvalue, $searchvalue, $searchvalue, $searchvalue, $searchvalue);
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
	$sql = "SELECT * FROM tblsubjects ORDER BY subject_code";
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