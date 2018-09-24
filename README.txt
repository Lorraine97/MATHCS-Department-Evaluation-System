Server External IP: 35.229.27.222


/* THIS CODE IS MY OWN WORK.
IT WAS WRITTEN WITHOUT CONSULTING CODE WRITTEN BY OTHER STUDENTS.
_Xinru Lu_ */

===Part 5===Data Population: 

Note for database: there might be some "warning" messages when you populate the table. However, it is because I added integers in the end of each row so that it will know when to end. Therefore, the tables will still work. 



Instruction to start the php pages: 

===Part 7===User Input Form(s): 

Go to "idForm.php" to input the students' ID.

It will jump to "idCheck.php" to check if the ID is valid or not. If valid, it will show a list of classes that the student has enrolled before and has not evaluated yet.

Then, if there is a course to evaluate, the student will be able to select a course and goes to the next page (-->  inputForm.php).


In inputForm.php, according to the selected course, it will load its evaluation questions.

Design!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
If the class has not been evaluated before, assume the class has no question. (!!! instead of all questions)

Since each questions except for the Open-Ended questions requires an answer, its all possible (qualified) answers are made into options of a select type input field. 
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


Submit on the inputForm page, it will goes to --> endPage.php

Design!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
Since only the valid answers are made into options, no need to check answers for selection questions, and no need to check if the student has input the answer or not.

Only need to check the free-ended questions.
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

There, it will check if the student has filled in the free-ended questions (if not, it will exclude the null answer of that question).



===Part 8===Class Report: 

-----Student---------------------------
Go to "studentViewForm.php" to input the course name the student wants to see;

If valid course name, then it will jump to "chooseProfessor.php", where listed professors that have instructed this course.

Then, it will show the general statistics of all sections of this course taught by that professor in "viewStudent.php"


-----Instructor---------------------------
Go to "facultyForm.php" to input the instructor ID of that professor;

If valid instructor ID, then it will jump to "facultyChooseCourse.php", where listed each class that instructor have taught. (with class number, term...)

Then, it will show specific statistics of that section taught by that professor in "viewFaculty.php"
