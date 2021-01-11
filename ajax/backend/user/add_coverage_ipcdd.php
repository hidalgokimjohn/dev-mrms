<?php
	include_once("../../../../Mrms/Database.php");
	include_once("../../../../Mrms/App.php");
	include_once("../../../../Mrms/Auth.php");
	include_once("../../../../Mrms/User.php");
	include_once("../../../../Mrms/City.php");
	$auth = new \Mrms\Auth();
	if ($auth->is_loggedIn()) {
		$user = new \Mrms\User();
		if(isset($_POST['username']) && isset($_POST['cadt_id'])){
			$user->add_ipcddCoverage($_POST['username'],$_POST['cadt_id']);
		}
		//echo $_POST['username'];
	}