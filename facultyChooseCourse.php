<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Choose a course of yours to see the history data (faculty)</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>
  <body>


  <br>
  <br>
  <br>
  <br>  
  <br>
    <div class="container">

   
      <h4>Here are courses of yours</h4>
      <h4>Please choose one to check for historical data...</h4>

      <?php

        

        # establish connection to cs377 database
        $conn = mysqli_connect("localhost",
                               "cs377", "cs377_s18", "evaluation_msc");
        # make sure no error in connection
        if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit(1);
        }

        echo "<br>";
        echo "<br>";

        # get the course name from the form
        $instructor = $_POST['instructor'];
        $instructor = str_replace(' ', '', $instructor);
        if(empty($instructor)) {
          echo "Please input your instructor ID ";
          echo '<span style="color:#f00;text-align:center;">'."(your ID is required)".'</span>';
          echo "<br><br>";
          echo "<form action='facultyForm.php'>";
          echo"<div class='form-group'>
               <button type='submit' class='btn btn-primary'>Re-enter your ID</button>
          </div>";
          echo "</form>";
        } else {
            $fIDcheck = "SELECT insID, fname, lname FROM instructor WHERE insID = \"$instructor\"";

            $result_fIDcheck = mysqli_query($conn, $fIDcheck);
            if (!$result_fIDcheck){
              printf("Error: %s\n", mysqli_error($conn));
              exit(1);
            }
            
            if (mysqli_num_rows($result_fIDcheck) == 0) {
              printf("Sorry you have no course history in the system");
              echo "<br><br>";
              echo "<form action='facultyForm.php'>";
              echo"<div class='form-group'>
                 <button type='submit' class='btn btn-primary'>Try another ID</button>
              </div>";
              echo "</form>";
            } else {
              $row_fIDcheck = mysqli_fetch_assoc($result_fIDcheck);
              printf("Welcome to the evaluation system Dr. ".$row_fIDcheck['fname']." ".$row_fIDcheck['lname']."<br><br>");
              echo "<br>";
              echo "<br>";
              echo "<br>";
              echo "<br>";


              $courseSearch = "SELECT DISTINCT cID, courseName, semester, year FROM class WHERE insID = \"$instructor\"";
              $courseLIST = mysqli_query($conn, $courseSearch);
              if (!$courseLIST) {
                 printf("Error: %s\n", mysqli_error($conn));
                 exit(1);
              }
              echo "<form action='viewFaculty.php' method='POST'>";
              echo "<label for='course' >Select an course: </label><br><br>";
              echo "<select name='course'>";
              while ($row = mysqli_fetch_assoc($courseLIST)) {
                echo "<option value='  " .  $row['cID'] .  "  '>" . 
                "[".$row['cID'] ."] ".$row['courseName'] .  
                " (". $row['semester'] .", " . $row['year'] .") ".
                "</option>";
              }
              echo "</select><br><br>";
              echo"<div class='form-group'>
                 <button type='submit' class='btn btn-primary'> Confirm </button>
              </div>";
              # pass the variable to next place
              echo "</form>";

            }
            mysqli_free_result($result_fIDcheck);
        }

        

      mysqli_close($conn);
        
      ?>

      
       
    </div>
  </body>
</html>
