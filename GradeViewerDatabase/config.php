 <?php

//define the database connection
define('DB_SERVER', 'localhost:3306');
define('DB_USERNAME', 'HazelCharm');
define('DB_PASSWORD', 'Reginaldo');
define('DB_NAME', 'grade_viewer');

//attempt to connect
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

//check of connection is unsuccessful
if($link === false) {
	die ("ERROR: Could not connect." . mysqli_connect_error());
}

//set the timezone
date_default_timezone_set('Asia/Manila');

?>