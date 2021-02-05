<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Ceac.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
if($_POST['finding_id']){
	echo ($app->removeFinding($_POST['finding_id']))?'removed':'';
}