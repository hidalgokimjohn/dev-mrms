<?php
	include_once("../../../Mrms/Database.php");
	include_once("../../../Mrms/App.php");
	include_once("../../../Mrms/Auth.php");
	include_once("../../../Mrms/Ceac.php");
	include_once("../../../Mrms/Upload.php");
	$auth = new \Mrms\Auth();
	$uploaded_files = new \Mrms\Upload();
	if ($auth->is_loggedIn()) {
		$uploaded_files->tbl_latestUpload();
	}