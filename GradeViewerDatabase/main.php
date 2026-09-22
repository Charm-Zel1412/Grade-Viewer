<html>
<title>Home - Arellano University Subject Advising - AUSMS </title>

<meta charset="utf-8">
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
	}

	else{
		//redirect to the login page
		header("location: login.php");
	}
	?>

			<div class="topnav" id="myTopnav">

			<a href="main.php" style = "background-color: white; color: black;">Home</a>

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

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" ||  $_SESSION['usertype'] == "DEAN") : ?>
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

      	<?php  

  	if (isset($_SESSION['error'])) {
  		?> <br> <div class="alert alert-warning" role="alert"> <center>
         <?php echo $_SESSION['error'];?> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </center>
         </div> 
    
    <?php
    unset($_SESSION['error']);

  	}

  	?>
      	
      </div>

      </div>

		<div class="container" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

		<!-- Modal Update-->
  <div class="modal fade" id="modalReset" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      </div>
      
    </div>
  </div>

  <?php  

  	if (isset($_SESSION['reseted'])) {
  		?> <br> <div class="alert alert-success" role="alert"> <center>
         <?php echo $_SESSION['reseted'];?> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </center>
         </div> 
    
    <?php
    unset($_SESSION['reseted']);

  	}

  	?>


  	<?php  

  	if (isset($_SESSION['error'])) {
  		?> <br> <div class="alert alert-warning" role="alert"> <center>
         <?php echo $_SESSION['error'];?> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </center>
         </div> 
    
    <?php
    unset($_SESSION['error']);

  	}

  	?>
  
</div>

	<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

	</form>



	<br>
	<br>
	<br>

	<?php 

	if ($_SESSION['usertype'] == "STUDENT") { 
     echo "<h1>Welcome, <font color ='red'>" . $_SESSION['firstname'] . ", " . $_SESSION['lastname'] . "</font></h1>";
		 echo "<h4> Student Number: " . $_SESSION['username'] . "</h4>"; 
		}

  else if ($_SESSION['usertype'] == "PROFESSOR") { 
     echo "<h1>Welcome, <font color ='red'>" . $_SESSION['firstname'] . ", " . $_SESSION['lastname'] . "</font></h1>";
     echo "<h4> ID#: " . $_SESSION['username'] . "</h4>"; 
    }

  else{ 
    echo "<h1>Welcome, <font color ='red'>" . $_SESSION['username'] . "</font></h1>";
    }?> 

    <?php

  echo "<h4>Account Type: " . $_SESSION['usertype'] . "</h4>"; ?> <br> <br>

<center>
  


</center>


	<script>
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
</script>

</body>

<footer>
	<p>Arellano University - Jose Rizal Campus</p>
</footer>

</html>


