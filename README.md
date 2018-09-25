# MATHCS-Department-Evaluation-System
*A dynamic evaluation webpage that self-updates with authorized and proper input questions (PHP, CSS, MySQL)*

### - Background:
  - An evaluation database is initiated with MySQL based on raw data from some historical evaluation answer
sheets (originally in excel; decoded into csv datasheet using R)

  - The ER diagram for the website is based on the following assumptions:
    - A class is assigned a **unique class number**, a course name, section number, semester and year. (e.g. class number 1859, course name CS377, section 1, Spring semester, and 2018)
    - Each course must be taught by an instructor
    - An instructor has a first name, a last name, and a **unique instructor id**
    - A student will be assigned a **unique integer identifier** based on their student ID, which allows their response to be anonymized
    - A student can take any number of classes
    - A student can evaluate any number of classes provided they have taken the class. However, the student **cannot** evaluate the same class more than once
    - A class evaluation must have at least one question and can have any number of questions
    - A question can take on the following four different types:
      - Agree/Disagree: 5 choice question where the choices are: strongly disagree ~ strongly agree
      - Multiple Choice: Question-specific choices that need to be defined
      - 1-10: On a scale of 1-10, with 1 being the lowest and 10 being the highest, how ... was the class?
      - Open-Ended: a textual response
    - Each class can share any number of questions with other classes

  - Database is normalized to meet **3NF schema**.

### - Functions:
  - **Class Report**: webpages are constructed to provide provides reports for each course. It is tailored specifically for each user (two types for now: faculty and student)
    - Student View of Class: a webpage that will allow the user to view summary statistics of a course
      - choose the course name and the instructor -> return aggregate statistics for all the questions
    - Faculty View of Class: a webpage that will allow the faculty to view **his/her statistics** for a single class
  - **User Input Form** (for students): a dynamically loaded form allows a student to enter in their class evaluation for a single class
      - Verify the user (unique identifier) is a valid one (in the system).
      - Verify the user is allowed (has taken the class before) to provide a class evaluation for the course.
      - *Dynamically* loads the evaluation questions pertinent to each course.
      - Ensure all the questions have an associated response.
      - Allow only one evaluation per student (and it should be the first one submitted / only one attempt allowed).


*It employs a rigid database structure as the system is pre-designed and not likely to change.*

*The system was on Google Cloud Server from March to August.*
