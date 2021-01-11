<?php
	include_once("../../../../Mrms/Database.php");
	include_once("../../../../Mrms/App.php");
	include_once("../../../../Mrms/Auth.php");
	include_once("../../../../Mrms/User.php");
	include_once("../../../../Mrms/City.php");
	$auth = new \Mrms\Auth();
	if ($auth->is_loggedIn()) {
		$city = new \Mrms\City();
		$city->city_list('160000000');
}