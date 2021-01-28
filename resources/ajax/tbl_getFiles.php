<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
if(strlen($_POST['psgc_mun'])>=9){
    $app->tbl_getFiles($_POST['psgc_mun'],'',$_POST['cycle_id']);
}else{
    $app->tbl_getFiles('',$_POST['psgc_mun'],$_POST['cycle_id']);
}

