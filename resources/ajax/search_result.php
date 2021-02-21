<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();

$area_id = $cycle_id= $activity_id = $stage_id=$form_id ='';
if($_POST['action']=='searchFile'){
    if(!empty($_POST['cycle_id'])){
        $cycle_id = implode(', ', $_POST['cycle_id']);
    }
    if(!empty($_POST['activity_id'])){
        $activity_id = implode(', ', $_POST['activity_id']);
    }
    if(!empty($_POST['stage_id'])){
        $stage_id = implode(', ', $_POST['stage_id']);
    }
    if(!empty($_POST['form_id'])){
        $form_id = implode(', ', $_POST['form_id']);
    }
    if(!empty($_POST['area_id'])){
        $area_id = implode(', ', $_POST['area_id']);
    }
    $app->searchFileResults($_GET['modality'],$cycle_id,$stage_id,$activity_id,$form_id,$area_id);
}else{
    echo json_encode(array('data'=>''));
}

