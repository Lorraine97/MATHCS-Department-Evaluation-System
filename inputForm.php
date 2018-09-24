<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Evaluation Check</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">

      <h3><br><br>Evaluation Form</h3>
    </div>
    <div class="container">

      <?php

        # establish connection to cs377 database
        $conn = mysqli_connect("localhost",
                               "cs377", "cs377_s18", "evaluation_msc");
        # make sure no error in connection
        if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit(1);
        }

        # get the course name from the form
        $class = $_POST['class'];
        $class = str_replace(' ', '', $class);
        $sID = $_POST['sID'];
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



                    echo "------ You have chosen to evaluate $class ------<br><br><br>";

                    echo "***Note: You will need to answer every question (except for the Open-Ended questions)by selecting one option for each<br>";


                    # Check if the class' evaluation form is empty (no one has evaluated before)

                    # !!! Design: if the class has not been evaluated before, assume the class has no question!!!!!!
                    # !!! Since each questions except for the Open-Ended questions requires an answer, its all possible (qualified) answers are made into options of a select type input field. !!!!!!


                    $empty = "SELECT DISTINCT qID FROM evaAnswer WHERE cID = \"$class\"";
                    if (!($emptyResult = mysqli_query($conn, $empty))) {
                       printf("Error: %s\n", mysqli_error($conn));
                       exit(1);
                    }

                    # if empty, return message
                    if (mysqli_num_rows($emptyResult)==0) {

                        echo "<br><br><br> The evaluation form of this course is still under construction... <br><br>";
                        echo "<form action='idForm.php' method='post'>";
                        echo "<br><br><br><div class='form-group'>
                                <button type='submit' class='btn btn-primary'>Go Back</button></div></form>";

                    } else {    # not empty, then dynamically load the questions for this class
                            
                        echo "<br><br><form action='endPage.php' method='post'>";
                   
                        # find 1-10 choice questions
                        $TenQuestions = "SELECT DISTINCT question.qID, content 
                                         FROM question, evaAnswer 
                                         WHERE evaAnswer.cID = \"$class\"
                                         AND question.qID = evaAnswer.qID AND type = '1-10\r'";
                        # execute the query
                        if (!($TenResult = mysqli_query($conn, $TenQuestions))) {
                           printf("Error: %s\n", mysqli_error($conn));
                           exit(1);
                        }


                        if (!mysqli_num_rows($TenResult)==0) {

                            # heading for this part
                            echo "<br><br><br><br><h4>====== 1 - 10 Questions ======</h4><br><br>";
                            echo "<h6>Select on a scale of 1-10, with 1 being the lowest and 10 being the highest</h6><br><br>";
                            # create a new paragraph
                            print("<p>\n");
                            # add the table class extra stuff
                            print("<table class=\"table table-striped\">\n");
                            while ($row = mysqli_fetch_assoc($TenResult)) {
                                $qq = $row['qID'];
                                $cc = $row['content'];
                                # question number and content
                                print("<tr>\n");
                                echo "Question $qq: $cc <br><br></label>";

                                # make options for this question
                                echo "<select name=$qq>";
                                for ($i = 1; $i < 11; $i++) {
                                  echo "<option value='" .  $i .  "'>" . $i ."</option>";
                                }                
                                echo "</select><br><br><br>";
                                print ("</tr>\n");

                            }
                            echo "<br><br>";
                            print("<p>\n");
                          
                        } else {

                            # if there is no 1-10 question for this class, return message

                            echo '<h6><span style="color:#f00;text-align:center;">'."!!!".'</span>';
                            echo "-- No \"1-10\" type question</h6><br><br>";
                        }

                        mysqli_free_result($TenResult);




                        # all possible answer for Agree/Disagree type questions
                        $TF = array('Strongly Disagree','Disagree','Neutral','Agree','Strong Agree'); 


                        # find Agree/Disagree questions
                        $ADquestions = "SELECT DISTINCT question.qID, content 
                                         FROM question, evaAnswer 
                                         WHERE evaAnswer.cID = \"$class\"
                                         AND question.qID = evaAnswer.qID AND type = 'Agree/Disagree\r'";

                        # execute the query
                        if (!($ADresult = mysqli_query($conn, $ADquestions))) {
                           printf("Error: %s\n", mysqli_error($conn));
                           exit(1);
                        }

                        if (!mysqli_num_rows($ADresult)==0) {

                            # heading for agree/disagree type questions
                            echo "<br><br><br><br><h4>====== Agree/Disagree Questions ======</h4><br><br>";
                            echo "<h6>5 choice question where the choices are: <br>
                            (1) strongly agree, (2) agree, (3) neutral, (4) disagree, and (5) strongly disagree.</h6><br><br>";
                            # create a new paragraph
                            print("<p>\n");
                            # add the table class extra stuff
                            print("<table class=\"table table-striped\">\n");

                            while ($rowAD = mysqli_fetch_assoc($ADresult)) {
                                $qqq = $rowAD['qID'];
                                $cc = $rowAD['content'];

                                # show Agree/Disagree question number and content
                                print("<tr>\n");
                                echo "Question $qqq: $cc <br><br>";

                                # make options
                                echo "<select name= $qqq >";
                                foreach ($TF as $key => $value) {
                                    echo "<option value='" . $value . "'>" . $value."</option>";
                                }
                                echo "</select><br><br><br>";
                                print ("</tr>\n");

                                
                            }
                            echo"<br><br>";
                            print("</table>\n");
                            print("<p>\n");
                            echo"<br><br>";

                        } else {

                            # if there is not agree/disagree type questions for this class, return message

                            echo '<h6><span style="color:#f00;text-align:center;">'."!!!".'</span>';
                            echo "-- No \"Agree/Disagree\" type question</h6><br><br>";
                            echo"<br><br>";
                        }

                        mysqli_free_result($ADresult);

                  
                        # find multiple choice questions
                        $MCquestions = "SELECT DISTINCT question.qID, content 
                                         FROM question, evaAnswer 
                                         WHERE evaAnswer.cID = \"$class\"
                                         AND question.qID = evaAnswer.qID AND type = 'Multiple Choice\r'";
                        # execute the query
                        if (!($MCresult = mysqli_query($conn, $MCquestions))) {
                           printf("Error: %s\n", mysqli_error($conn));
                           exit(1);
                        }

                        
                        if (!mysqli_num_rows($MCresult)==0) {

                            # heading for multiple choice questions

                            echo "<br><br><br><br><h4>====== Multiple Choice Questions ======</h4><br><br>";
                            echo "<h6>Choose one for each of the following</h6><br><br>";
                            # create a new paragraph
                            print("<p>\n");
                            # add the table class extra stuff
                            print("<table class=\"table table-striped\">\n");

                            # load single question at a time
                            while ($rowAD = mysqli_fetch_assoc($MCresult)) {
                                $qqq = $rowAD['qID'];
                                $ccc = $rowAD['content'];

                                # get all answers for a single question
                                $getAnswer = "SELECT DISTINCT answer as choice
                                                  FROM evaAnswer
                                                  WHERE qID = $qqq
                                                  ORDER BY answer";

                                # execute the query
                                if (!($choices = mysqli_query($conn, $getAnswer))) {
                                   printf("Error: %s\n", mysqli_error($conn));
                                   exit(1);
                                }

                                # show question number and question content
                                print("<tr>\n");
                                echo "Question $qqq: $ccc <br><br>";
                                # construct answer field for each of the multiple choice questions
                                echo "<select name='".$qqq."'>";
                                while ($x = mysqli_fetch_assoc($choices)) {
                                  echo "<option value='" . $x['choice'] . "'>" . $x['choice'] . "</option>";
                                }
                                echo "</select><br><br><br>";
                                print ("</tr>\n");

                                
                            }
                            echo"<br><br>";
                            print("</table>\n");
                            print("<p>\n");
                            echo"<br><br>";
                          
                        } else {
                            echo '<h6><span style="color:#f00;text-align:center;">'."!!!".'</span>';
                            echo "-- No \"Multiple Choice\" type question</h6><br><br>";
                            echo"<br><br>";
                        }

                        mysqli_free_result($MCresult);



                        # find open-text questions
                        $openQuestions = "SELECT DISTINCT question.qID, content 
                                         FROM question, evaAnswer 
                                         WHERE evaAnswer.cID = \"$class\"
                                         AND question.qID = evaAnswer.qID AND type = 'Open-Ended\r'";
                        # execute the query
                        if (!($openResult = mysqli_query($conn, $openQuestions))) {
                           printf("Error: %s\n", mysqli_error($conn));
                           exit(1);
                        }

                        if (!mysqli_num_rows($openResult)==0) {

                            # heading for open-text questions

                            echo "<h4>====== Open-Ended Questions ======</h4><br><br>";
                            echo "<h6>Please leave your additional comment</h6><br>";
                            # create a new paragraph
                            print("<p>\n");
                            # add the table class extra stuff
                            print("<table class=\"table table-striped\">\n");
                            while ($row = mysqli_fetch_assoc($openResult)) {
                                $qq = $row['qID'];
                                $cc = $row['content'];

                                # print the questions
                                print("<tr>\n");
                                echo "<label for='".$qq ."' >Question $qq: $cc</label>";

                                # give answer field 

                                echo "<textarea class='form-control' name='". $qq . "'rows='6'></textarea><br><br>";
                                print ("</tr>\n");


                                
                            }
                            echo "<br><br>";
                            print("</table>\n");
                            print("<p>\n");
                          
                        } else {

                            # if there is no open-ended questions for this class, show this message

                            echo '<h6><span style="color:#f00;text-align:center;">'."!!!".'</span>';
                            echo "-- No \"Open-Ended\" type question</h6><br><br>";
                        }


                        mysqli_free_result($openResult);


                        # finally confirm the student ID and class number
                        echo "This is $sID doing evaluation for $class<br><br>";
                        echo "Confirm your student ID <input type='checkbox' name='ss' value= '" . $sID  . "'><br><br>";
                        echo "Confirm your chosen course ID <input type='checkbox' name='cc' value= '" . $class  . "'><br><br>";

                        # submit and reset buttom
                        echo "<div class='form-group'>
                                <button type='submit' class='btn btn-primary'>Send</button>
                                <button type='reset' class='btn btn-primary'>Reset</button>
                                </div>";

                        # form ending
                        echo "</form>";

                    }
                }
        

            mysqli_close($conn);
      ?>



      <br><br><br>
      
    </div>
  </body>
</html>
