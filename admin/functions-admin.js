				function generateSessionOpeningTimeForm() {
					var formOpeningTime = document.getElementById("session-opening-time-form");
					var formClosingTime = document.getElementById("session-closing-time-form");
					if (formOpeningTime.style.display === 'none') {
						formOpeningTime.style.display = 'block';
						formClosingTime.style.display = 'none';
					}
					else {
						formOpeningTime.style.display = 'none';
						formClosingTime.style.display = 'none';
					}
				}

				function generateSessionClosingTimeForm() {
					var formOpeningTime = document.getElementById("session-opening-time-form");
					var formClosingTime = document.getElementById("session-closing-time-form");
					if (formClosingTime.style.display === 'none') {
						formClosingTime.style.display = 'block';
						formOpeningTime.style.display = 'none';
					}
					else {
						formClosingTime.style.display = 'none';
						formOpeningTime.style.display = 'none';
					}
				}
				function deleteUidConfirmation(uid, name) {
					let response = confirm("Are you sure you want to delete " + name + "?");
					if (response) {
						window.location.replace("?delete_uid=1&uid_value=" + uid);
					}
				}
				function changeInstitutionName() {
					  let institutionname = prompt("Enter new institution name:");
					  if (!institutionname == null || !institutionname == "") {
						eventname = encodeURIComponent(institutionname);
						window.location.replace("?iname=" + institutionname);
					  } 
					}
					function changeEventName() {
					  let eventname = prompt("Enter new event name:");
					  if (!eventname == null || !eventname == "") {
						eventname = encodeURIComponent(eventname);
						window.location.replace("?eventname=" + eventname);
					  }
					}
					function changeCourseName(courseNumber) {
					  let newCourseName = prompt("Enter new course name:");
					  if (!newCourseName == null || !newCourseName == "") {
						newCourseName = encodeURIComponent(newCourseName);
						window.location.replace("?course_number=" + courseNumber + "&new_course_name=" + newCourseName);
					  }
					}
					function changeNumberOfGroups(courseNumber) {
					  let newNumberOfGroups = prompt("Enter new number of groups:");
					  
					  if (!newNumberOfGroups == null || !newNumberOfGroups == "") {
						  if (isNaN(newNumberOfGroups)) {
							 alert("Provide a number!"); 
						  }
						  else {
							  
							  if (newNumberOfGroups < 1 || newNumberOfGroups > 9) {
								  alert("The number of groups must be between 1 and 9!"); 
							  }
						  
							  else {
								newNumberOfGroups = encodeURIComponent(newNumberOfGroups);
								window.location.replace("?course_number=" + courseNumber + "&new_number_of_groups=" + newNumberOfGroups);
							  }
						  }
					  }
					}
					
					function changeLimit(courseNumber) {
					  let newLimit = prompt("Enter new limit of students:");
					  
					  if (!newLimit == null || !newLimit == "") {
						  if (isNaN(newLimit)) {
							 alert("Provide a number!"); 
						  }
						  else {
							  
							  if (newLimit < 1 || newLimit > 1000) {
								  alert("The number of groups must be between 1 and 1000!"); 
							  }
						  
							  else {
								newLimit = encodeURIComponent(newLimit);
								window.location.replace("?course_number=" + courseNumber + "&new_limit=" + newLimit);
							  }
						  }
					  }
					}
					
					function addNewCourse() {
						let newCourseName = prompt("Enter new course name:");
						
						if (!newCourseName == null || !newCourseName == "") {
							let newNumberOfGroups = prompt("Enter new number of groups:");
						  
							  if (!newNumberOfGroups == null || !newNumberOfGroups == "") {
								  if (isNaN(newNumberOfGroups)) {
									 alert("Provide a number!"); 
								  }
								  else {
									  
									  if (newNumberOfGroups < 1 || newNumberOfGroups > 9) {
										  alert("The number of groups must be between 1 and 9!"); 
									  }
								  
									  else {
										newCourseName = encodeURIComponent(newCourseName);
										newNumberOfGroups = encodeURIComponent(newNumberOfGroups);
										window.location.replace("?new_course_name=" + newCourseName + "&new_number_of_groups=" + newNumberOfGroups);
									  }
								  }
							  }
						}
					}
					
					function deleteLastCourse(courseName) {
						let response = confirm("Are you sure you want to delete the last course \"" + courseName + "\"?");
							if (response) {
								window.location.replace("?delete_last_course=1");
							}
					}