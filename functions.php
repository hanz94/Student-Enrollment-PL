<?php
			
			function Value($path) {
				echo file_get_contents($path);
			}
			
			function test_input($input) {
				$input = trim($input);
				$input = stripslashes($input);
				$input = htmlspecialchars($input);
				return $input;
			}
			
			function SessionClosedMessage() {
				$session_auto_control = file_get_contents('session-auto-control.php');
				$session_opening_time = file_get_contents('session-opening-time.php');
				$session_closing_time = file_get_contents('session-closing-time.php');
				$current_time = time();
						if ($session_auto_control) {
							if ($session_opening_time >= $current_time && $session_opening_time < $session_closing_time) {
								echo '<p>Formularz zapisów nie jest jeszcze dostępny!</p>
								<br>
								<p>Aktualny czas: <b><span id="current-time">' . date('d M Y H:i:s', $current_time) . '</span></b></p>
								<br>
								<p>Formularz otwiera się:</p>
								<br>
								<p><b>' . date('d M Y H:i:s', $session_opening_time) . '</b></p>
								<br>
								<button style="margin-top:unset;" class="action-button enrollment-submit-button" onclick="location.reload();">Odśwież stronę</button>';
							}
							else if ($session_closing_time <= $current_time && $session_opening_time < $session_closing_time) {
								echo '<p>Formularz zapisów jest niedostępny!</p>
								<br>
								<p>Aktualny czas: <b><span id="current-time">' . date('d M Y H:i:s', $current_time) . '</span></b></p>
								<br>
								<p>Formularz został zamknięty:</p>
								<br>
								<p>' . date('d M Y H:i:s', $session_closing_time) . '</p>
								<button style="margin-top:25px;" class="action-button enrollment-submit-button" onclick="location.href=\'wyniki\';">Wyniki</button>';
							}
							else if ($session_opening_time > $session_closing_time) {
								echo '<p>Formularz zapisów jest niedostępny!</p>';
							}
						}
						else {
								echo '<p>Formularz zapisów jest niedostępny!</p>';
							}
			}
			
			function SessionClosed() {
					echo '<main>
			
				<header class="container-small knsa-kul">
					<img src="knsa-logo.png" alt="" class="img-knsa">
					<img src="kul.jpg" alt="" class="img-kul">
					<div>'; Value('institution-name.php'); echo '</div>
				</header>
				
					<div class="flag"></div>
				
				<section class="container-small container-event-name-date">
					<div class="event-name">'; Value('event-name.php'); echo '</div>
				</section>
				
				<section class="container-small result-form">
					<div>'; SessionClosedMessage(); echo '</div>
				</section>

				</main>';
				
			}
			
			function RandomUID($length) {
				$characters = 'abcdefghijklmnoprstuwvxyzABCDEFGHIJKLMNOPRSTUWVXYZ0123456789';
				$randomUID = rand(0,9);
				for ($a = 0; $a < $length - 1; $a++) {
					$randomUID .= $characters[rand(0, strlen($characters) - 1)];
				}
				return $randomUID;
			}
			
			function MainIndex() {
				$session_auto_control = file_get_contents('session-auto-control.php');
				$session_opening_time = file_get_contents('session-opening-time.php');
				$session_closing_time = file_get_contents('session-closing-time.php');
				$current_time = time();
				
				echo '<main>
			
				<header class="container-small knsa-kul">
					<img src="knsa-logo.png" alt="" class="img-knsa">
					<img src="kul.jpg" alt="" class="img-kul">
					<div>'; Value('institution-name.php'); echo '</div>
				</header>
				
					<div class="flag"></div>
				
				<section class="container-small container-event-name-date">
					<div class="event-name">'; Value('event-name.php'); echo '</div>
				</section>
				

				<nav id="input-solution" class="container-small result-form">
				
					<span id="enrollment-begin-button-container"><button class="action-button" id="btn-nav-begin">Rozpocznij zapisy</button></span>
					
					<button class="action-button" id="btn-nav-changes">Wprowadź zmiany</button>
					
					<button class="action-button" id="btn-nav-results">Wyniki</button>
					
				</nav>

			</main>';

			}
			
			function InputNameSurname() {
				
				$courses = unserialize(file_get_contents('courses-db.php'));
				global $db_host, $db_user, $db_pass, $db_name;
				
					echo '<main>
			
				<header class="container-small knsa-kul">
					<img src="knsa-logo.png" alt="" class="img-knsa">
					<img src="kul.jpg" alt="" class="img-kul">
					<div>'; Value('institution-name.php'); echo '</div>
				</header>
				
					<div class="flag"></div>
				
				<section class="container-small container-event-name-date">
					<div class="event-name">'; Value('event-name.php'); echo '</div>
				</section>

				<section id="input-solution" class="container-small result-form">
				
				<label for="name-surname-textarea">
					<p>Imię i nazwisko:</p><br />
				</label>
					<form method="post" class="name-surname-form">
						<input type="text" id="name-surname-textarea" name="name_surname_value" autocomplete="off" autofocus>
						<button class="next-button">DALEJ</button>
					</form>
					
				</section>

			</main>';
			
				if (isset($_POST['name_surname_value']) && $_POST['name_surname_value'] != null) {
					$new_name_surname_value = test_input($_POST['name_surname_value']);
					
					//check if name surname already exists in db    done
					//make sure that generated randomuid is not identical to already existing uid in db    done
					
					$new_uid_exists = true;
					
					while ($new_uid_exists) {
						$new_uid = RandomUID(24);
						
						$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
						mysqli_set_charset($conn, 'utf8');
						$q_uid_check = 'SELECT uid FROM uid_db WHERE uid = "' . $new_uid . '";';
						$result = mysqli_query($conn, $q_uid_check) or die('Błąd podczas odczytu danych.');
						mysqli_close($conn);
						$uid_found = mysqli_num_rows($result);
					
							if ($uid_found > 0) {
								$new_uid_exists = true;
							}
							else {
								$new_uid_exists = false;
							}
					}
					
					
						$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
						mysqli_set_charset($conn, 'utf8');
						$q_ns_check = 'SELECT name_surname FROM uid_db WHERE name_surname = "' . $new_name_surname_value . '";';
						$result = mysqli_query($conn, $q_ns_check) or die('Błąd podczas odczytu danych.');
						mysqli_close($conn);
						
						$ns_found = mysqli_num_rows($result);
						
						if ($ns_found > 0) {
							echo '<script src="functions.min.js"></script>
							<script> 
								showModalError("Podane imię i nazwisko już istnieje.<br>Wprowadź inną wartość lub skontaktuj się z organizatorem.", "?uID=new");
							</script>';
							file_put_contents('security.log', '[' . date("Y-m-d h:i:s A") . '] Issue: Name and surname already exists, IP: ' . $_SERVER['REMOTE_ADDR'] . ' Name and surname value: ' . $new_name_surname_value . PHP_EOL, FILE_APPEND);
						}
						else {
							$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
							mysqli_set_charset($conn, 'utf8');
							$q = 'INSERT INTO `uid_db` (`id`, `uid`, `name_surname`, `group_for_course_1`, `group_for_course_1_when`, `group_for_course_2`, `group_for_course_2_when`, `group_for_course_3`, `group_for_course_3_when`, `group_for_course_4`, `group_for_course_4_when`) VALUES (NULL, "' . $new_uid . '", "' . $new_name_surname_value . '", "null", "0", "null", "0", "null", "0", "null", "0");';
							mysqli_query($conn, $q) or die('Błąd podczas odczytu danych.');
							mysqli_close($conn);
							
							echo '<script>window.location.replace("?uID=' . $new_uid . '")</script>';
						}
				}
		
				
			}
			
			function StudentEnrollmentForm() {
				
				$courses = unserialize(file_get_contents('courses-db.php'));
				$limits = unserialize(file_get_contents('limits.php'));
				global $db_host, $db_user, $db_pass, $db_name;
							
				$uid_input = test_input($_GET['uID']);
	
					$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
					mysqli_set_charset($conn, 'utf8');
					$q_name = 'SELECT name_surname FROM uid_db WHERE uid = "' . $uid_input . '";';
					$result = mysqli_query($conn, $q_name) or die('Błąd podczas odczytu danych.');
					mysqli_close($conn);
					
					$name_surname = mysqli_fetch_all($result);
					$name_surname = $name_surname[0][0];
				
				
				echo '<main>
			
				<header class="container-small knsa-kul">
					<img src="knsa-logo.png" alt="" class="img-knsa">
					<img src="kul.jpg" alt="" class="img-kul">
					<div>'; Value('institution-name.php'); echo '</div>
				</header>
				
					<div class="flag"></div>
				
				<section class="container-small container-event-name-date">
					<div class="event-name">'; Value('event-name.php'); echo '</div>
				</section>

				<section id="input-solution" class="container-small result-form">
					<div class="enrollment-name-surname-details">
							<p>Zapisujesz się jako</p>
							<p>' . $name_surname . '</p>
						</div>
						<div class="enrollment-uid-details">
							<p><b>Kod: ' . $uid_input . '</b></p>
							<p><i>Zapamiętaj kod, aby dokonać późniejszych zmian!</i></p><br>
								<details open>
									<summary>Więcej opcji</summary>
										<ul>
											<li style="margin-top:10px;text-decoration:underline;list-style-type:none;cursor:pointer;" id="cookie-button"></li>
											<script>var currentUID ="' . $uid_input . '";var nameSurname ="' . $name_surname . '";</script>
										</ul>
								</details>
						</div>
						
						<form method="post">
							<table class="enrollment-table">
								<tr>
									<th>Nazwa przedmiotu</th>
									<th><label for="group-number">Grupa</label></th>
									<th>Status</th>
								</tr>';
								
								$groups_assigned_counter = 0;
								
								for ($course_number = 1; $course_number <= count($courses); $course_number++) {
									
									$course_number_key_to_assign = 'group_for_course_';
									$course_number_key_to_assign .= $course_number;
									$course_number_key_to_assign_when = 'group_for_course_';
									$course_number_key_to_assign_when .= $course_number;
									$course_number_key_to_assign_when .= '_when';
									
									$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
									mysqli_set_charset($conn, 'utf8');
									$q = 'SELECT ' . $course_number_key_to_assign . ' FROM uid_db WHERE uid = "' . $uid_input . '";';
									$result = mysqli_query($conn, $q) or die('Błąd podczas odczytu danych.');
									$q_when = 'SELECT ' . $course_number_key_to_assign_when . ' FROM uid_db WHERE uid = "' . $uid_input . '";';
									$result_when = mysqli_query($conn, $q_when) or die('Błąd podczas odczytu danych.');
									mysqli_close($conn);
									
									$group_selected = mysqli_fetch_all($result);
									$group_selected = $group_selected[0][0];
									$group_selected_when = mysqli_fetch_all($result_when);
									$group_selected_when = $group_selected_when[0][0];
									
									
									echo '<tr>
									<td>' . array_keys($courses)[$course_number-1] . '</td>
									<td>
										<select name="' . $course_number_key_to_assign . '"'; echo ($course_number === 1) ? ' id="group-number"':''; echo '>
											<option value="null">--Wybierz--</option>';
											
											for ($group_number = 1; $group_number <= $courses[array_keys($courses)[$course_number-1]]; $group_number++) {
												
												$group_number_key_to_assign = 'group_for_course_';
												$group_number_key_to_assign .= $group_number;
												
												echo '<option value="' . $group_number . '"'; echo ($group_selected === strval($group_number)) ? ' selected="selected"':''; echo '>Grupa ' . $group_number . '</option>';
												
												if ($group_selected === strval($group_number)) {
													$groups_assigned_counter++;
												}
												
											}
											
										echo '</select>
									</td>
									<td>';
									
									if ($group_selected === 'null') {
										echo 'Nie wybrano';
									}
									else if (str_starts_with($group_selected, 'error_')) {
										echo 'Grupa ' . substr($group_selected, -1) . '<br /><span style="color:#f00;">Brak miejsc!</span>';
									}
									// else if - group selected length = 1
									else {
										echo 'Grupa ' . $group_selected . '<br /><span style="color:#0b0;">' . date('d M Y H:i:s', $group_selected_when) . '</span>';
									}
									
									
									echo '</td>
								</tr>';
									
								}
							
							echo '</table>
							
								<button '; echo ($groups_assigned_counter === count($courses)) ? 'style="margin-top:25px;"':''; echo 'class="action-button enrollment-submit-button">Zatwierdź i zapisz</button>
								</form>
							';
							
							echo ($groups_assigned_counter === count($courses)) ? '<button style="margin-top:15px;" class="action-button enrollment-submit-button" onclick="location.href=\'wyniki\';">Wyniki</button>':'';

					echo '</section>

			</main>';
			
				//if under the comment security update   done   + all elses security update convert into else if
				if (!empty($_POST) && count($_POST) === count($courses)) {
					
					$accepted_values = array('null','1','2','3','4');
					
					foreach ($_POST as $key => $value) {
						
						$security_check_key_1 = str_starts_with($key, 'group_for_course_');
						$security_check_key_2 = is_numeric(substr($key, -1, 1));
						$security_check_key_length = strlen($key);
						
						//echo $key . '<br>';
						
						$security_check_value = in_array($value, $accepted_values);
							
				if (!$security_check_value || !$security_check_key_1 || !$security_check_key_2  || $security_check_key_length != 18) {
							echo '<script src="functions.min.js"></script>
						<script>
						showModalError("Błąd podczas przetwarzania danych.<br>Organizator został powiadomiony.", "?");
						</script>';
						
						file_put_contents('security.log', '[' . date("Y-m-d h:i:s A") . '] SECURITY Issue: Incorrect POST input, IP: ' . $_SERVER['REMOTE_ADDR'] . ' uID: ' . $_GET['uID'] . ' Key input: ' . $key . ' Value input: ' . $value . PHP_EOL, FILE_APPEND);
						die();
						}
					}
					
					for ($course_number = 1; $course_number <= count($courses); $course_number++) {
						
						$course_number_key_to_assign = 'group_for_course_';
						$course_number_key_to_assign .= $course_number;
						$course_number_key_to_assign_when = 'group_for_course_';
						$course_number_key_to_assign_when .= $course_number;
						$course_number_key_to_assign_when .= '_when';
						
						if ($_POST[$course_number_key_to_assign] === 'null') {
							
							$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
							mysqli_set_charset($conn, 'utf8');
							$q = 'UPDATE `uid_db` SET `' . $course_number_key_to_assign . '` = "null" WHERE uid = "' . $uid_input . '";';
							mysqli_query($conn, $q) or die('Błąd podczas odczytu danych.');
							$q_when = 'UPDATE `uid_db` SET `' . $course_number_key_to_assign_when . '` = "0" WHERE uid = "' . $uid_input . '";';
							mysqli_query($conn, $q_when) or die('Błąd podczas odczytu danych.');
							mysqli_close($conn);
						}
						else {
				
								$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
								mysqli_set_charset($conn, 'utf8');
								$q = 'SELECT ' . $course_number_key_to_assign . ' FROM uid_db WHERE uid = "' . $uid_input . '";';
								$result = mysqli_query($conn, $q) or die('Błąd podczas odczytu danych.');
								mysqli_close($conn);
								
								$group_number_previously_selected = mysqli_fetch_all($result);
								$group_number_previously_selected = $group_number_previously_selected[0][0];
							
							if ($group_number_previously_selected === '0' || str_starts_with($group_number_previously_selected, 'error_') || $group_number_previously_selected != $_POST[$course_number_key_to_assign]) {
								
								$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
								mysqli_set_charset($conn, 'utf8');
								$q_when = 'UPDATE `uid_db` SET `' . $course_number_key_to_assign_when . '` = "' . time() . '" WHERE uid = "' . $uid_input . '";';
								mysqli_query($conn, $q_when) or die('Błąd podczas odczytu danych.');
								mysqli_close($conn);
							
							}
								$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
								mysqli_set_charset($conn, 'utf8');
								$q_check_limit = 'SELECT group_for_course_' . $course_number . ' FROM uid_db WHERE group_for_course_' . $course_number . ' = "' . $_POST[$course_number_key_to_assign] . '";';
								$result = mysqli_query($conn, $q_check_limit) or die('Błąd podczas odczytu danych.');
								mysqli_close($conn);
								
								$slots_occupied = mysqli_num_rows($result);
							
								if ($limits[$course_number-1] != 0 && $slots_occupied >= $limits[$course_number-1]) {
									
									if ($group_number_previously_selected === $_POST[$course_number_key_to_assign]) {
										continue;
									}
									
									$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
									mysqli_set_charset($conn, 'utf8');
									$q = 'UPDATE `uid_db` SET `' . $course_number_key_to_assign . '` = "error_' . $_POST[$course_number_key_to_assign] . '" WHERE uid = "' . $uid_input . '";';
									mysqli_query($conn, $q) or die('Błąd podczas odczytu danych.');
									mysqli_close($conn);
									
									file_put_contents('security.log', '[' . date("Y-m-d h:i:s A") . '] Issue: Course limit exceeded (brak miejsc), IP: ' . $_SERVER['REMOTE_ADDR'] . ' uID: ' . $_GET['uID'] . ' Name and surname: ' . $name_surname . ' Course name: ' . array_keys($courses)[$course_number-1] . PHP_EOL, FILE_APPEND);
								}
								else {
							
									$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
									mysqli_set_charset($conn, 'utf8');
									$q = 'UPDATE `uid_db` SET `' . $course_number_key_to_assign . '` = "' . $_POST[$course_number_key_to_assign] . '" WHERE uid = "' . $uid_input . '";';
									mysqli_query($conn, $q) or die('Błąd podczas odczytu danych.');
									mysqli_close($conn);
								
								}
						}
					}
					echo '<script>window.location.replace("?uID=' . $uid_input . '")</script>';
				}
			}
			
			function EnrollmentResults() {
				
				$courses = unserialize(file_get_contents('../courses-db.php'));
				$limits = unserialize(file_get_contents('../limits.php'));
				$session_auto_control = file_get_contents('../session-auto-control.php');
				$session_closing_time = file_get_contents('../session-closing-time.php');
				$current_time = time();
				global $db_host, $db_user, $db_pass, $db_name;

				echo '<br><br>';
				
				
				echo '<main>
					
						<header class="container-small knsa-kul">
								<img src="../knsa-logo.png" alt="" class="img-knsa">
								<img src="../kul.jpg" alt="" class="img-kul">
								<div>'; Value('../institution-name.php'); echo '</div>
						</header>
							
								<div class="flag"></div>
							
						<section class="container-small container-event-name-date">
								<div class="event-name">'; Value('../event-name.php'); echo '</div>
						</section>

						<section id="input-solution" class="container-small result-form">
							
								<p>Wyniki:</p>';
								if ($session_auto_control && $session_closing_time != 2145913200) {
									echo '<div class="remaining-time"><b><p id="remaining-time-header">Do końca zapisów</p><p id="remaining-time"></p></b></div>
									<script>var closingTime = ' . $session_closing_time . ';</script><script src="remaining-time.js"></script>';
								}
								
							for ($course_number = 1; $course_number <= count($courses); $course_number++) {
						
								for ($group_number = 1; $group_number <= $courses[array_keys($courses)[$course_number-1]]; $group_number++) {
								
									echo '<table>
									<tr>
										<th colspan="3">
											<p>' . array_keys($courses)[$course_number-1] . '</p>
											<p>(grupa ' . $group_number . ')</p>';
											echo ($limits[$course_number-1] === 0) ? '':'<p style="font-size: 0.75rem;">Limit miejsc: ' . $limits[$course_number-1] . '</p>';
										echo '</th>
									</tr>
									<tr>
										<th style="word-break:normal;">Lp</th>
										<th>Imię i nazwisko</th>
										<th>Kiedy zapisano</th>
									</tr>';
									
									$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
									mysqli_set_charset($conn, 'utf8');
									$q = 'SELECT name_surname, group_for_course_' . $course_number . '_when FROM uid_db WHERE group_for_course_' . $course_number . ' = "' . $group_number . '" ORDER BY group_for_course_' . $course_number . '_when;';
									$result = mysqli_query($conn, $q) or die('Błąd podczas odczytu danych.');
									mysqli_close($conn);
									
									$enr_results = mysqli_fetch_all($result, MYSQLI_ASSOC);

									
											if (empty($enr_results)) {
												echo '<tr><td colspan="3">Lista jest pusta!</td></tr>';
											}
											else {
												
												$key_to_check_when = 'group_for_course_';
												$key_to_check_when .= $course_number;
												$key_to_check_when .= '_when';
												
												$lp = 1;
												foreach ($enr_results as $keynumber => $details) {
														echo 	'<tr>
																<td>' . $lp . '</td>';
													foreach ($details as $key => $value) {
														
														if ($key === 'name_surname') {
															echo 	'<td>' . $value . '</td>';
														}
														else if ($key === $key_to_check_when) {
															echo 	'<td>' . date('d M Y H:i:s', $value) . '</td>';
														}
													}
													echo '</tr>';
													$lp++;
												}
											}
									echo '</table>';
								}
							}
						echo '
						<button style="margin-top:15px;" class="action-button enrollment-submit-button" onclick="location.href=\'../\';">Powrót do menu</button>
						
						</section>

					</main>';
			}
?>