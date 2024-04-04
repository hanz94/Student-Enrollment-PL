<?php 	include '../functions.php'; 
		require_once '../db-connect.php';
		//$uid_db = unserialize(file_get_contents('../.htuiddb'));
		$status = file_get_contents('../session-status.php');
		$session_auto_control = file_get_contents('../session-auto-control.php');
		$session_opening_time = file_get_contents('../session-opening-time.php');
		$session_closing_time = file_get_contents('../session-closing-time.php');
		$courses = unserialize(file_get_contents('../courses-db.php'));
		$limits = unserialize(file_get_contents('../limits.php'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
	<meta name="viewport" content="width=device-width, initial-scale=0.6">
	<meta name="author" content="Koło Naukowe Studentów Anglistyki KUL">
	<title>Panel</title>
		<style>	
		table, td {
			border: 1px solid black;
			padding: 8px;
			border-collapse: collapse;
			word-break: break-word;
		}
		
		.td-auto-control {
			padding-top: 18px;
			padding-bottom: 18px;
		}
		
		.td-session-status {
			padding-top: 24px;
		}
		
		div[style]>div:first-child {
			min-width: 30%;
		}
		td[colspan] {
			text-align:center;
		}
		div[style]>div:last-child {
			margin-left: 30px;
		}
		
		.institution-event {
				margin-top: 30px;
			}
		</style>
</head>
<body>

	<script src="functions-admin.js"></script>

	<div>
		<table style="margin-top:10px;">
		
		<?php
			if ($session_auto_control) {
				echo '<tr>
						<td class="td-auto-control">Sterowanie automatyczne</td>
						<td><span style="color:#0b0;">Włączone</span></td>
						<td><button onclick="location.href=\'?session_auto_control=0\'">Wyłącz</button></td>
					</tr>
					<tr>
						<td>Formularz otwiera się:</td>
						<td>' . date('d M Y H:i:s', $session_opening_time) . '</td>
						<td><button onclick="generateSessionOpeningTimeForm()">Zmień datę otwarcia</button>
							<div id="session-opening-time-form" style="display:none;">
								<form method="get">
									<p>Nowa data otwarcia:</p>
									<input type="datetime-local" name="new_session_opening_time">
									<input style="display:block; margin-top:15px;" type="submit" value="Zapisz">
								</form>
							</div>
						</td>
					</tr>
					<tr>
						<td>Formularz zamyka się:</td>
						<td>' . date('d M Y H:i:s', $session_closing_time) . '</td>
						<td><button onclick="generateSessionClosingTimeForm()">Zmień datę zamknięcia</button>
							<div id="session-closing-time-form" style="display:none;">
								<form method="get">
									<p>Nowa data zamknięcia:</p>
									<input type="datetime-local" name="new_session_closing_time">
									<input style="display:block; margin-top:15px;" type="submit" value="Zapisz">
								</form>
							</div>
						</td>
					</tr>';
			}
			else {
				echo '<tr>
						<td class="td-auto-control" colspan="' . count($courses)+1 . '">Sterowanie automatyczne</td>
						<td><span style="color:#f00;">Wyłączone</span></td>
						<td><button onclick="location.href=\'?session_auto_control=1\'">Aktywuj</button></td>
					</tr>';
			}
			
			?>
			
			</table>
			
			<table style="margin-top:30px;">
		
			<tr>
				<td <?php echo $session_auto_control ? '':'class="td-session-status"';?> colspan="<?php echo count($courses)+3?>">
				<?php
					if (!$session_auto_control) {
				
					echo '<form method="get" style="display:inline;">
						<input type="hidden" name="session_status" value="'; echo $status ? '0':'1'; echo '">
						<input type="submit" value="'; echo $status ? 'Close Session':'Open Session'; echo '">
					</form>';
					
					}
					
					$session_opening_time = file_get_contents('../session-opening-time.php');
					$session_closing_time = file_get_contents('../session-closing-time.php');
					$current_time = time();
		
					if ($session_opening_time != '0' && $session_closing_time != '0') {
						if ($session_opening_time < $current_time && $session_closing_time > $current_time) {
							file_put_contents('../session-status.php', 1);
						}
						else {
							file_put_contents('../session-status.php', 0);
						}
					}
					
					$status = file_get_contents('../session-status.php');
					
					?>
					<p>Status: (Update Time: <?php echo date("d-m-Y H:i:s"); ?>)</p>
					<p><?php echo $status ? 'Formularz zapisów jest <span style="color:#0b0;">otwarty</span>!' : 'Formularz zapisów jest <span style="color:#f00;">zamknięty</span>!'?></p>
				</td>
			</tr>
			
			<?php
			
				$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
				mysqli_set_charset($conn, 'utf8');
				$q = 'SELECT * FROM uid_db';
				$result = mysqli_query($conn, $q) or die('Błąd podczas odczytu danych.');
				
				$uid_db_my = mysqli_fetch_all($result, MYSQLI_ASSOC);
				
				mysqli_close($conn);

				
				if (empty($uid_db_my)) {
					echo 	'<tr>
								<td colspan="3">
									<p>Lista zapisów jest pusta!</p>
								</td>
							</tr>';
				}
				else {
					foreach ($uid_db_my as $uid_db_key => $uid_details) {
						echo '<tr>
								<td>
									<span>' . $uid_details['name_surname'] . '</span>
								</td>
								<td>
									<span>' . $uid_details['uid'] . '</span>
								</td>';
								
								for ($course_number = 1; $course_number <= count($courses); $course_number++) {
									
									$key_to_check = 'group_for_course_';
									$key_to_check .= $course_number;
									
									echo '	<td>
												<span>' . $uid_details[$key_to_check] . '</span>
											</td>';
									
								}

								echo '<td>
									<input type="submit" onclick="deleteUidConfirmation(\'' . $uid_details['uid'] . '\', \'' . $uid_details['name_surname'] . '\')" value="Delete ' . $uid_details['name_surname'] . '">
								</td>
							</tr>';
						
					}
					
				}
				
			?>
		
		</table>

	</div>

	<div class="institution-event">
		<table style="margin-top:10px;">
			<tr>
				<td><p>Institution name</p></td>
				<td><p><?php Value('../institution-name.php');?></p></td>
				<td>
					<button onclick="changeInstitutionName()">Change Institution Name</button>
				</td>
			</tr>
			<tr>
				<td><p>Event name</p></td>
				<td><p><?php Value('../event-name.php');?></p></td>
				<td>
					<button onclick="changeEventName()">Change Event Name</button>
				</td>
			</tr>
		</table>
		
		<!--<div style="margin-top:40px;">
			<button style="margin-left:15px;" onclick="addNewCourse()">Add new course</button>
			<button style="margin-left:15px;" onclick="deleteLastCourse(<?php // echo '\'' . array_key_last($courses) . '\'';?>)">Delete last course</button>
		</div>-->
		
		<table style="margin-top:40px;">
			<tr>
				<td>Course name</td>
				<td>Number of Groups</td>
				<td>Limit per Group</td>
				<?php echo (!empty($courses)) ? '<td colspan="3">Options</td>':''; ?>
			</tr>
			
			<?php
			if (!empty($courses)) {
			
				for ($course_number = 1; $course_number <= count($courses); $course_number++) {
					echo '<tr>';
					echo '<td>' . array_keys($courses)[$course_number-1] . '</td>';
					echo '<td>' . $courses[array_keys($courses)[$course_number-1]] . '</td>';
					echo '<td>' . $limits[$course_number-1] . '</td>';
					echo '<td><button onclick="changeCourseName(' . $course_number . ')">Change Course Name</button></td>';
					echo '<td><button onclick="changeNumberOfGroups(' . $course_number . ')">Change Number of Groups</button></td>';
					echo '<td><button onclick="changeLimit(' . $course_number . ')">Change Limit</button></td>';
					echo '</tr>';
				}
			}
			else {
				echo 	'<tr>
								<td colspan="3">
									<p>Lista dostępnych kursów jest pusta!</p>
								</td>
							</tr>'; 
			}
			?>
		</table>
		
	</div>


<br />


<?php
					
				if (isset($_GET['session_auto_control'])) {
					if ($_GET['session_auto_control'] === '1') {
						file_put_contents('../session-auto-control.php', 1);
						file_put_contents('../session-opening-time.php', round(time()/60)*60);
						file_put_contents('../session-closing-time.php', 2145913200);
						
						$current_time = time();
						$session_opening_time = file_get_contents('../session-opening-time.php');
						$session_closing_time = file_get_contents('../session-closing-time.php');
						
						if ($session_opening_time < $current_time && $session_closing_time > $current_time) {
							$new_status = 1;
							file_put_contents('../session-status.php', $new_status);
						}
						else {
							$new_status = 0;
							file_put_contents('../session-status.php', $new_status);
						}
						
						echo '<script>
						let response = confirm("Sterowanie automatyczne zostało włączone.\nFormularz został automatycznie '; echo $new_status ? 'otwarty':'zamknięty'; echo '.");
						if (response || !response) {
								window.location.replace("?");
							  }
						</script>';
					}	
					else if ($_GET['session_auto_control'] === '0') {
						file_put_contents('../session-auto-control.php', 0);
						file_put_contents('../session-opening-time.php', 0);
						file_put_contents('../session-closing-time.php', 0);
						echo '<script>
						let response = confirm("Sterowanie automatyczne zostało wyłączone.");
						if (response || !response) {
								window.location.replace("?");
							  }
						</script>';
					}
				}
					
				if (isset($_GET['new_session_opening_time'])) {
					$new_session_opening_time = test_input($_GET['new_session_opening_time']);
					
					if (($timestamp = strtotime($new_session_opening_time)) === false) {
						echo '<script>
						let response = confirm("Nieprawidłowy format.");
						if (response || !response) {
								window.location.replace("?");
							  }
						</script>';
					}
					else {
						file_put_contents('../session-opening-time.php', strtotime($new_session_opening_time));
						$current_time = time();
						$session_opening_time = file_get_contents('../session-opening-time.php');
						$session_closing_time = file_get_contents('../session-closing-time.php');
						
						if ($session_opening_time < $current_time && $session_closing_time > $current_time) {
							$new_status = 1;
							file_put_contents('../session-status.php', $new_status);
						}
						else {
							$new_status = 0;
							file_put_contents('../session-status.php', $new_status);
						}
						
						echo '<script>
							let response = confirm("Data otwarcia została zmieniona na ' . date("d-m-Y H:i:s", strtotime($new_session_opening_time)) . '.\nFormularz został automatycznie '; echo $new_status ? 'otwarty':'zamknięty'; echo '.");
							if (response || !response) {
									window.location.replace("?");
								  }
							</script>';
					}
				}
				
				if (isset($_GET['new_session_closing_time'])) {
					$new_session_closing_time = test_input($_GET['new_session_closing_time']);
					
					if (($timestamp = strtotime($new_session_closing_time)) === false) {
						echo '<script>
						let response = confirm("Nieprawidłowy format.");
						if (response || !response) {
								window.location.replace("?");
							  }
						</script>';
					}
					else {
						file_put_contents('../session-closing-time.php', strtotime($new_session_closing_time));
						$current_time = time();
						$session_opening_time = file_get_contents('../session-opening-time.php');
						$session_closing_time = file_get_contents('../session-closing-time.php');
						
						if ($session_opening_time < $current_time && $session_closing_time > $current_time) {
							$new_status = 1;
							file_put_contents('../session-status.php', $new_status);
						}
						else {
							$new_status = 0;
							file_put_contents('../session-status.php', $new_status);
						}
						
						echo '<script>
							let response = confirm("Data zamknięcia została zmieniona na ' . date("d-m-Y H:i:s", strtotime($new_session_closing_time)) . '.\nFormularz został automatycznie '; echo $new_status ? 'otwarty':'zamknięty'; echo '.");
							if (response || !response) {
									window.location.replace("?");
								  }
							</script>';
					}
				}
					
				if (isset($_GET['iname'])) {
					file_put_contents('../institution-name.php', htmlspecialchars($_GET['iname']));
					echo '<script>
					let response = confirm("Institution name changed to: ' . $_GET['iname'] . '");
					if (response || !response) {
							window.location.replace("?");
						  }
					</script>';
					}
					
				if (isset($_GET['eventname'])) {
					file_put_contents('../event-name.php', htmlspecialchars($_GET['eventname']));
					echo '<script>
					let response = confirm("Event name changed to: ' . $_GET['eventname'] . '");
					if (response || !response) {
							window.location.replace("?");
						  }
					</script>';
					
					}
					
				if (isset($_GET['session_status'])) {
					if ($_GET['session_status']) {
						file_put_contents('../session-status.php', 1);
						echo '<script>
						let response = confirm("Session is now open!");
						if (response || !response) {
								window.location.replace("?");
							  }
						</script>';
					}
					else {
						file_put_contents('../session-status.php', 0);
						echo '<script>
						let response = confirm("Session is now closed!");
						if (response || !response) {
								window.location.replace("?");
							  }
						</script>';
					}
				}
					
				// if security improvement done
				if (isset($_GET['delete_uid']) && $_GET['delete_uid'] && isset($_GET['uid_value'])) {
						$uid_value_to_be_deleted = test_input($_GET['uid_value']);
						
						$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
						mysqli_set_charset($conn, 'utf8');
						
						$q_name = 'SELECT name_surname FROM `uid_db` WHERE uid = "' . $uid_value_to_be_deleted . '";';
						$result = mysqli_query($conn, $q_name) or die('Błąd podczas odczytu danych.');
						$name_to_be_deleted = mysqli_fetch_all($result);
						
						$q_delete = 'DELETE FROM `uid_db` WHERE uid = "' . $uid_value_to_be_deleted . '";';
						mysqli_query($conn, $q_delete) or die('Błąd podczas odczytu danych.');
						mysqli_close($conn);
						
						echo '<script>
						let response = confirm("User ' . $name_to_be_deleted[0][0]  . ' has been deleted!");
						if (response || !response) {
								window.location.replace("?");
							  }
						</script>';
						
					}
					
					//filter validate int for course number, check if course number exists, new course name max characters 100
					if (isset($_GET['course_number']) && isset($_GET['new_course_name']) && $_GET['new_course_name'] != null) {
						
						//two courses must not have the same name !! two identical key names prohibited   done
						if (!array_key_exists($_GET['new_course_name'], $courses)) {
						
							$keys = array_keys($courses);
							$keys[array_search(array_keys($courses)[$_GET['course_number']-1], $keys)] = test_input($_GET['new_course_name']);
							$courses = array_combine($keys, $courses);
							file_put_contents('../courses-db.php', serialize($courses));
							echo '<script>
							let response = confirm("The name of the course has been changed to ' . $_GET['new_course_name'] . '!");
							if (response || !response) {
									window.location.replace("?");
								  }
							</script>';
						}
						else {
							echo '<script>
							let response = confirm("New name of the course must NOT be identical to the name of any other course!");
							if (response || !response) {
									window.location.replace("?");
								  }
							</script>';
						}
					}
					
					//filter validate int for course number, filter validate int for new number of groups
					if (isset($_GET['course_number']) && isset($_GET['new_number_of_groups']) && $_GET['course_number'] != null && $_GET['new_number_of_groups'] != null) {
						if ($_GET['new_number_of_groups'] < 1 || $_GET['new_number_of_groups'] > 9) {
							echo '<script>alert("The number of groups must be between 1 and 9!");</script>';
						}
						else {
							$courses[array_keys($courses)[$_GET['course_number']-1]] = intval($_GET['new_number_of_groups']);
							
							file_put_contents('../courses-db.php', serialize($courses));
							
							//check if anyone is assigned to the non-existing group in case the group number was decreased     based on line 349 functions     done
							if (!empty($uid_db)) {
								foreach ($uid_db as $uid => $uid_details) {
									foreach ($uid_details as $key => $value) {
										
										for ($course_number = 1; $course_number <= count($courses); $course_number++) {
										
											for ($group_number = 1; $group_number <= $courses[array_keys($courses)[$course_number-1]]; $group_number++) {
												
												$key_to_check = 'group_for_course_';
												$key_to_check .= $course_number;
												$group_name_to_check = 'group_';
												$group_name_to_check .= $group_number;
												$when_check = 'group_for_course_';
												$when_check .= $course_number;
												$when_check .= '_when';
												
												if ($key === $key_to_check && intval($value) > $_GET['new_number_of_groups']) {
													$uid_db[$uid][$key_to_check] = 'null';
													$uid_db[$uid][$when_check] = 0;
												}
											}
											
										}
										
									}
								
								}
								file_put_contents('../.htuiddb', serialize($uid_db));
							}
							
							echo '<script>
							let response = confirm("New number of groups in the course \"' . array_keys($courses)[$_GET['course_number']-1] . '\" has been changed to ' . $_GET['new_number_of_groups'] . '!");
							if (response || !response) {
									window.location.replace("?");
								  }
							</script>';
						}
						
						
					}
					
					//check for ints, filter validate int for both
					if (isset($_GET['course_number']) && isset($_GET['new_limit']) && $_GET['course_number'] != null && $_GET['new_limit'] != null) {
						if ($_GET['new_limit'] >=1 && $_GET['new_limit'] <= 1000) {
							
							$limits[$_GET['course_number']-1] = $_GET['new_limit'];
							file_put_contents('../limits.php', serialize($limits));
							
							echo '<script>
							let response = confirm("New limit for the course \"' . array_keys($courses)[$_GET['course_number']-1] . '\" has been changed to ' . $_GET['new_limit'] . '.");
							if (response || !response) {
									window.location.replace("?");
								  }
							</script>';
							
						}
						else {
							echo '<script>
							let response = confirm("New limit must be a number between 1 and 1000!");
							if (response || !response) {
									window.location.replace("?");
								  }
							</script>';
						}
						
					}
					
					//todo: name of new course must not be identical to the name of any other already existing course!!
					if (isset($_GET['new_course_name']) && isset($_GET['new_number_of_groups']) && $_GET['new_course_name'] != null && $_GET['new_number_of_groups'] != null) {
						
						//for each already registered user assign additional key "group for course" (when) x
						if (!empty($uid_db)) {
							foreach ($uid_db as $uid => $uid_details) {
								
								$new_key_to_assign = 'group_for_course_';
								$new_key_to_assign .= count($courses) + 1;
								$new_key_to_assign_when = 'group_for_course_';
								$new_key_to_assign_when .= count($courses) + 1;
								$new_key_to_assign_when .= '_when';
								
								$uid_details[$new_key_to_assign] = 'null';
								$uid_details[$new_key_to_assign_when] = 0;
								
								$uid_db[$uid] = $uid_details;
							}
							file_put_contents('../.htuiddb', serialize($uid_db));
						}

						$new_course_name = test_input($_GET['new_course_name']);
						$courses[$new_course_name] = intval($_GET['new_number_of_groups']);
						file_put_contents('../courses-db.php', serialize($courses));
						
						$limits[] = 0;
						file_put_contents('../limits.php', serialize($limits));
						
						
						echo '<script>
							let response = confirm("New course \"' . $_GET['new_course_name'] . '\" with ' . $_GET['new_number_of_groups'] . ' groups has been created!");
							if (response || !response) {
									window.location.replace("?");
								  }
							</script>';
					}
					
					if (isset($_GET['delete_last_course']) && $_GET['delete_last_course']) {
						
						if (!empty($uid_db)) {
							foreach ($uid_db as $uid => $uid_details) {
								
								unset($uid_details[array_key_last($uid_details)]);
								unset($uid_details[array_key_last($uid_details)]);
								
								$uid_db[$uid] = $uid_details;
							}
								//check
								unset($limits[array_key_last($limits)]);
								
							file_put_contents('../.htuiddb', serialize($uid_db));
							file_put_contents('../limits.php', serialize($limits));
						}
						
						$last_course_name = array_key_last($courses);
						
						unset($courses[array_key_last($courses)]);
						file_put_contents('../courses-db.php', serialize($courses));
						
						echo '<script>
							let response = confirm("The last course \"' . $last_course_name . '\" has been deleted!");
							if (response || !response) {
									window.location.replace("?");
								  }
							</script>';

					}
				
?>
				</body>
				</html> 