<?php
include_once("../../../../Mrms/Database.php");
include_once("../../../../Mrms/App.php");
include_once("../../../../Mrms/Auth.php");
include_once("../../../../Mrms/User.php");
include_once("../../../../Mrms/City.php");
$auth = new \Mrms\Auth();
$app = new \Mrms\App();
if ($auth->is_loggedIn()) {
    $user = new \Mrms\User();
    $app->edit_target($_POST['form_id'], $_POST['form_target']);
}