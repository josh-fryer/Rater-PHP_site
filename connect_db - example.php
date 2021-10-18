<?php
	// replace with your own MySQL details
	DEFINE ('DB_USER', 'REPLACE');
	DEFINE ('DB_PASSWORD', 'REPLACE');
	DEFINE ('DB_HOST', 'REPLACE');
	DEFINE ('DB_NAME', 'REPLACE');

	// Create connection
	$dbc = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	// Check connection
	if ($dbc->connect_error) {
		die("Connection failed: " . $dbc->connect_error);
	}

	$dbc -> set_charset("utf8");
?>

