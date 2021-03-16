<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
if (isset($_GET['form_id'])) {
    echo $app->uploadFile();
} else {
    echo 'false';
}
