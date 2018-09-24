<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Submitting your answer...</title>
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
      /*

      ====================================================================================================
      ====================================================================================================
      ====================================================================================================
      ======= NOTE: Since only the valid questions are made into options, no need to check selection 
      ===============questions, and no need to check if the student has input the answer or not;==========
      ====================================================================================================
      ====================== Only need to check the free-ended questions==================================
      ====================================================================================================
      ====================================================================================================
      ====================================================================================================

      */
        # establish connection to cs377 database
        $conn = mysqli_connect("localhost","cs377", "cs377_s18", "evaluation_msc");
        # make sure no error in connection
        if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit(1);
        }

        # get the course name from the form
        $class = $_POST['cc'];
        $class = str_replace(' ', '', $class);
        $sID = $_POST['ss'];
        $sID = str_replace(' ', '', $sID);

        if ($class == null || $sID == null) {
          echo '<span style="color:#f00;text-align:center;">';
          echo "<br><br><br><br>You have to confirm (by checking) your student ID and your selected course number!!!";
          echo '</span>';

          # submit and reset buttom
          echo "<form action='idForm.php' method='post'>";
          echo "<br><br><br><br><div class='form-group'>
                    <button type='submit' class='btn btn-primary'>Go back</button>
                    </div>";

            # form ending
          echo "</form>";

        } else {

              # show the student's name and the class number again

              echo "<h5>You have completed evaluation for $class as student $sID</h5><br><br><br><br>";
              echo "<h6> Note: you cannot re-submit your evaluation form </h6><br><br>";
              $searchForAll = "SELECT DISTINCT qID FROM evaAnswer WHERE cID = \"$class\"";

              if (!($qList = mysqli_query($conn, $searchForAll))) {

                 printf("Error: %s\n", mysqli_error($conn));
                 exit(1);
              }

              echo "<div class='container'>";
              echo "<br><br>Your answer is the following: <br><br>";
              


              while ($rowPrint = mysqli_fetch_assoc($qList)) {
                  $qq = $rowPrint['qID'];
                  $ans = $_POST[$qq];

      /*
              ====================================================================================================
              ====================================================================================================
              #since the open-ended questions are the only ones who may have empty answer, we only need to check 
              for each question, if the answer is empty, we don't include the answer in the database
              ====================================================================================================
              ====================================================================================================
      */
                  if (!$ans == null) {
                    # print answer
                    echo "For question $qq, your answer is $ans  ";

                    # insert answer to database
                    $sql = "INSERT INTO evaAnswer (cID, sID, answer, qID)
                    VALUES (\"$class\", \"$sID\", \"$ans\", $qq)";

                    if ($conn->query($sql) === TRUE) {
                        echo "        (Submitted ) <br><br>";
                    } else {
                      echo "<br><br>";
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }

                  } else {
                    echo "For open-ended question $qq, you did not submit the answer<br><br>";
                  }
              }
              echo "</div>";




                  echo "<form action='idForm.php'>";
                  echo"<br><class='form-group'>
                  <button type='submit' class='btn btn-primary'> Quit </button><br><br>";
                  echo "</form>";
                  echo "<br><br><br><br><br><br>";
            }

        mysqli_close($conn);
       ?>
    
    </div>   
  </body>
</html>
