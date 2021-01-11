<?php
include_once("../../../Mrms/Database.php");
include_once("../../../Mrms/App.php");
include_once("../../../Mrms/Auth.php");
include_once("../../../Mrms/Ceac.php");
$auth = new \Mrms\Auth();
$app = new \Mrms\App();
if ($auth->is_loggedIn()) {
    if ($_POST['modality_id']) {
        $cycles = $app->getCycles();
        $ar = array('cycles' => $cycles);
        echo json_encode($ar, JSON_PRETTY_PRINT);
    }

}


