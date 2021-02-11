<?php
include_once("../app/Database.php");
include_once("../app/App.php");;
include_once("../app/Ceac.php");
header('Content-type:application/json;charset=utf-8');
header('Access-Control-Allow-Origin:*');
$api = new \app\App();
$api->apiCategory('ipcdd_drom');