<?php
include_once("../../../Mrms/Database.php");
	include_once("../../../Mrms/App.php");
	include_once("../../../Mrms/Auth.php");
	include_once("../../../Mrms/Ceac.php");
	include_once("../../../Mrms/Dqa.php");
	$auth = new \Mrms\Auth();
	$dqa = new \Mrms\Dqa();
	if ($auth->is_loggedIn()) {
	    if($dqa->delete_finding($_POST['finding_id'])){
	        echo 'deleted';
        }
	}