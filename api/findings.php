<?php
include_once("../app/Database.php");
include_once("../app/App.php");;
include_once("../app/Ceac.php");
header('Content-type:application/json;charset=utf-8');
header('Access-Control-Allow-Origin:*');
$app = new \app\App();
$app->api_findings();