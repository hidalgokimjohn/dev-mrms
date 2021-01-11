<?php
    include_once("../../app/Database.php");
	include_once("../../app/App.php");
	include_once("../../app/Auth.php");
	include_once("../../app/Ceac.php");
	include_once("../../app/Dqa.php");
	$auth = new \app\Auth();
	$dqa = new \app\Dqa();
	if ($auth->loggedIn()) {
		$dqa->table_conducted_dqa();
	}