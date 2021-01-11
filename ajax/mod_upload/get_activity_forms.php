<?php
include_once("../../../Mrms/Database.php");
include_once("../../../Mrms/App.php");
include_once("../../../Mrms/Auth.php");
include_once("../../../Mrms/Ceac.php");
include_once("../../../Mrms/Dqa.php");
include_once("../../../Mrms/User.php");
$auth = new \Mrms\Auth();
$dqa = new \Mrms\Dqa();
$user = new \Mrms\User();
if ($auth->is_loggedIn()) {
    if (strlen($_POST['psgc_mun']) == 9) {
        $activity_form = $dqa->activity_form($_POST['psgc_mun'], $_POST['cycle'], '');
    } else {
        $activity_form = $dqa->activity_form('', $_POST['cycle'], $_POST['psgc_mun']);
    }
    $ar = array('activity_form' => $activity_form);
    echo json_encode($ar, JSON_PRETTY_PRINT);
}
