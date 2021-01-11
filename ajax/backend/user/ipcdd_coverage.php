<?php
	include_once("../../../../Mrms/Database.php");
	include_once("../../../../Mrms/App.php");
	include_once("../../../../Mrms/Auth.php");
	include_once("../../../../Mrms/User.php");
	include_once("../../../../Mrms/City.php");
	$auth = new \Mrms\Auth();
	if ($auth->is_loggedIn()) {
		$user = new \Mrms\User();
		if(isset($_POST['username'])){
            $ipcdd_coverage = $user->get_ipccd_coverage($_POST['username']);
            //var_dump($ipcdd_coverage);
            foreach ($ipcdd_coverage as $item){
                $checked = ($item['is_checked']=='checked') ? 'checked' : '';
                echo ' <tr>
                    <td><input name="user_coverage_ipcdd" data-cadtid="'.$item['id'].'" '.$checked.' class="check-ipcdd" type="checkbox"></td>
                    <td>'.$item['cadt_name'].'</td>
                </tr>';
            }
		}
	}