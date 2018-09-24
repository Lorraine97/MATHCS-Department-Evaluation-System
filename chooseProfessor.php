<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Choose a course to see the history data (student)</title>
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

   
      <h4>Here are the available professors.</h4>
      <h5>Please choose one to proceed...</h5>

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
        $classSelected = $_POST['getCourseName'];
        $classSelected = str_replace(' ', '', $classSelected);



        if(empty($classSelected)) {
          printf("Please input the course name");
          exit(1);
        }

        echo "You entered: ". $_POST['getCourseName'];

        echo "<br>";
        echo "<br>";


        # make sure no error in connection
        if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit(1);
        }
        $insSearch = "SELECT DISTINCT instructor.insID, fname, lname FROM instructor, class 
                    WHERE class.courseName = \"$classSelected\"and instructor.insID = class.insID";
        $insLIST = mysqli_query($conn, $insSearch);
        if (!$insLIST) {
           printf("Error: %s\n", mysqli_error($conn));
           exit(1);
        }


        if (mysqli_num_rows($insLIST) == 0) {
          if(!empty($classSelected)) {
            echo "Course number  ";
            echo '<span style="color:#f00;text-align:center;">'.$classSelected.'</span>';
            echo " is not offered or has not been evaluated yet. Try with a different course";
            echo "<br><br>";
            echo "<form action='studentViewForm.php'>";
            echo"<br><class='form-group'>
            <button type='submit' class='btn btn-primary'>Re-enter course number</button><br><br>";
            echo "</form>";
          }
        } else {

        echo "<form action='viewStudent.php' method='POST'>";
        echo "<label for='instructor' >Select an instructor* for ". $classSelected.":</label><br><br>";
        echo "<select name='instructor'>";
        while ($row = mysqli_fetch_assoc($insLIST)) {
          echo "<option value='  " .  $row['insID'] .  "  '>" . 'Dr. '. $row['fname'] . " ". $row['lname'] ."</option>";
        }
        echo "</select><br><br>";
        echo "<div class='form-group'>
                    <button type='submit' class='btn btn-primary'>Select this professor</button>
                    </div>";
        # pass the variable to next place
        echo '<input type="hidden" name="className" value="'.htmlentities($classSelected).'">';
        echo "</form>";

        mysqli_free_result($insLIST);
      }

      mysqli_close($conn);
        
      ?>

      
       
    </div>
  </body>
</html>
