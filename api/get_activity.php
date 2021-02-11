<?php
include_once("../app/Database.php");
include_once("../app/App.php");;
include_once("../app/Ceac.php");
header('Content-type:application/json;charset=utf-8');

$api = new \app\App();
$api->apiActivity($_GET['stage_id']);