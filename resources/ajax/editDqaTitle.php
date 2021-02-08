<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Ceac.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
if($_POST['staff']!=='' && $_POST['dqaTitle']!==''){
    echo 'submitted';
}else{
    echo 'submit_error';
}