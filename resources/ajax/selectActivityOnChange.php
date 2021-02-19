<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
echo '<pre>';
echo '<br>';

$modality_id=implode($_POST['cycle_id'],"");
$modality_id = '"'.implode(', ', $_POST['cycle_id']).'"';
