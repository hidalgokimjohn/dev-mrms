<?php
	include_once('../app/Database.php');
	include_once('../app/App.php');
	$app = new \app\App();
	session_start();
	$app->log($_SESSION['username'], 'logout', 'has logged out', null, null);
    $app->logout();




