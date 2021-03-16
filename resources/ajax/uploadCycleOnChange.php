<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
if (isset($_POST['cycle_id'])) {
    echo $app->getCeacActivity($_POST['area_id'],$_POST['cycle_id']);
} else {
    echo 'false';
}
