<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
if (isset($_POST['modality_id'])) {
    echo $app->searchGetCycles_json($_POST['modality_id']);
} else {
    echo 'false';
}
