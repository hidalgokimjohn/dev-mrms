<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Ceac.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();


if($_POST['withFindings']=='yes'){
	$required_fields = array("withFindings", "typeOfFindings", "textFindings", "responsiblePerson","dateOfCompliance");

	foreach ($required_fields as $field) {
	    if (!strlen($_POST[$field])) {
	         $error[]=$field." is required";
	    }
	}
	if(!empty($error)){
		echo 'error_on_required_fields';
	}else{
		echo ($app->submitWithFinding())?'submitted':'submit_error';
	}
}

if($_POST['withFindings']=='ta'){
	$required_fields = array("textFindings");

	foreach ($required_fields as $field) {
	    if (!strlen($_POST[$field])) {
	         $error[]=$field." is required";
	    }
	}
	if(!empty($error)){
		echo 'error_on_required_fields';
	}else{
		echo ($app->submitGiveTa())?'submitted':'submit_error';
	}
}
if($_POST['withFindings']=='no'){
		echo ($app->submitNoFinding())?'submitted':'submit_error';
}

