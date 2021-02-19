<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
if (isset($_POST['activity_id'])) {
    $stage_id = implode(', ', $_POST['activity_id']);
    echo $activities = $app->searchSelectForm($stage_id);
} else {
    echo 'false';
}
