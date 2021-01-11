<?php
include_once("../../../Mrms/Database.php");
include_once("../../../Mrms/App.php");
include_once("../../../Mrms/Auth.php");
include_once("../../../Mrms/Ceac.php");
include_once("../../../Mrms/Dqa.php");
include_once("../../../Mrms/User.php");
include_once("../../../Mrms/Upload.php");
$auth = new \Mrms\Auth();
$dqa = new \Mrms\Dqa();
$user = new \Mrms\User();
$upload = new \Mrms\Upload();
if ($auth->is_loggedIn()) {
    if ($upload->check_target($_POST['act_ft']) == 'true') {
        if ($upload->can_upload <=1) {
            if ($upload->checkFileMime()) {
                $upload->upload_file();
            } else {
                echo 'PDF lang. Wag na ipilit ang iba. Masasaktan ka lang';
            }
        } else {
            echo 'The form you select is not yet open for uploading. MOV must go through 1st Level of DQA. Contact MEO for this matter';
        }

    } else {
        echo 'Target reached. Please contact MEO to adjust targets';
    }
}
