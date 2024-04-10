# Introduction
Application for online Student Enrollment (Polish version).
The project has been created to facilitate enrollment process for students who need to divide themselves and assign to specific university courses.
The application enables students to choose a group for each course they need to be assigned without modifying the choices of other fellow students.
The enrollment administrator uses separate website to control the enrollment process, intervene in need, and see the enrollment results.

# How to use
Demo version is available here: (Part I, II, III)
## Part I: https://knsa.pl/zapisy/
The main navigation panel for students who want to enroll themselves to specific groups of predefined academic courses.
The student begins by providing his/her name+surname ("Rozpocznij zapisy"). The system assigns each student a unique ID (uID), accessible only for that person.
The next step is to fill in a table form, in which specific choices can be made. The student can also come back to the form later by one of the following methods:
1. Remembering the uID and pasting it later in the "Wprowadź zmiany" modal box;
2. Searching for the link in the Web Browser History (if available, the uID is sent by the GET method);
3. Clicking the button "Zapisz kod w pamięci przeglądarki". The button uses JavaScript to save the uID together with student's name+surname as a cookie, which is stored in the Web Browser memory for up to 90 days. If the website detects such cookie, the button "Wprowadź zmiany" automatically redirects the user to his/her enrollment form.
All cookies can be deleted at any time using the "Usuń kod z pamięci przeglądarki" button.

## Part II: https://knsa.pl/zapisy/wyniki/
The page for enrollment results. After clicking the button "Zapisz zmiany" in Part I, the system automatically assigns a student to selected groups, provided that the maxlimit of students for a group is not exceeded. In case the maxlimit is exceeded, the student will see the red-font information "Brak miejsc!" in the table form of Part I.
The system assigns students in a first-come-first-served method. That is why in the results page (Part II), the system supplements each correctly assigned name+surname with the information when the student was assigned (the column "Kiedy zapisano"). The page is also adjusted for printing.

## Part III: https://knsa.pl/zapisy/admin/
The admin panel, which is visible to enrollment administrator only (temporary version). The panel enables administrators to control the enrollment process.
The most important features of the admin panel are: 
1. Access to all uIDs in the database, together with names+surnames, groups assigned to each student, and errors;
2. Possibility of deleting unwanted users from the database.
3. Changing the content of each page (institution name, event name, event date, description etc.)

# Background information
The idea of the online Student Enrollment application was developed as a response to the needs of English Philology Department at Catholic University of Lublin, Poland.
Previously, the students struggled with the process due to unwanted changes in Google Docs files, such as replacing names, deleting other students from tables etc.
The solution is going to be implemented at KUL in October 2024, before the implementation it still needs to undergo several changes and adjustments.

