<?php

if (isset($_POST['btnlogin'])) {
	//require the config file
	require_once "config.php";
	
	//build template fot the login sql statement
	$sql = "SELECT tblaccounts.*FROM tblaccounts LEFT JOIN tblstudents ON tblstudents.studentnumber = tblaccounts.username WHERE tblaccounts.username = ? AND tblaccounts.password = ? AND tblaccounts.status = 'ACTIVE'";

	//check if the sql will run on the connection by preparing the statement
	if ($stmt = mysqli_prepare($link,$sql)) {
		//bind the data from the login page
		mysqli_stmt_bind_param($stmt,"ss", $_POST['txtusername'], $_POST['txtpassword']);
		//check if the statement will execute
		if (mysqli_stmt_execute($stmt)) {
			//get the result of the executing the statement
			$result = mysqli_stmt_get_result($stmt);
			//check if there is data in the result
			if (mysqli_num_rows($result) > 0) {
				
				//fetch the result into an array
				$account = mysqli_fetch_array($result, MYSQLI_ASSOC);
				//create session
				session_start();

				//record session

				$_SESSION['username'] = $_POST['txtusername'];
				$_SESSION['usertype'] = $account['usertype'];
				$_SESSION['firstname'] = $account['firstname'];
				$_SESSION['lastname'] = $account['lastname'];
				$_SESSION['studentnumber'] = $account['studentnumber'];
				$_SESSION['password'] = $account['password'];

				//redirect
						header("location: main.php");
			}
			else {
				$_SESSION['error'] = "<font color ='red'>Incorrect login details or account is inactive.</font>";
			}
		}
		else{
			$_SESSION['error'] = "Error on the login statement";
		}
	}
}

?>

<html>
<title>Login Page - Arellano University Subject Advising - AUSHS</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
	
	html{
		height: 100%;
		width: 100%;
		background-color: lightcyan;
	}

	header{
		padding: 10px;
		text-align: center;
		background-color: blue;
		font-size: 30px;
		color: white;
	}

	body{
		margin: 0;
    padding: 0;
		font-family: times-new-roman;
	}

	form{
		
		font-size: 20px;
		font-style: oblique;
		display: inline-block;
		border: 1px solid black;
    box-sizing: border-box;
    margin: 8px 0;
    padding-right: 50px;
		padding-bottom: 20px;
		padding-left: 50px;
		background-color: gold;
		border-radius: 25px;

	}

	footer{
		position: fixed;
		width: 100%;
        left: 0;
        bottom: 0;
		color: white;
		padding: 3px;
		text-align: left;
		background-color: red;
		font-size: 10px;
		display: block;
	}


		.topnav {
  overflow: hidden;
  background-color: yellow;
}

.topnav a {
  float: left;
  display: block;
  color: black;
  text-align: center;
  padding: 10px 16px;
  text-decoration: none;
  font-size: 17px;
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

<header>
	<img src="new-au-logo.png" style="width:100px;height:100px;">
	Arellano University Grade Viewer - AUSHS
</header>

<body>


	<div class="topnav" id="myTopnav">

		<a style="text-align: center; float: none; left: 50%;">- Log In -</a>

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
    if (isset($_SESSION['error'])) {
        displayCustomToast('Error', $_SESSION['error']);
        unset($_SESSION['error']);
    }
    ?>
</div>


<center style = "padding-top: 30px;">

<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">
		
		<br><br>
		<b>ID #: </b> <br><input type = "text" name = "txtusername" required><br><br>
		<b>Password: </b> <br><input type = "password"  id="password" name = "txtpassword" required>
		<span class="password-toggle-icon"><i class="fas fa-eye"></i></span><br><br>
	
		<input type = "submit" name = "btnlogin" value = "Log In" class = 'btn btn-default' style="background-color: green; color: white;"><br>
	</form>	

</center>

<script src="password-script.js"></script>

<!-- Toast Initialization Script -->
<!-- Custom Script for Auto-Hide Toasts -->
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