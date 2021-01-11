<?php
include_once("../../../Mrms/Database.php");
include_once("../../../Mrms/App.php");
include_once("../../../Mrms/Auth.php");
include_once("../../../Mrms/Ceac.php");
$auth = new \Mrms\Auth();
$app = new \Mrms\App();
if ($auth->is_loggedIn()) {
    if ($_POST['psgc_mun']) {
        $brgy = $app->getBrgy($_POST['psgc_mun']);
        $ar = array('brgy' => $brgy);
        echo json_encode($ar, JSON_PRETTY_PRINT);
    }
}


