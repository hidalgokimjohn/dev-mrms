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
    if(strlen($_POST['psgc_mun'])>=9){
        $upload->tbl_select_file_to_dqa($_POST['psgc_mun'],'',$_POST['cycle_id']);
    }else{
        $upload->tbl_select_file_to_dqa('',$_POST['psgc_mun'],$_POST['cycle_id']);
    }

}
