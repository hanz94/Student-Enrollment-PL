<?php 	require_once '../db-connect.php';
		include '../functions.php'; 
		$uid_db = unserialize(file_get_contents('../.htuiddb'));
		$session = file_get_contents('../session-status.php');
?>

<!DOCTYPE html>

<html lang="pl-PL">
    <head>
		<meta charset="UTF-8">
        <meta name="robots" content="noindex, nofollow" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="Koło Naukowe Studentów Anglistyki KUL">
		<title><?php Value('../institution-name.php');?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,300;0,600;1,300;1,600&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="../styles.css">
		
		<style>
			:root {
				--margin-from-top: -30px;
			}
		
			body {
				width: unset;
			}
		
			table {
				margin-top: 20px;
				margin-bottom: 10px;
				border: 1px solid #000;
				border-collapse: collapse;
				width: 91%;
			}
			table th, table td {
				border: 1px solid #000;
				padding: 5px;
			}
			
			@media print {
				* {
					box-shadow: none;
				}
				.flag, header, button {
					display: none;
				}
				table {
					page-break-inside: avoid;
				}
			}
		
		</style>
    </head>

    <body>
		<?php
			EnrollmentResults();
		?>
    </body>
</html>