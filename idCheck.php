<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Checking your sID...</title>
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
      <?php
        # establish connection to cs377 database
        $conn = mysqli_connect("localhost","cs377", "cs377_s18", "evaluation_msc");
        # make sure no error in connection
        if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit(1);
        }
        # check student's ID
        $your_id = "".$_POST['sID'];



        $IDcheck = "SELECT sID FROM student WHERE sID = \"$your_id\"";
        #print("<pre><code>");
        #print($IDcheck); # echo the query
        #print("</code></pre>");

        # execute the IDcheck query
        $resultIDcheck = mysqli_query($conn, $IDcheck);
        if (!$resultIDcheck){
          printf("Error: %s\n", mysqli_error($conn));
          exit(1);
        }
        $resultIDcheckrow = mysqli_fetch_assoc($resultIDcheck);



        if (mysqli_num_rows($resultIDcheck) == 0){
          if(empty($your_id)) {
            echo "Please input your student ID ";
            echo '<span style="color:#f00;text-align:center;">'."(student ID is required)".'</span>';
            echo "<br><br>";
            echo "<form action='idForm.php'>";
            echo"<div class='form-group'>
                 <button type='submit' class='btn btn-primary'>Re-enter your ID</button>
            </div>";
            echo "</form>";
          } else {
            echo "Student ID ";
            echo '<span style="color:#f00;text-align:center;">'.$your_id.'</span>';
            echo " is not in the system. Try with a different ID";
            echo "<br><br>";
            echo "<form action='idForm.php'>";
            echo"<div class='form-group'>
                 <button type='submit' class='btn btn-primary'>Re-enter your ID</button>
            </div>";            
            echo "</form>";
          }
        } else {
          printf("Welcome to the evaluation system! (ID: $your_id)<br><br>");


          $courseCheck = "SELECT cID FROM enroll_in 
          WHERE sID = \"$your_id\"
          AND cID NOT IN 
          (SELECT DISTINCT cID 
          FROM evaAnswer
          WHERE sID = \"$your_id\")";
          $resultcourseCheck = mysqli_query($conn, $courseCheck);

          #check for query running
          if (!$resultcourseCheck){
            printf("Error: %s\n", mysqli_error($conn));
            exit(1);
          }

          if (mysqli_num_rows($resultcourseCheck) == 0) {
            echo "You have completed all evaluations for the classes you enrolled in.";
          } else {

            # select courses(enrolled ones) to evalute
            echo "<form action='inputForm.php' method='POST'>";
            
            echo "<label for='class' >You have enrolled in the following course.</label><br><br>";
            echo "<label for='class' >Select a course number to evaluate*:</label><br><br>";
            echo "<select name='class'>";
            while ($row = mysqli_fetch_assoc($resultcourseCheck)) {
              echo "<option value='  " . $row['cID'] . "  '>" . $row['cID'] . "</option>";
            }
            echo "</select><br><br>";

            echo "Confirm your student ID <input type='checkbox' name='sID' value= '" . $your_id  . "'><br><br>";
            echo"<div class='form-group'>
                      <button type='submit' class='btn btn-primary'>Submit</button>
                      
            </div>";
            echo "</form>";
          }

          mysqli_free_result($resultIDcheck);
          mysqli_free_result($resultcourseCheck);
        }

        
        mysqli_close($conn);
      ?>
    
    </div>   
  </body>
</html>
