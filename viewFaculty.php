<!DOCTYPE html>
<html lang="en">
  <head>
    <title>View Class Statistics (Faculty View)</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
      div {
        border: double;}
      body { width: 974px; margin: 0 auto; }

          
  </style>
  </head>
  <body>
      <br>
      <br>
      <br>
      <table class="table table-striped">


      <h3>Information Page (Faculty)</h3>
    </table>


      <?php
        #include('chooseProfessor.php');
        # establish connection to cs377 database
        $conn = mysqli_connect("localhost",
                               "cs377", "cs377_s18", "evaluation_msc");
        # make sure no error in connection
        if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit(1);
        }

        $course = $_POST['course']; #cID
        $course = str_replace(' ', '', $course);

        echo "<br>* You have selected to view statistics of $course<br><br><br>";






        #--------------------------------------------------------------
        #-----------Agree and Strongly Agree---------------------------
        #--------------------------------------------------------------
        #--------------------------------------------------------------
        echo "<div class='form-group'>";
        echo "<br><br><br><br><h5>Here is the statistics for \"Agree/Disagree\" type questions</h5><br>";
        # faculty view of answers for Agree/Disagree

        $ADquery = "SELECT question.qID, question.content,
                    SUM(CASE WHEN answer = 'Strongly Agree' THEN 1 ELSE 0 END) AS Strongly_Agree, 
                    SUM(CASE WHEN answer = 'Agree' THEN 1 ELSE 0 END) AS Agree, 
                    SUM(CASE WHEN answer = 'Neutral' THEN 1 ELSE 0 END) AS Neutral, 
                    SUM(CASE WHEN answer = 'Disagree' THEN 1 ELSE 0 END) AS Disagree, 
                    SUM(CASE WHEN answer = 'Strongly Disagree' THEN 1 ELSE 0 END) AS Strongly_Disagree
                    FROM evaAnswer, question
                    WHERE cID = $course
                    AND question.qID = evaAnswer.qID
                    AND question.type = 'Agree/Disagree\r'
                    GROUP BY qID";


        #print("<pre><code>");
        #print($Agree); # echo the query
        #print("</code></pre>");

        # execute the query
        $ADresult = mysqli_query($conn, $ADquery);
        if (! $ADresult ) {
           printf("Error: %s\n", mysqli_error($conn));
           exit(1);
        }



        # create a new paragraph
        print("<p>\n");
        # add the table class extra stuff
        print("<table class=\"table table-striped\">\n");
              # write the contents of the table
              $header = false;

              
              
            if (!mysqli_num_rows($ADresult)==0) {
              while ($row = mysqli_fetch_assoc($ADresult)){
                 # print the attribute names once!
                 if (!$header) {
                    $header = true;
                    print("<thead><tr>\n");
                    foreach ($row as $key => $value) {
                       print "<th>" . $key . "</th>";             // Print attr. name
                    }
                    print("</tr></thead>\n");
                 }
                 print("<tr>\n");     # Start row of HTML table
                 foreach ($row as $key => $value) {
                    print ("<td>" . $value . "</td>"); # One item in row
                 }
                 print ("</tr>\n");   # End row of HTML table
              }
              
            } else {
              echo '<h6><span style="color:#f00;text-align:center;">'."!!!".'</span>';
              echo "-- No \"Agree/Disagree\" questions answered for this course</h6><br><br></div>";
            }
            mysqli_free_result($ADresult);

        print("</table>\n");
        print("<p>\n");

        echo "<br><br><br><br></div>";







        #--------------------------------------------------------------
        #---------------1 - 10 ----------------------------------------
        #--------------------------------------------------------------
        #--------------------------------------------------------------
        echo "<div class='form-group'>";

        echo "<br><br><br><h5>Here is the statistics for \"1-10\" type questions</h5><br>";

        $stats = "SELECT question.qID as qID, content, count(*),
                    SUM(CASE WHEN answer = '1' THEN 1 ELSE 0 END) AS '1', 
                    SUM(CASE WHEN answer = '2' THEN 1 ELSE 0 END) AS '2', 
                    SUM(CASE WHEN answer = '3' THEN 1 ELSE 0 END) AS '3', 
                    SUM(CASE WHEN answer = '4' THEN 1 ELSE 0 END) AS '4', 
                    SUM(CASE WHEN answer = '5' THEN 1 ELSE 0 END) AS '5',
                    SUM(CASE WHEN answer = '6' THEN 1 ELSE 0 END) AS '6', 
                    SUM(CASE WHEN answer = '7' THEN 1 ELSE 0 END) AS '7', 
                    SUM(CASE WHEN answer = '8' THEN 1 ELSE 0 END) AS '8', 
                    SUM(CASE WHEN answer = '9' THEN 1 ELSE 0 END) AS '9', 
                    SUM(CASE WHEN answer = '10' THEN 1 ELSE 0 END) AS '10'
                    FROM evaAnswer, question
                    WHERE question.qID = evaAnswer.qID
                    AND cID = $course
                    AND question.type = '1-10\r'
                    GROUP BY question.qID";

        # execute the query
        if (!($statsResult = mysqli_query($conn, $stats))) {
           printf("Error: %s\n", mysqli_error($conn));
           exit(1);
        }




        if (!mysqli_num_rows($statsResult)==0) {
              # create a new paragraph
              print("<p>\n");
              # add the table class extra stuff
              print("<table class=\"table table-striped\">\n");
                    # write the contents of the table
                  $header = false;
                  
                  while ($row = mysqli_fetch_assoc($statsResult)){
                     # print the attribute names once!
                     if (!$header) {
                        $header = true;
                        print("<thead><tr>\n");
                        foreach ($row as $key => $value) {
                           print "<th>" . $key . "</th>";             // Print attr. name
                        }
                        print("</tr></thead>\n");
                     }
                     print("<tr>\n");     # Start row of HTML table
                     foreach ($row as $key => $value) {
                        print ("<td>" . $value . "</td>"); # One item in row
                     }
                     print ("</tr>\n");   # End row of HTML table
                  }

              print("</table>\n");
              print("<p>\n");
              mysqli_free_result($statsResult);

              echo"<br><br><br><br></div>";

          } else {
            echo '<h6><span style="color:#f00;text-align:center;">'."!!!".'</span>';
            echo "-- No response for all \"1-10\" questions</h6><br><br></div>";
          }

 
        #--------------------------------------------------------------
        #------Multiple-choice: counts for each category---------------
        #--------------------------------------------------------------
        #--------------------------------------------------------------
        echo "<div class='form-group'>";
        echo "<br><br><br><h5>Here is the statistics for \"Multiple Choice\" type questions</h5><br>";



        echo "<h6>Counts for each \"Multiple Choice\" type question answered for this class</h6>";
        echo "<br><br>Note: If some choices do not appear in the following table, it means it has zero counts</h6><br>";
        echo "A complete list of all possible choices for each question is attached below this table</h6><br><br><br>";

        # find multiple choice questions
        $findQuestions = "SELECT DISTINCT question.qID FROM question, evaAnswer 
                          WHERE type = 'Multiple Choice\r' AND question.qID = evaAnswer.qID
                          AND evaAnswer.cID = \"$course\"";
        # execute the query
        if (!($questionResult = mysqli_query($conn, $findQuestions))) {
           printf("Error: %s\n", mysqli_error($conn));
           exit(1);
        }

        if (!mysqli_num_rows($questionResult)==0) {

                  
                  $header = false;
                  # create a new paragraph
                  print("<p>\n");
                  # add the table class extra stuff
                  print("<table class=\"table table-striped\">\n");
                  while ($row = mysqli_fetch_assoc($questionResult)) {
                    $qq = $row['qID'];
                    $singleQuesQuery = "SELECT question.qID, question.content, answer, count(*) as counts
                                        FROM evaAnswer, question
                                        WHERE question.qID = evaAnswer.qID and cID = \"$course\" and question.qID = $qq
                                        GROUP BY answer";
                    # execute the query
                    if (!($singleQResult = mysqli_query($conn, $singleQuesQuery))) {
                       printf("Error: %s\n", mysqli_error($conn));
                       exit(1);
                    }
                    
                    # write the contents of the table
                    
                    while ($rowResult = mysqli_fetch_assoc($singleQResult)){
                       # print the attribute names once!

                       if (!$header) {
                          $header = true;
                          print("<thead><tr>\n");
                          foreach ($rowResult as $key => $value) {
                             print "<th>" . $key . "</th>";             // Print attr. name
                          }
                          print("</tr></thead>\n");
                       }

                       print("<tr>\n");     # Start row of HTML table

                       foreach ($rowResult as $key => $value) {
                          print ("<td>" . $value . "</td>"); # One item in row
                       }
                       print ("</tr>\n");   # End row of HTML table
                    }

                    mysqli_free_result($singleQResult);
          
                    }
        } else {
            echo '<br><br><br><br><h6><span style="color:#f00;text-align:center;">'."!!!".'</span>';
            echo "-- No response for all \"Multiple Choice\" questions</h6><br><br>";
        }

        print("</table>\n");
        print("<p>\n");
        echo"<br><br><br></div>";
        mysqli_free_result($questionResult);

        
        
        echo "<br><br><br><h6>List of all possible answers for each \"Multiple Choice\" type question</h6>";
        echo "<pre>";


        # display all multiple question answers
        $displayAnswer = "SELECT question.qID, question.content, evaAnswer.answer as choice
                          FROM question, evaAnswer
                          WHERE type = 'Multiple Choice\r' and question.qID = evaAnswer.qID
                          GROUP BY qID, evaAnswer.answer";

        # execute the query
        if (!($displayResult = mysqli_query($conn, $displayAnswer))) {
           printf("Error: %s\n", mysqli_error($conn));
           exit(1);
        } else {
          print("<p>\n");
          # add the table class extra stuff
          print("<table class=\"table table-striped\">\n");
          while ($row = mysqli_fetch_assoc($displayResult)){
                 # print the attribute names once!
                 if (!$header) {
                    $header = true;
                    print("<thead><tr>\n");
                    foreach ($row as $key => $value) {
                       print "<th>" . $key . "</th>";             // Print attr. name
                    }
                    print("</tr></thead>\n");
                 }
                 print("<tr>\n");     # Start row of HTML table
                 foreach ($row as $key => $value) {
                    print ("<td>" . $value . "</td>"); # One item in row
                 }
                 print ("</tr>\n");   # End row of HTML table
          }
          print("</table>\n");
          print("<p>\n");
        }

        echo "</pre>";



            echo "<form action='facultyForm.php'>";
            echo"<class='form-group'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;
                <button type='submit' class='btn btn-primary'> Restart </button>
                <button type='submit' class='btn btn-primary'> Quit </button>";
            echo "</form>";
            echo "<br><br><br><br><br><br>";

      ?>
      
    </div>
  </body>
</html>
