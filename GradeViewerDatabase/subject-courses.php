<html>
<title>Assign management - Arellano University Subject Advising - AUSMS </title>

<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="design.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<header>
    <img src="new-au-logo.png" style="width:100px;height:100px;">
  Assign management - Arellano University Subject Advising - AUSMS 
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
        header("location: main.php");
        exit();
    }
    ?>

        <div class="topnav" id="myTopnav">

            <a href="subjects-management.php" style="float: right;" class="active">Return</a>

        <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "PROFESSOR" || $_SESSION['usertype'] == "DEAN") : ?>
       <a style = "background-color: white; color: black;">Subject Courses</a>
    <?php endif; ?>

        <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "DEAN") : ?>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "PROFESSOR" || $_SESSION['usertype'] == "DEAN") : ?>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "PROFESSOR" || $_SESSION['usertype'] == "DEAN") : ?>
    <?php endif; ?>

    <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "PROFESSOR" || $_SESSION['usertype'] == "DEAN") : ?>
    <?php endif; ?>

     <?php if ($_SESSION['usertype'] == "ADMINISTRATOR" || $_SESSION['usertype'] == "PROFESSOR" || $_SESSION['usertype'] == "DEAN") : ?> 
    <?php endif; ?>

     <?php if ($_SESSION['usertype'] == "STUDENT") : ?>
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

require_once "config.php";

 
if (isset($_GET['subject_code']) && !empty(trim($_GET['subject_code']))) {
        $sql = "SELECT * FROM  tblsubjects WHERE subject_code = ?";

         if ($stmt = mysqli_prepare($link, $sql)) {
        $searchvalue = $_GET['subject_code'];
        mysqli_stmt_bind_param($stmt, "s", $searchvalue);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            buildtable($result);
          }
        }

    }

?>



<?php

function buildtable($result) {
    if(mysqli_num_rows($result) > 0){
        //create a table using html

$firstrow = true;
        // display
    
    while ($account = mysqli_fetch_array($result)) {
    echo "<form>";

    if ($firstrow) {
        // code...
    

        echo "Subject Code: <b>" . $account['subject_code'] . "</b><br>";

        echo "Subject Description: <b>" . $account['subject_description'] . "</b><br>";

        echo "<center>";
        echo "List of Assigned Courses";

        echo"<a href='assign-courses.php?subject_code=" . $account['subject_code']. "' style='float: right;' class = 'btn btn-success' data-toggle='modal' data-target='#modalCreate'>Assigned Courses</a> <br> <br> <br>";
            echo "</center>";

        echo"<br> <br>";
        echo "</center>";
        

        echo "<table>";

        //create the header of the table
        echo "<tr>";
        echo "<th>1st Course</th><th>2nd Course</th><th>3rd Course</th><th>4th Course</th><th>5th Course</th><th>6th Course</th><th>7th Course</th><th>8th Course</th>";
        echo "</tr>";

        $firstrow = false;

 
    

            if (!empty($account['course_1'])) {

            echo "<tr>";
            echo "<td>" . $account['course_1'] . "</td>";
            echo "<td>" . $account['course_2'] . "</td>";
            echo "<td>" . $account['course_3'] . "</td>";
            echo "<td>" . $account['course_4'] . "</td>";
            echo "<td>" . $account['course_5'] . "</td>";
            echo "<td>" . $account['course_6'] . "</td>";
            echo "<td>" . $account['course_7'] . "</td>";
            echo "<td>" . $account['course_8'] . "</td>";
            
            
   }
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
