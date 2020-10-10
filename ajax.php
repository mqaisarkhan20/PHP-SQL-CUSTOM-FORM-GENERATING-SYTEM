<?php 

require 'includes/config.php'; 

if (isset($_GET['get_all_forms'])) {
	$forms = $db->multiple_row("SELECT * FROM forms");

	header('Content-Type: application/json');
	echo json_encode(['forms' => $forms]);
}

?>