<?php session_start();
include_once('../../../Mrms/Database.php');
include_once('../../../Mrms/App.php');
$app = new \app\App();
$app->update_profile_pic();
header('location:../../index.php?p=account');