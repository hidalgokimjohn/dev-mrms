<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
if (isset($_POST['stage_id'])) {
    $stage_id = implode(', ', $_POST['stage_id']);
    echo $activities = $app->searchSelectActivity($_GET['modality'], $stage_id);
} else {
    echo 'false';
}
