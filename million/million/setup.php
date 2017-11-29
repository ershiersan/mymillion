<?php
    require_once("config.php");
    require_once("service/db.php");
    DB::init();
	$dataArray = array("introduction"=>"任贤齐吧_百度贴吧");
	$condition = "`title`='richie6'";
	DB::update(DBPREFIX."order", $dataArray, $condition);
?>