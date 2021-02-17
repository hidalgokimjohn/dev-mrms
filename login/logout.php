<?php
	include_once('../app/Database.php');
	include_once('../app/App.php');
	$app = new \app\App();
	session_start();
	$app->log($_SESSION['username'], 'logout', 'has logged out', null, null);
    $app->logout();
    header('location: http://auth.caraga.dswd.gov.ph:8080/auth/realms/entdswd.local/protocol/openid-connect/logout?redirect_uri=http://crg-kcapps-svr.entdswd.local/mrms/index.php');
    exit;





