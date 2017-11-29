<?php
	require_once("db.php");
	$database = new DB();
	print_r($database->get_all("select * from million_member"));
?>