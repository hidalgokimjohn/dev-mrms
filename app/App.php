<?php

namespace app;

class App
{
    public function connectDatabase()
    {
        $database = Database::getInstance();
        return $database->getConnection();
    }

    public function login_sso($user)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            users.username,
            lib_user_positions.user_position,
            lib_user_positions.user_position_abbrv,
            lib_user_positions.user_group,
            users.`status`,
            personal_info.first_name,
            personal_info.last_name,
            personal_info.pic_url
            FROM
            users
            INNER JOIN personal_info ON personal_info.fk_username = users.username
            left JOIN lib_user_positions ON users.fk_position = lib_user_positions.id
            WHERE users.username = ?");
        $q->bind_param('s', $user);
        $q->execute();

        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['user_status'] = $row['status'];
            $_SESSION['login'] = 'logged_in';
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_position'] = $row['user_position'];
            $_SESSION['user_position_abbrv'] = $row['user_position_abbrv'];
            $_SESSION['user_lvl'] = $row['user_group'];
            $_SESSION['pic_url'] = $row['pic_url'];
            $_SESSION['user_fullname'] = $row['first_name'] . ' ' . $row['last_name'];
        } else {
            return false;
        }
    }

    public function login($user, $pass)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
					users.username,
					users.`password`,
					lib_user_positions.user_position,
					lib_user_positions.user_position_abbrv,
					lib_user_positions.user_group,
					users.`status`,
                    personal_info.first_name,
                    personal_info.last_name,
                    personal_info.pic_url
					FROM
					users
					INNER JOIN personal_info ON personal_info.fk_username = users.username
					INNER JOIN lib_user_positions ON users.fk_position = lib_user_positions.id
					WHERE users.username = ? AND users.status='active'");
        $q->bind_param('s', $user);
        $q->execute();

        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pass, $row['password'])) {
                session_regenerate_id();
                $_SESSION['user_status'] = $row['status'];
                $_SESSION['login'] = 'logged_in';
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_position'] = $row['user_position'];
                $_SESSION['user_position_abbrv'] = $row['user_position_abbrv'];
                $_SESSION['user_lvl'] = $row['user_group'];
                $_SESSION['pic_url'] = $row['pic_url'];
                $_SESSION['user_fullname'] = $row['first_name'] . ' ' . $row['last_name'];

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function logout()
    {
        unset($_SESSION['oauth2state']);
        session_destroy();
    }

    public function log($u, $e, $d, $f, $target_id)
    {
        $mysql = $this->connectDatabase();
        $ip = $_SERVER['REMOTE_ADDR'];
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $q = $mysql->prepare("INSERT INTO `system_logs` (`username`, `event`, `details`, `ip_address`, `created_at`,`file_id`,`target_id`) VALUES (?, ?, ?, ?, ?,?,?)");
        $q->bind_param('ssssssi', $u, $e, $d, $ip, $now, $f, $target_id);
        $q->execute();
        $result = $q->get_result();
        if ($q->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function p_title($p)
    {

        switch ($p) {

            case 'modules';
                $title = '';
                if (isset($_GET['p'])) {
                    $title = "Data Qaulity Assessment | MRMS";
                }
                return $title;

            case 'dashboards';
                $title = '';
                if (isset($_GET['p'])) {
                    $title = "Dashboard";
                }
                if (isset($_GET['m']) && $_GET['m'] == 'mov_uploading_2021') {
                    $title .= " - MOV Uploading 2021 | MRMS";
                }
                if (isset($_GET['m']) && $_GET['m'] == 'mov_uploading_2020') {
                    $title .= " - MOV Uploading 2020 | MRMS";
                }
                if (isset($_GET['m']) && $_GET['m'] == 'mov_reviewed') {
                    $title .= " - MOV Reviewed | MRMS";
                }
                if (isset($_GET['m']) && $_GET['m'] == 'exec_db') {
                    $title .= " - Executive | MRMS";
                }
                return $title;
            default:
                echo 'MRMS | Home';
                break;
        }
    }

    public function sidebar_active($m, $url)
    {
        echo ($m == $url) ? 'active' : '';
    }

    public function sidebar_showList($m, $url)
    {
        echo ($m == $url) ? 'show' : '';
    }

    public function page_footer()
    {
        echo '<div class="footer fixed"><div class="pull-right"></div><div>
        &copy; ' . date("Y ") . ' DSWD CARAGA Kalahi-CIDSS | Monitoring & Evaluation Unit.</div></div>';
    }

    public function getCities()
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            lib_region.psgc_region,
            lib_municipality.psgc_mun,
            lib_municipality.mun_name
            FROM
            lib_municipality
            INNER JOIN lib_province ON lib_municipality.psgc_province = lib_province.psgc_province
            INNER JOIN lib_region ON lib_province.psgc_region = lib_region.psgc_region
            WHERE lib_region.psgc_region='160000000' ORDER BY lib_municipality.mun_name ASC");
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function getCadt()
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT * FROM lib_cadt ORDER BY lib_cadt.id ASC");
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function getCycle($year, $modality)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
                cycles.id,
                lib_cycle.cycle_name,
                lib_modality.modality_name,
                cycles.batch,
                cycles.`year`,
                cycles.`status`
                FROM
                cycles
                INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE cycles.`year`= ? AND lib_modality.modality_group in ('ncddp_drom','ipcdd_drom','ipcdd','ncddp') AND lib_modality.modality_name=?");
        $q->bind_param('is', $year, $modality);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function getStaffs($position)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
				CONCAT(personal_info.first_name,' ',personal_info.last_name) as fullname,
				personal_info.fk_username,
				lib_user_positions.user_position
				FROM
				users
				INNER JOIN personal_info ON personal_info.fk_username = users.username
				INNER JOIN lib_user_positions ON users.fk_position = lib_user_positions.id
				WHERE lib_user_positions.user_position_abbrv IN ($position)";
        var_dump($q);
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function getTypeOfFindings()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                lib_findings.id,
                lib_findings.findings_type
                FROM
                lib_findings";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function tbl_dqaConducted()
    {
        $mysql = $this->connectDatabase();
        include_once 'User.php';
        $user = new User();
        $user->info($_SESSION['username']);
        if ($user->position_abbrv !== 'RMES') {
            $where_con = ' AND m.conducted_by="' . $_SESSION['username'] . '"';
        }
        $q = "SELECT
                DATE_FORMAT(m.created_at, '%Y/%m/%d'),
                lib_municipality.mun_name,
                m.title,
                CONCAT(responsible_of.first_name,' ',responsible_of.last_name) AS responsible_person,
                CONCAT(conducted_bys.first_name,' ',conducted_bys.last_name) AS conducted_by,
                responsible_of.first_name,
                responsible_of.last_name,
                conducted_bys.first_name,
                conducted_bys.last_name,
                m.dqa_guid,
                m.fk_psgc_mun,
                m.fk_cycle,
                lib_cadt.cadt_name,
                lib_cadt.id,
                m.id
            FROM
                tbl_dqa AS m
            INNER JOIN users AS u1 ON (u1.username = m.responsible_person)
            INNER JOIN users AS u2 ON (u2.username = m.conducted_by)
            INNER JOIN personal_info AS conducted_bys ON conducted_bys.fk_username = u2.username
            INNER JOIN personal_info AS responsible_of ON responsible_of.fk_username = u1.username
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = m.fk_psgc_mun
            INNER JOIN cycles ON cycles.id = m.fk_cycle
            INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
            LEFT JOIN lib_cadt ON lib_cadt.id = m.fk_cadt_id
            WHERE 1 = 1 ";
        $q .= $where_con;
        $result = $mysql->query($q) or die($mysql->error);
        while ($row = $result->fetch_row()) {
            $data[] = $row;
        }
        $json_data = array("data" => $data);
        echo json_encode($json_data);
    }

    public function tbl_dqaItems()
    {
        $mysql = $this->connectDatabase();
        $dqaId = $_GET['dqaId'];
        $q = "SELECT
                lib_municipality.mun_name,
                lib_barangay.brgy_name,
                CONCAT(lib_form.form_name,IF (lib_barangay.brgy_name IS NOT NULL,', ',''),COALESCE (lib_barangay.brgy_name,'')) AS forms,
                form_uploaded.original_filename,
                CONCAT(personal_info.first_name,' ',personal_info.last_name) AS fullname,
                personal_info.first_name,
                personal_info.last_name,
                form_uploaded.reviewed_by,
                form_uploaded.is_reviewed,
                form_uploaded.with_findings,
                form_uploaded.is_findings_complied,
                form_uploaded.file_id,
                form_uploaded.file_path,
                lib_cadt.cadt_name,
                form_target.ft_guid,
                tbl_dqa_list.created_at
            FROM
            tbl_dqa
            INNER JOIN tbl_dqa_list ON tbl_dqa.dqa_guid = tbl_dqa_list.fk_dqa_guid
            LEFT JOIN form_uploaded ON form_uploaded.file_id = tbl_dqa_list.fk_file_guid
            LEFT JOIN form_target ON form_target.ft_guid = tbl_dqa_list.ft_guid OR form_target.ft_guid = form_uploaded.fk_ft_guid
            LEFT JOIN users ON users.username = form_uploaded.uploaded_by
            LEFT JOIN personal_info ON personal_info.fk_username = users.username
            LEFT JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            LEFT JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            WHERE
             tbl_dqa.dqa_guid='$dqaId' AND tbl_dqa_list.is_delete='0' AND (form_uploaded.is_deleted='0' OR form_uploaded.is_deleted is null)";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }
        } else {
            $data = '';
        }

        $json_data = array("data" => $data);
        echo json_encode($json_data);
    }

    public function tbl_getFiles($fk_psgc_mun, $cadt_id, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $fk_psgc_mun = $mysql->real_escape_string($fk_psgc_mun);
        $cadt_id = $mysql->real_escape_string($cadt_id);
        $cycle_id = $mysql->real_escape_string($cycle_id);
        $username = $_SESSION['username'];
        $q = "SELECT
                form_uploaded.file_id,
                form_target.ft_guid,
                form_uploaded.original_filename,
                form_uploaded.file_path,
                form_uploaded.date_uploaded,
                COALESCE (
                    lib_barangay.brgy_name,
                    lib_municipality.mun_name,
                    lib_cadt.cadt_name,
                    'n/a'
                ) AS location,
                lib_form.form_name,
                lib_form.form_type,
                form_uploaded.uploaded_by,
                tbl_dqa_list.fk_dqa_guid,
                tbl_dqa_list.is_delete,
                tbl_dqa_list.ft_guid
            FROM
                form_target
            LEFT JOIN form_uploaded ON form_uploaded.fk_ft_guid = form_target.ft_guid
            LEFT JOIN tbl_dqa_list ON tbl_dqa_list.fk_file_guid = form_uploaded.file_id
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            LEFT JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            LEFT JOIN lib_form ON lib_form.form_code = form_target.fk_form
            WHERE
                (
                    (
                        form_target.fk_psgc_mun = '$fk_psgc_mun'
                        OR form_target.fk_cadt = '$cadt_id'
                    )
                    AND form_target.fk_cycle = '$cycle_id'
                    AND (
                        tbl_dqa_list.is_delete = 0
                        OR tbl_dqa_list.is_delete IS NULL
                        OR tbl_dqa_list.fk_file_guid IS NULL
                    )
                    AND form_uploaded.is_deleted = 0
                    AND (
                        form_target.ft_guid NOT IN (
                            SELECT
                                form_target.ft_guid
                            FROM
                                tbl_dqa_list
                            LEFT JOIN form_target ON form_target.ft_guid = tbl_dqa_list.ft_guid
                            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
                            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
                            LEFT JOIN form_uploaded ON form_uploaded.file_id = tbl_dqa_list.fk_file_guid
                            WHERE
                                added_by = '$username'
                            AND (
                                form_target.fk_psgc_mun = '$fk_psgc_mun'
                                OR form_target.fk_cadt = '$cadt_id'
                            )
                            AND form_target.fk_cycle = '$cycle_id')))";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_row()) {
                $data[] = $row;
            }
        } else {
            $data = '';
        }
        $json_data = array("data" => $data);
        echo json_encode($json_data);
    }

    public function getDqaInfo($dqaId)
    {
        $mysql = $this->connectDatabase();
        $dqaId = $mysql->real_escape_string($dqaId);
        $q = "SELECT
                COALESCE(tbl_dqa.fk_psgc_mun,tbl_dqa.fk_cadt_id,'n/a') as area_id,
                tbl_dqa.fk_cycle,
                tbl_dqa.responsible_person,
                tbl_dqa.conducted_by,
                tbl_dqa.created_at,
                tbl_dqa.dqa_status
                FROM
                tbl_dqa
                WHERE dqa_guid='$dqaId'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return false;
        }
    }

    public function addFile()
    {
        $mysql = $this->connectDatabase();
        $ceac = new Ceac();
        $f_guid = $ceac->v4();
        $ft_guid = $mysql->real_escape_string($_POST['ftGuid']);
        $dqa_id = $mysql->real_escape_string($_POST['dqaId']);
        $file_id = $mysql->real_escape_string($_POST['fileId']);
        if ($_POST['fileId'] !== '') {
            !
            $q = "INSERT INTO `tbl_dqa_list` (`fk_file_guid`,`fk_dqa_guid`, `added_by`, `created_at`, `is_delete`,`ft_guid`)
            VALUES ('$file_id','$dqa_id', '$_SESSION[username]', NOW(), '0','$ft_guid')";
        } else {
            $q = "INSERT INTO `tbl_dqa_list` (`fk_file_guid`,`fk_dqa_guid`, `added_by`, `created_at`, `is_delete`,`ft_guid`)
            VALUES (null,'$dqa_id', '$_SESSION[username]', NOW(), '0','$ft_guid')";
        }
        if ($ft_guid) {
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                if ($file_id !== null) {
                    $q = "UPDATE `form_uploaded` SET `is_added_to_dqa`='0' WHERE (`file_id`='$file_id') LIMIT 1";
                    $mysql->query($q) or die($mysql->error);
                }
                echo 'added';
            }
        }
    }

    public function updateDqaList()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                tbl_dqa_list.id,
                tbl_dqa_list.fk_file_guid
                FROM
                tbl_dqa_list";
        $result = $mysql->query($q);
        while ($row = $result->fetch_assoc()) {
            $q1 = "SELECT
                    form_uploaded.fk_ft_guid
                    FROM
                    form_uploaded
                    WHERE file_id='$row[fk_file_guid]'";
            $result1 = $mysql->query($q1);
            while ($row1 = $result1->fetch_assoc()) {
                $q2 = "UPDATE `tbl_dqa_list` SET `ft_guid`='$row1[fk_ft_guid]' WHERE (`fk_file_guid`='$row[fk_file_guid]')";
                $result2 = $mysql->query($q2) or die($mysql->error);
                if ($result2) {
                    echo 'updated<br>';
                }
            }
        }
    }

    public function getRelatedFiles($ft_guid)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                lib_activity.id,
                form_target.fk_psgc_mun,
                form_target.fk_psgc_brgy,
                form_target.fk_cycle,
                form_target.fk_cadt
                FROM
                lib_form
                INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
                INNER JOIN form_target ON lib_form.form_code = form_target.fk_form
                INNER JOIN form_uploaded ON form_target.ft_guid = form_uploaded.fk_ft_guid
                WHERE form_target.ft_guid='$ft_guid'";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        $q1 = "SELECT
                lib_activity.id,
                form_uploaded.original_filename,
                form_uploaded.file_path
                FROM
                lib_form
                INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
                INNER JOIN form_target ON lib_form.form_code = form_target.fk_form
                LEFT JOIN form_uploaded ON form_target.ft_guid = form_uploaded.fk_ft_guid
                WHERE lib_activity.id='$row[id]' AND (form_target.fk_psgc_mun='$row[fk_psgc_mun]' OR form_target.fk_cadt='$row[fk_cadt]') AND form_target.fk_psgc_brgy='$row[fk_psgc_brgy]'";
        $result1 = $mysql->query($q1) or die($mysql->error);
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                $data[] = $row1;
            }
            return $data;
        } else {
            return false;
        }

    }

    public function createDqa()
    {
        $mysql = $this->connectDatabase();
        $ceac = new Ceac();
        $dqa_gui = $ceac->v4();

        if (strlen($_POST['municipality']) == 9) {
            $q = $mysql->prepare("INSERT INTO `tbl_dqa` (`dqa_guid`, `fk_psgc_mun`, `fk_cycle`, `title`, `responsible_person`, `conducted_by`, `created_at`,`dqa_status`)
            VALUES (?, ?, ?, ?, ?, ?, NOW(),'not complied')");
        } else {
            $q = $mysql->prepare("INSERT INTO `tbl_dqa` (`dqa_guid`, `fk_cadt_id`, `fk_cycle`, `title`, `responsible_person`, `conducted_by`, `created_at`,`dqa_status`)
            VALUES (?, ?, ?, ?, ?, ?, NOW(),'not complied')");
        }

        $q->bind_param('siisss', $dqa_gui, $_POST['municipality'], $_POST['cycle'], $_POST['dqaTitle'], $_POST['staff'], $_SESSION['username']);
        $q->execute();
        if ($q->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function submitNoFinding()
    {
        $mysql = $this->connectDatabase();
        if ($_GET['file_name'] == 'Not Yet Uploaded') {
            //NYU stands for Not Yet Uploaded
            echo 'notYetUploaded_';
        } else {
            //check if file has previous findings (not complied)
            if (!$this->checkPreviousFindings()) {
                //if no previous findings set no finding.
                $fileId = $_GET['file_id'];
                $q="UPDATE `form_uploaded` SET `with_findings`='no findings', `is_reviewed`='reviewed',`date_reviewed`=NOW() WHERE (`file_id`='$fileId') LIMIT 1";
                $result = $mysql->query($q) or die($mysql->error);
                if($mysql->affected_rows>0){
                    return true;
                }
                return true;
            } else {
                echo 'hasPreviousFindings_';
            }

        }

    }
    public function submitWithFinding()
    {
        $mysql = $this->connectDatabase();
        $guid = new Ceac();
        $finding_guid = $guid->v4();
        $fk_ft_guid = $_GET['ft_guid'];
        $fk_dqa_guid = $_GET['dqa_id'];
        $fileId = $_GET['file_id'];
        $textFindings = $_POST['textFindings'];
        $responsiblePerson = $_POST['responsiblePerson'];
        $typeOfFindings = $_POST['typeOfFindings'];
        $dateOfCompliance = $_POST['dateOfCompliance'];
        $addedBy = $_SESSION['username'];
        $q = "";
        if ($_GET['file_name'] == 'Not Yet Uploaded') {
            $q = "INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`, `fk_dqa_guid`, `fk_findings`, `findings`, `responsible_person`, `is_deleted`, `created_at`, `is_checked`, `added_by`, `dqa_level`, `deadline_for_compliance`) VALUES ('$finding_guid', '$fk_ft_guid', '$fk_dqa_guid', '$typeOfFindings', '$textFindings', '$responsiblePerson', '0', '$dateOfCompliance', '0', '$addedBy', 'field', '$dateOfCompliance')";
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $q = "INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`, `fk_dqa_guid`, `fk_findings`, `findings`, `responsible_person`, `is_deleted`, `created_at`, `is_checked`, `added_by`, `dqa_level`, `deadline_for_compliance`,`fk_file_guid`) VALUES ('$finding_guid', '$fk_ft_guid', '$fk_dqa_guid', '$typeOfFindings', '$textFindings', '$responsiblePerson', '0', NOW(), '0', '$addedBy', 'field', '$dateOfCompliance','$fileId')";
            //Update file status
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                $fileUpdate = "UPDATE `form_uploaded` SET `with_findings`='with findings', `is_reviewed`='reviewed', `reviewed_by`='$addedBy', `date_reviewed`=NOW() WHERE (`file_id`='$fileId') LIMIT 1";
                $resultFileUpdate = $mysql->query($fileUpdate) or die($mysql->error);
                if ($mysql->affected_rows > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

    }
    public function submitGiveTa()
    {
        $mysql = $this->connectDatabase();
        return true;

    }

    public function checkPreviousFindings()
    {
        $mysql = $this->connectDatabase();
        $fk_ft_guid = $_GET['ft_guid'];
        $q = "SELECT
            tbl_dqa_findings.fk_ft_guid
            FROM
            tbl_dqa_findings
            WHERE fk_ft_guid='$fk_ft_guid' AND is_checked=0 AND is_deleted=0";
        $result = $mysql->query($q) or die($mysql->error);
        if ($mysql->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function displayFindings($fileId)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        tbl_dqa_findings.findings,
        tbl_dqa_findings.responsible_person,
        tbl_dqa_findings.deadline_for_compliance,
        tbl_dqa_findings.is_checked,
        tbl_dqa_findings.created_at,
        tbl_dqa_findings.dqa_level,
        tbl_dqa_findings.date_complied,
        tbl_dqa_findings.added_by,
        tbl_dqa_findings.findings_guid
        FROM
        tbl_dqa_findings
        WHERE fk_file_guid='$fileId' AND is_deleted=0";
        $results = $mysql->query($q) or die($mysql->error);
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function findingStatus($id,$deadLineForCompliance)
    {
        $s = ($id == '0') ? '<span class="badge bg-danger"><span class="fa fa-times-circle"></span> Not Complied</span> ' : '<span class="badge bg-success"><span class="fa fa-check-circle"></span> Complied</span> ';
        //0 is not complied
        if($id=='0'){
           return $s.=$this->dueStatus($deadLineForCompliance);
        }else{
            return $s;
        }
    }
    public function userInfo($username)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        *,
        CONCAT(personal_info.first_name,' ',personal_info.last_name) as fullName
        FROM
        personal_info
        WHERE personal_info.fk_username='$username'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['fullName'];
        } else {
            return $row['fullName'] = 'User not found';
        }
    }
    public function dueStatus($deadLineForCompliance)
    {
        $today = date("Y-m-d");
        $dueDate = abs(strtotime($deadLineForCompliance)) - strtotime($today);
        $difference = floor($dueDate / (60 * 60 * 24));
        if ($difference == 0) {
            return '<span class="badge bg-warning"><span class="fa fa-exclamation-circle text-danger"></span> <span class="text-danger">Due Today</span></span>';
        }
        if ($difference <= -1) {
            return '<span class="badge bg-warning"><span class="fa fa-exclamation-circle text-danger"></span> <span class="text-danger">Due now</span></span>';
        }
    }

    public function removeFinding($id){
        $mysql = $this->connectDatabase();
        $q = "UPDATE `tbl_dqa_findings` SET `is_deleted`='1' WHERE (`findings_guid`='$id') LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        if($mysql->affected_rows>0){
            return true;
        }else{
            return false;
        }
    }
    public function noFindings($fileId){
        $mysql = $this->connectDatabase();
        $q = "SELECT
        form_uploaded.file_id,
        form_uploaded.is_reviewed,
        form_uploaded.with_findings
        FROM
        form_uploaded
        WHERE file_id='$fileId' AND is_reviewed='reviewed' AND with_findings='no findings'";
        $result = $mysql->query($q) or die($mysql->error);
        if($result->num_rows>0){
            return true;
        }else{
            return false;
        }
    }
}
