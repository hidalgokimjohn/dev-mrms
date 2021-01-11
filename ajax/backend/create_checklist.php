<?php
	include_once("../../../Mrms/Database.php");
	include_once("../../../Mrms/App.php");
	include_once("../../../Mrms/Auth.php");
	include_once("../../../Mrms/Ceac.php");
	$auth = new \Mrms\Auth();
	if ($auth->is_loggedIn()) {
        $ceac = new \Mrms\Ceac();
        if (isset($_POST['psgc'])) {
            $ceac->create_checklist('ncddp', 'covid-approach', $_POST['psgc'], $_POST['cycle']);
        }
        if (isset($_POST['cadt'])) {
            $ceac->create_checklist_ipcdd('ipcdd', 'version 1', $_POST['cadt'], $_POST['cycle']);
        }
        //$ceac->create_checklist_ipcdd('ipcdd','version 1',1, 10);
    }


