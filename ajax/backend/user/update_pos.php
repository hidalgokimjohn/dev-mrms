<?php
	include_once("../../../../Mrms/Database.php");
	include_once("../../../../Mrms/App.php");
	include_once("../../../../Mrms/Auth.php");
	include_once("../../../../Mrms/User.php");
	$auth = new \Mrms\Auth();
	if ($auth->is_loggedIn()) {
		$users = new \Mrms\User();
		if($users->update_userPosition($_POST['pos_id'],$_POST['username'])){
			echo 'updated';
		}
	}