<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Viewing Statistics of a Class (student view) </title>
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


      <h3>Information Page (Student)</h3>
    </table>


      <?php
        # establish connection to cs377 database
        $conn = mysqli_connect("localhost",
                               "cs377", "cs377_s18", "evaluation_msc");
        # make sure no error in connection
        if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit(1);
        }

        # get insID chosen from previous page
        $ins = $_POST['instructor'];
        $ins = str_replace(' ', '', $ins);


        # get the course number from previous page
        $className = $_POST['className'];
        $className = str_replace(' ', '', $className);

        # notice of current class number and instructor ID
        echo "<br>* You have selected to view statistics of $className (instructor ID: $ins)<br><br><br>";


        #--------------------------------------------------------------
        #-----------Agree and Strongly Agree---------------------------
        #--------------------------------------------------------------
        #--------------------------------------------------------------
        echo "<div class='form-group'>";
        echo "<br><br><br><br><h5>Here is the statistics for \"Agree/Disagree\" type questions</h5><br>";
        
        # student view of agree

        $Agree = "SELECT q.qID, question.content, ans.answer, ans.part/q.total*100 as percentage
                  FROM 
                        (select qID, answer, count(*) as part
                        from evaAnswer, (select cID from class where insID = \"$ins\" 
                        and courseName = \"$className\") c
                        where answer = 'Agree' and evaAnswer.cID = c.cID
                        group by qID) ans,
                              
                        (select qID, count(*) as total
                        from evaAnswer, (select cID from class where insID = \"$ins\" 
                        and courseName = \"$className\") c
                              where evaAnswer.cID = c.cID
                              group by qID) q,
                        question
                              
                  WHERE ans.qID = q.qID and question.qID = q.qID
                  GROUP BY q.qID";


        # execute the query
        if (!($agreeResult = mysqli_query($conn, $Agree))) {
           printf("Error: %s\n", mysqli_error($conn));
           exit(1);
        }





        # student view of strongly agree

        $StrAgree = "SELECT q.qID, question.content, ans.answer, ans.part/q.total*100 as percentage
                  FROM 
                        (select qID, answer, count(*) as part
                        from evaAnswer, (select cID from class where insID = \"$ins\" 
                                          and courseName = \"$className\") c
                        where answer = 'Strongly Agree' and evaAnswer.cID = c.cID
                        group by qID) ans,
                              
                        (select qID, count(*) as total
                        from evaAnswer, (select cID from class where insID = \"$ins\" 
                                          and courseName = \"$className\") c
                        where evaAnswer.cID = c.cID
                        group by qID) q,
                        question
                              
                  WHERE ans.qID = q.qID and question.qID = q.qID
                  GROUP BY q.qID ";

        # execute the query
        if (!($strAgreeResult = mysqli_query($conn, $StrAgree))) {
           printf("Error: %s\n", mysqli_error($conn));
           exit(1);
        }




        # create a new paragraph
        print("<p>\n");
        # add the table class extra stuff
        print("<table class=\"table table-striped\">\n");
              # write the contents of the table

              # NOTE: agree results and strongly agree results are in the same table

              $header = false;
              
            if (!mysqli_num_rows($strAgreeResult)==0) {
              while ($row = mysqli_fetch_assoc($strAgreeResult)){
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
              mysqli_free_result($strAgreeResult);
            } else {
              echo '<h6><span style="color:#f00;text-align:center;">'."!!!".'</span>';
              echo "-- No response for \"Strongly Agree\" for all \"Agree/Disagree\" questions</h6><br><br>";
            }

            if (!mysqli_num_rows($agreeResult)==0) {
              while ($rowAgree = mysqli_fetch_assoc($agreeResult)){
                 # print the attribute names once!
                 if (!$header) {
                    $header = true;
                    print("<thead><tr>\n");
                    foreach ($rowAgree as $key => $value) {
                       print "<th>" . $key . "</th>";             // Print attr. name
                    }
                    print("</tr></thead>\n");
                 }
                 print("<tr>\n");     # Start row of HTML table
                 foreach ($rowAgree as $key => $value) {
                    print ("<td>" . $value . "</td>"); # One item in row
                 }
                 print ("</tr>\n");   # End row of HTML table
              }
              mysqli_free_result($agreeResult);
            } else {
              echo '<h6><span style="color:#f00;text-align:center;">'."!!!".'</span>';
              echo "-- No response for \"Agree\" for all \"Agree/Disagree\"  questions</h6><br><br>";
            }


        print("</table>\n");
        print("<p>\n");

        echo "<br><br><br><br></div>";


        #--------------------------------------------------------------
        #---------------1 - 10 Questions -AVG--------------------------
        #--------------------------------------------------------------
        #--------------------------------------------------------------
        echo "<div class='form-group'>";

        echo "<br><br><br><h5>Here is the statistics for \"1-10\" type questions</h5><br>";

        $findAvg = "SELECT q.qID, question.content, avg(answer) as Average
                    FROM question,
                              (select qID, answer
                              from evaAnswer, class
                              where insID = \"$ins\" and courseName = \"$className\"
                              and evaAnswer.cID = class.cID) q
                    WHERE question.type = '1-10\r' and question.qID = q.qID
                    GROUP BY q.qID";

        # execute the query
        if (!($avgResult = mysqli_query($conn, $findAvg))) {
           printf("Error: %s\n", mysqli_error($conn));
           exit(1);
        }


        if (!mysqli_num_rows($avgResult)==0) {
              # create a new paragraph
              print("<p>\n");
              # add the table class extra stuff
              print("<table class=\"table table-striped\">\n");
                    # write the contents of the table
                  $header = false;
                  
                  while ($row = mysqli_fetch_assoc($avgResult)){
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
              mysqli_free_result($avgResult);

              echo"<br><br><br><br></div>";

          } else {
            echo '<h6><span style="color:#f00;text-align:center;">'."!!!".'</span>';
            echo "-- No response for all \"1-10\" questions</h6><br><br>";
          }

        

        #--------------------------------------------------------------
        #------Multiple-choice: percentages for each category----------
        #--------------------------------------------------------------
        #--------------------------------------------------------------
        echo"<div class='form-group'>";
        echo "<br><br><br><h5>Here is the statistics for \"Multiple Choice\" type questions</h5><br>";


        # find multiple choice questions
        $findQuestions = "SELECT qID FROM question WHERE type = 'Multiple Choice\r'";
        # execute the query
        if (!($questionResult = mysqli_query($conn, $findQuestions))) {
           printf("Error: %s\n", mysqli_error($conn));
           exit(1);
        }

        if (!mysqli_num_rows($questionResult)==0) {
            $header = false;
            while ($row = mysqli_fetch_assoc($questionResult)) {
              $qq = $row['qID'];
              $eachQuestionQuery = "SELECT t.qID, question.content, answer, count(*)/t.total*100 as percentage
                                  FROM evaAnswer, question, instructor, class,
                                      (SELECT qID, count(*) as total
                                      FROM evaAnswer, instructor, class
                                      WHERE instructor.insID = \"$ins\"
                                      and instructor.insID = class.insID
                                      and courseName = \"$className\"
                                      and class.cID = evaAnswer.cID
                                      GROUP BY qID) t
                                  WHERE question.qID = $qq
                                  and instructor.insID = \"$ins\"
                                  and instructor.insID = class.insID
                                  and courseName = \"$className\"
                                  and class.cID = evaAnswer.cID
                                  and question.qID = evaAnswer.qID and t.qID = question.qID
                                  GROUP BY answer";
              # execute the query
              if (!($eachQResult = mysqli_query($conn, $eachQuestionQuery))) {
                 printf("Error: %s\n", mysqli_error($conn));
                 exit(1);
              }
              
              # create a new paragraph
              print("<p>\n");
              # add the table class extra stuff
              print("<table class=\"table table-striped\">\n");
              
              # write the contents of the table

              
              while ($rowResult = mysqli_fetch_assoc($eachQResult)){


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

              mysqli_free_result($eachQResult);
            }


          
        } else {
            echo '<h6><span style="color:#f00;text-align:center;">'."!!!".'</span>';
            echo "-- No response for all \"Multiple Choice\" questions</h6><br><br>";
        }

        print("</table>\n");
        print("<p>\n");
        echo"<br><br><br></div>";
        mysqli_free_result($questionResult);



            echo "<form action='studentViewForm.php'>";
            echo"<br><class='form-group'>
            <button type='submit' class='btn btn-primary'>Search data for another course</button><br><br>";
            echo "</form>";
            echo "<br><br><br><br><br><br>";

      ?>
      
    </div>
  </body>
</html>
