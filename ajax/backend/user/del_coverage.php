<?php
	include_once("../../../../Mrms/Database.php");
	include_once("../../../../Mrms/App.php");
	include_once("../../../../Mrms/Auth.php");
	include_once("../../../../Mrms/User.php");
	include_once("../../../../Mrms/City.php");
	$auth = new \Mrms\Auth();
	if ($auth->is_loggedIn()) {
		$user = new \Mrms\User();
		//echo $_POST['username'];
		$user->remove_cityCoverage($_POST['username'],$_POST['city_id']);
	}