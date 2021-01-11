<?php
include_once("../../../Mrms/Database.php");
include_once("../../../Mrms/App.php");
include_once("../../../Mrms/Auth.php");
include_once("../../../Mrms/Ceac.php");
$auth = new \Mrms\Auth();
$app = new \Mrms\App();
if ($auth->is_loggedIn()) {
    if ($_POST['cycle_id']) {
        $muni = $app->getMuni($_POST['cycle_id'], $_POST['modality_group']);
        $ar = array('muni' => $muni);
        echo json_encode($ar, JSON_PRETTY_PRINT);
    }

}


