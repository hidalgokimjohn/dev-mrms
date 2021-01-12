<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
include_once("../../app/Ceac.php");
include_once("../../app/Dqa.php");
include_once("../../app/Upload.php");
include_once("../../app/User.php");
$auth = new \app\Auth();
$dqa = new \app\Dqa();
$user = new \app\User();
$upload = new \app\Upload();
if ($auth->loggedIn()) {
        $upload->viewDqaItems();
}
