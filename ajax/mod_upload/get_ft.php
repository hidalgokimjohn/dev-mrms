<?php
include_once("../../../Mrms/Database.php");
include_once("../../../Mrms/App.php");
include_once("../../../Mrms/Auth.php");
include_once("../../../Mrms/Ceac.php");
include_once("../../../Mrms/Dqa.php");
include_once("../../../Mrms/User.php");
$auth = new \Mrms\Auth();
$ceac = new \Mrms\Ceac();
$user = new \Mrms\User();
if ($auth->is_loggedIn()) {
    $activity = '';
    if (isset($_POST['activity_id'])) {
        $activity = $_POST['activity_id'];
    }
    if (strlen($_POST['psgc_mun']) == 9) {
        $ceacForms = $ceac->getForms($_POST['psgc_mun'], $_POST['cycle'], '', $activity);
    } else {
        $ceacForms = $ceac->getForms('', $_POST['cycle'], $_POST['psgc_mun'], $activity);
    }

    $ar = array("forms_to_upload" => $ceacForms);
    echo json_encode($ar, JSON_PRETTY_PRINT);
}
