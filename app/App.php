<?php

namespace app;

class App
{
    public $firstName;
    public $midName;
    public $lastName;
    public $sectorName;
    public $sectorDesc;
    public $status;
    public $posName;
    public $posDesc;
    public $officeName;
    public $officeDesc;


    public function connectDatabase()
    {
        $database = Database::getInstance();
        return $database->getConnection();
    }

    public function connectHREDatabase()
    {
        $database = Database::getInstance();
        return $database->getConnectionKCPIS();
    }

    //generate GUID
    public function v4()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public function login_sso($user)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            tbl_person_info.first_name,
            tbl_person_info.mid_name,
            tbl_person_info.last_name,
            tbl_person_info.`status`,
            tbl_person_info.position_name,
            tbl_person_info.position_desc,
            tbl_person_info.office_name,
            tbl_person_info.office_desc,
            tbl_person_info.sector_name,
            tbl_person_info.sector_desc,
            tbl_users.id_number,
            tbl_users.username
            FROM
            tbl_users
            INNER JOIN tbl_person_info ON tbl_person_info.fk_id_number = tbl_users.id_number
            WHERE tbl_users.username = ?");
        $q->bind_param('s', $user);
        $q->execute();

        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['id_number'] = $row['id_number'];
            $_SESSION['user_status'] = $row['status'];
            $_SESSION['login'] = 'logged_in';
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_position'] = $row['position_name'];
            $_SESSION['user_position_desc'] = $row['position_desc'];
            $_SESSION['user_lvl'] = $row['office_name'];
            $_SESSION['user_fullname'] = $row['first_name'] . ' ' . $row['last_name'];
        } else {
            return false;
        }
    }

    public function login($user, $pass)
    {

        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            tbl_person_info.first_name,
            tbl_person_info.mid_name,
            tbl_person_info.last_name,
            tbl_person_info.`status`,
            tbl_person_info.position_name,
            tbl_person_info.position_desc,
            tbl_person_info.office_name,
            tbl_person_info.office_desc,
            tbl_person_info.sector_name,
            tbl_person_info.sector_desc,
            tbl_users.id_number,
            tbl_users.username,
            tbl_users.password
            FROM
            tbl_users
            INNER JOIN tbl_person_info ON tbl_person_info.fk_id_number = tbl_users.id_number
            WHERE tbl_users.username = ?");
        $q->bind_param('s', $user);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pass, $row['password'])) {
                session_regenerate_id();
                $_SESSION['id_number'] = $row['id_number'];
                $_SESSION['user_status'] = $row['status'];
                $_SESSION['login'] = 'logged_in';
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_position'] = $row['position_name'];
                $_SESSION['user_position_desc'] = $row['position_desc'];
                $_SESSION['user_lvl'] = $row['office_name'];
                $_SESSION['user_fullname'] = $row['first_name'] . ' ' . $row['last_name'];
                return true;
            } else {
                return false;
            }
        } else {
            // return false;
            header('location: register.php?p=id_number');
            exit();
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
                    if (isset($_GET['title'])) {
                        $title .= $_GET['title'] . " |";
                    }
                    $title .= " Data Quality Assessment | MRMS";
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
            case 'user_mngt';
                if (isset($_GET['p']) && $_GET['p'] == 'user_mngt') {
                    $title = "User Management | MRMS";
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

    public function sidebar_collapsed($m, $url)
    {
        echo ($m !== $url) ? 'collapsed' : '';
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

    public function getCities($status, $modalityGroup)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
        lib_municipality.mun_name,
        lib_modality.modality_group,
        cycles.`status`
        FROM
        implementing_muni_ncddp
        INNER JOIN lib_municipality ON lib_municipality.psgc_mun = implementing_muni_ncddp.fk_psgc_mun
        INNER JOIN cycles ON cycles.id = implementing_muni_ncddp.fk_cycles
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE cycles.`status`='$status' AND lib_modality.modality_group='$modalityGroup'
        ORDER BY lib_municipality.mun_name ASC");
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

    public function getCadt($status, $modalityGroup)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
        lib_cadt.id,
        lib_cadt.cadt_name
        FROM
        implementing_cadt_ipcdd
        INNER JOIN cycles ON cycles.id = implementing_cadt_ipcdd.fk_cycles
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        INNER JOIN lib_cadt ON lib_cadt.id = implementing_cadt_ipcdd.fk_cadt
        WHERE cycles.`status`='$status' AND lib_modality.modality_group='$modalityGroup'
        ORDER BY lib_cadt.cadt_name ASC");
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

    public function searchGetCadt($modalityGroup)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
        lib_cadt.id,
        lib_cadt.cadt_name
        FROM
        implementing_cadt_ipcdd
        INNER JOIN cycles ON cycles.id = implementing_cadt_ipcdd.fk_cycles
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        INNER JOIN lib_cadt ON lib_cadt.id = implementing_cadt_ipcdd.fk_cadt
        WHERE lib_modality.modality_group='$modalityGroup'
        ORDER BY lib_cadt.cadt_name ASC");
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

    public function searchGetMuni($modalityGroup)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
        lib_municipality.psgc_mun,
        lib_municipality.mun_name
        FROM
        implementing_muni_ncddp
        INNER JOIN cycles ON cycles.id = implementing_muni_ncddp.fk_cycles
        INNER JOIN lib_municipality ON lib_municipality.psgc_mun = implementing_muni_ncddp.fk_psgc_mun
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE lib_modality.modality_group='$modalityGroup'
        ORDER BY lib_municipality.mun_name ASC");
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

    public function searchGetCycles($modalityGroup)
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
                WHERE  lib_modality.modality_group=?");
        $q->bind_param('s', $modalityGroup);
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

    public function searchSelectStage($modalityGroup)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = $mysql->prepare("SELECT
	lib_category.id,
	lib_category.category_name
FROM
	lib_category
	INNER JOIN lib_modality ON lib_category.fk_modality = lib_modality.id 
WHERE
	lib_modality.modality_group = ? ORDER BY lib_category.id ASC");
        $q->bind_param('s', $modalityGroup);
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

    public function searchSelectActivity($modalityGroup, $stage_id)
    {
        $mysql = $this->connectDatabase();
        $stage_id = $mysql->real_escape_string($stage_id);
        $q = "SELECT
	lib_activity.id as value,
	lib_activity.activity_name as label
FROM
	lib_category
	INNER JOIN lib_modality ON lib_category.fk_modality = lib_modality.id
	INNER JOIN lib_activity ON lib_activity.fk_category = lib_category.id 
WHERE
	lib_modality.modality_group = '$modalityGroup' AND lib_category.id IN ($stage_id)";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return json_encode($data);
        } else {
            return false;
        }
    }

    public function searchSelectForm($stage_id)
    {
        $mysql = $this->connectDatabase();
        $stage_id = $mysql->real_escape_string($stage_id);
        $q = "SELECT
        lib_form.id as value,
        lib_form.form_name as label
    FROM
        lib_form
        WHERE lib_form.fk_activity IN ($stage_id)";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return json_encode($data);
        } else {
            return false;
        }
    }

    public function getCycle($status, $modalityGroup)
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
                WHERE cycles.`status`=? AND lib_modality.modality_group=?");
        $q->bind_param('ss', $status, $modalityGroup);
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
        $modalityGroup = $mysql->real_escape_string($_GET['modality']);
        //TODO add where modality
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
                lib_modality.modality_group,
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
            INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
            WHERE m.conducted_by='$_SESSION[username]'
            AND lib_modality.modality_group='$modalityGroup'";
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
                tbl_dqa_list.created_at,
                tbl_dqa_list.id,
                tbl_dqa_list.added_by
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
                $row['17'] = $this->userInfo($row['17']);
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
                        OR form_uploaded.is_deleted = 0
                        OR form_uploaded.is_deleted is NULL
                        
                    )
                    AND form_uploaded.is_compliance is null
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
                                tbl_dqa_list.added_by = '$username'
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
            !$q = "INSERT INTO `tbl_dqa_list` (`fk_file_guid`,`fk_dqa_guid`, `added_by`, `created_at`, `is_delete`,`ft_guid`)
            VALUES ('$file_id','$dqa_id', '$_SESSION[username]', NOW(), '0','$ft_guid')";
        } else {
            $q = "INSERT INTO `tbl_dqa_list` (`fk_file_guid`,`fk_dqa_guid`, `added_by`, `created_at`, `is_delete`,`ft_guid`)
            VALUES (null,'$dqa_id', '$_SESSION[username]', NOW(), '0','$ft_guid')";
        }
        if ($ft_guid) {
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                if ($file_id !== null) {
                    $q = "UPDATE `form_uploaded` SET `is_added_to_dqa`='1' WHERE (`file_id`='$file_id') LIMIT 1";
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
        $listId = $_GET['list_id'];

        if ($_GET['file_name'] == 'Not Yet Uploaded') {
            //NYU stands for Not Yet Uploaded
            echo 'notYetUploaded_';
        } else {
            //check if file has previous findings (not complied)
            if (!$this->checkPreviousFindings()) {
                //if no previous findings set no finding.
                $fileId = $_GET['file_id'];
                $q = "UPDATE `form_uploaded` SET `with_findings`='no findings', `is_reviewed`='reviewed',`date_reviewed`=NOW() WHERE (`file_id`='$fileId') LIMIT 1";
                $result = $mysql->query($q) or die($mysql->error);
                if ($mysql->affected_rows > 0) {
                    $listUpdate = "UPDATE `tbl_dqa_list` SET `is_reviewed`='reviewed' WHERE (`id`='$listId') LIMIT 1";
                    $mysql->query($listUpdate);
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
        $finding_guid = $this->v4();
        $fk_ft_guid = $_GET['ft_guid'];
        $fk_dqa_guid = $_GET['dqa_id'];
        $fileId = $_GET['file_id'];
        $listId = $_GET['list_id'];
        $textFindings = $mysql->real_escape_string($_POST['textFindings']);
        $responsiblePerson = $_POST['responsiblePerson'];
        $typeOfFindings = $_POST['typeOfFindings'];
        $dateOfCompliance = $_POST['dateOfCompliance'];
        $addedBy = $_SESSION['username'];
        $dqaLevel = $_POST['dqaLevel'];
        $q = "";
        if ($_GET['file_name'] == 'Not Yet Uploaded') {
            $q = "INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`, `fk_dqa_guid`, `fk_findings`, `findings`, `responsible_person`, `is_deleted`, `created_at`, `is_checked`, `added_by`, `dqa_level`, `deadline_for_compliance`) VALUES ('$finding_guid', '$fk_ft_guid', '$fk_dqa_guid', '$typeOfFindings', '$textFindings', '$responsiblePerson', '0', NOW(), '0', '$addedBy', '$dqaLevel', '$dateOfCompliance')";
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                $listUpdate = "UPDATE `tbl_dqa_list` SET `is_reviewed`='reviewed',`in_hard_copy`='yes' WHERE (`id`='$listId') LIMIT 1";
                $resultFileUpdate = $mysql->query($listUpdate) or die($mysql->error);
                return true;
            } else {
                return false;
            }
        } else {
            $q = "INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`, `fk_dqa_guid`, `fk_findings`, `findings`, `responsible_person`, `is_deleted`, `created_at`, `is_checked`, `added_by`, `dqa_level`, `deadline_for_compliance`,`fk_file_guid`) VALUES ('$finding_guid', '$fk_ft_guid', '$fk_dqa_guid', '$typeOfFindings', '$textFindings', '$responsiblePerson', '0', NOW(), '0', '$addedBy', '$dqaLevel', '$dateOfCompliance','$fileId')";
            //Update file status
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                $listUpdate = "UPDATE `tbl_dqa_list` SET `is_reviewed`='reviewed' WHERE (`id`='$listId') LIMIT 1";
                $resultFileUpdate = $mysql->query($listUpdate) or die($mysql->error);
                $fileUpdate = "UPDATE `form_uploaded` SET `with_findings`='with findings', `is_reviewed`='reviewed', `date_reviewed`=NOW() WHERE (`file_id`='$fileId') LIMIT 1";
                $mysql->query($fileUpdate) or die($mysql->error);
                return true;
            } else {
                return false;
            }
        }
    }

    public function submitGiveTa()
    {
        $mysql = $this->connectDatabase();
        $mysql = $this->connectDatabase();
        $finding_guid = $this->v4();
        $fk_ft_guid = $_GET['ft_guid'];
        $fk_dqa_guid = $_GET['dqa_id'];
        $fileId = $_GET['file_id'];
        $textFindings = $_POST['textFindings'];
        $listId = $_GET['list_id'];
        $addedBy = $_SESSION['username'];
        $dqaLevel = $_POST['dqaLevel'];
        $q = "";
        if ($_GET['file_name'] == 'Not Yet Uploaded') {
            $q = "INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`, `fk_dqa_guid`,`findings`, `is_deleted`, `created_at`, `added_by`, `dqa_level`,`technical_advice`) VALUES ('$finding_guid', '$fk_ft_guid', '$fk_dqa_guid', '$textFindings', '0',NOW(),'$addedBy','$dqaLevel','technical advice')";
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                $listUpdate = "UPDATE `tbl_dqa_list` SET `is_reviewed`='reviewed' WHERE (`id`='$listId') LIMIT 1";
                $mysql->query($listUpdate);
                return true;
            } else {
                return false;
            }
        } else {
            $q = "INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`, `fk_dqa_guid`, `findings`, `is_deleted`, `created_at`, `added_by`,`fk_file_guid`,`dqa_level`,`technical_advice`) VALUES ('$finding_guid', '$fk_ft_guid', '$fk_dqa_guid', '$textFindings', '0', NOW(), '$addedBy','$fileId','$dqaLevel','technical advice')";
            //Update file status
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                // $fileUpdate = "UPDATE `form_uploaded` SET `is_reviewed`='reviewed', `date_reviewed`=NOW() WHERE (`file_id`='$fileId') LIMIT 1";
                // $resultFileUpdate = $mysql->query($fileUpdate) or die($mysql->error);
                $listUpdate = "UPDATE `tbl_dqa_list` SET `is_reviewed`='reviewed' WHERE (`id`='$listId') LIMIT 1";
                $mysql->query($listUpdate);
                return true;
            } else {
                return false;
            }
        }
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

    public function displayFindings($fileId, $ft_guid)
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
        tbl_dqa_findings.findings_guid,
        tbl_dqa_findings.technical_advice
        FROM
        tbl_dqa_findings
        WHERE  (fk_ft_guid='$ft_guid' OR fk_file_guid='$fileId') AND is_deleted=0
        ORDER BY tbl_dqa_findings.created_at DESC";
        $results = $mysql->query($q) or die($mysql->error);
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function findingStatus($id, $deadLineForCompliance)
    {
        $s = ($id == '0') ? '<span class="badge bg-danger"><span class="fa fa-times-circle"></span> Not Complied</span> ' : '<span class="badge bg-success"><span class="fa fa-check-circle"></span> Complied</span> ';
        //0 is not complied
        if ($id == '0') {
            return $s .= $this->dueStatus($deadLineForCompliance);
        } else {
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

    public function removeFinding($id)
    {
        $mysql = $this->connectDatabase();
        $q = "UPDATE `tbl_dqa_findings` SET `is_deleted`='1' WHERE (`findings_guid`='$id') LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($mysql->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function noFindings($fileId)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        form_uploaded.file_id,
        form_uploaded.is_reviewed,
        form_uploaded.with_findings
        FROM
        form_uploaded
        WHERE file_id='$fileId' AND is_reviewed='reviewed' AND with_findings='no findings'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function allfindingsByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
                count(tbl_dqa_findings.findings_guid) as findingsByUsername
                FROM
                    tbl_dqa_findings
                INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE
                tbl_dqa_findings.is_deleted = 0
                AND tbl_dqa_findings.added_by = '$username'
                AND cycles.`status` = '$status'
                AND lib_modality.modality_group = '$modalityGroup'
                AND tbl_dqa_findings.technical_advice is NULL";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['findingsByUsername'];
        } else {
            return '0';
        }
    }

    public function thisWeekFindingsByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
        Count(tbl_dqa_findings.added_by) AS thisWeekReviewedByUsername
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE cycles.`status`='$status' 
        AND tbl_dqa_findings.is_deleted=0 
        AND tbl_dqa_findings.added_by='$username'
        AND WEEKOFYEAR(tbl_dqa_findings.created_at)=WEEKOFYEAR(NOW()) 
        AND YEAR(tbl_dqa_findings.created_at) = YEAR(now()) 
        AND lib_modality.modality_group='$modalityGroup'
        AND tbl_dqa_findings.technical_advice is NULL";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['thisWeekReviewedByUsername'];
        } else {
            return '0';
        }
    }

    public function thisDayFindingsByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
        Count(tbl_dqa_findings.added_by) AS thisDayReviewedByUsername
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE cycles.`status`='$status' 
        AND tbl_dqa_findings.is_deleted=0 
        AND tbl_dqa_findings.added_by='$username'
        AND DAYOFYEAR(tbl_dqa_findings.created_at)=DAYOFYEAR(NOW()) 
        AND YEAR(tbl_dqa_findings.created_at) = YEAR(now()) 
        AND lib_modality.modality_group='$modalityGroup'
        AND tbl_dqa_findings.technical_advice is NULL";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['thisDayReviewedByUsername'];
        } else {
            return '0';
        }
    }

    public function allTaByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
                count(tbl_dqa_findings.findings_guid) as all_ta
                FROM
                    tbl_dqa_findings
                INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE
                tbl_dqa_findings.is_deleted = 0
                AND tbl_dqa_findings.added_by = '$username'
                AND cycles.`status` = '$status'
                AND lib_modality.modality_group = '$modalityGroup'
                AND tbl_dqa_findings.technical_advice='technical advice'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['all_ta'];
        } else {
            return '0';
        }
    }

    public function thisWeekTaByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
                count(tbl_dqa_findings.findings_guid) as this_week_ta
                FROM
                    tbl_dqa_findings
                INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE
                tbl_dqa_findings.is_deleted = 0
                AND tbl_dqa_findings.added_by = '$username'
                AND cycles.`status` = '$status'
                AND lib_modality.modality_group = '$modalityGroup' 
                AND WEEKOFYEAR(tbl_dqa_findings.created_at)=WEEKOFYEAR(NOW()) 
                AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())
                AND tbl_dqa_findings.technical_advice='technical advice'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['this_week_ta'];
        } else {
            return '0';
        }
    }

    public function thisDayTaByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
                count(tbl_dqa_findings.findings_guid) as this_day_ta
                FROM
                    tbl_dqa_findings
                INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE
                tbl_dqa_findings.is_deleted = 0
                AND tbl_dqa_findings.added_by = '$username'
                AND cycles.`status` = '$status'
                AND lib_modality.modality_group = '$modalityGroup' 
                AND DAYOFYEAR(tbl_dqa_findings.created_at)=DAYOFYEAR(NOW()) 
                AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())
                AND tbl_dqa_findings.technical_advice='technical advice'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['this_day_ta'];
        } else {
            return '0';
        }
    }

    public function allfindingsCompliedByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
                count(tbl_dqa_findings.findings_guid) as CompliedFindingsByUsername
                FROM
                    tbl_dqa_findings
                INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE
                tbl_dqa_findings.is_deleted = 0
                AND tbl_dqa_findings.added_by = '$username'
                AND cycles.`status` = '$status'
                AND lib_modality.modality_group = '$modalityGroup'
                AND tbl_dqa_findings.is_checked=1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['CompliedFindingsByUsername'];
        } else {
            return '0';
        }
    }

    public function thisWeekFindingsCompliedByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
                count(tbl_dqa_findings.findings_guid) as thisWeekFindingsCompliedByUsername
                FROM
                    tbl_dqa_findings
                INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE
                tbl_dqa_findings.is_deleted = 0
                AND tbl_dqa_findings.added_by = '$username'
                AND cycles.`status` = '$status'
                AND lib_modality.modality_group = '$modalityGroup'
                AND tbl_dqa_findings.is_checked=1
                AND WEEKOFYEAR(tbl_dqa_findings.created_at)=WEEKOFYEAR(NOW()) 
                AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['thisWeekFindingsCompliedByUsername'];
        } else {
            return '0';
        }
    }

    public function thisDayFindingsCompliedByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
                count(tbl_dqa_findings.findings_guid) as thisDayFindingsCompliedByUsername
                FROM
                    tbl_dqa_findings
                INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE
                tbl_dqa_findings.is_deleted = 0
                AND tbl_dqa_findings.added_by = '$username'
                AND cycles.`status` = '$status'
                AND lib_modality.modality_group = '$modalityGroup'
                AND tbl_dqa_findings.is_checked=1
                AND DAYOFYEAR(tbl_dqa_findings.created_at)=DAYOFYEAR(NOW()) 
                AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['thisDayFindingsCompliedByUsername'];
        } else {
            return '0';
        }
    }

    public function allReviewedByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
                COUNT(form_uploaded.file_id) as allReviewedByUsername
                FROM
                form_uploaded
                INNER JOIN form_target ON form_target.ft_guid = form_uploaded.fk_ft_guid
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE form_uploaded.reviewed_by='$username' 
                AND form_uploaded.is_reviewed='r    eviewed' 
                AND form_uploaded.is_deleted=0 
                AND lib_modality.modality_group='$modalityGroup' 
                AND cycles.`status`='$status'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['allReviewedByUsername'];
        } else {
            return '0';
        }
    }

    public function thisWeekReviewedByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
                COUNT(form_uploaded.file_id) as thisWeekReviewedByUsername
                FROM
                form_uploaded
                INNER JOIN form_target ON form_target.ft_guid = form_uploaded.fk_ft_guid
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE form_uploaded.reviewed_by='$username' 
                AND form_uploaded.is_reviewed='reviewed' 
                AND form_uploaded.is_deleted=0 
                AND lib_modality.modality_group='$modalityGroup' 
                AND cycles.`status`='$status'
                AND WEEKOFYEAR(form_uploaded.date_uploaded)=WEEKOFYEAR(NOW()) 
                AND YEAR(form_uploaded.date_uploaded) = YEAR(now())";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['thisWeekReviewedByUsername'];
        } else {
            return '0';
        }
    }

    public function thisDayReviewedByUsername($username, $modalityGroup, $status)
    {
        $mysql = $this->connectDatabase();
        $modalityGroup = $mysql->real_escape_string($modalityGroup);
        $q = "SELECT
                COUNT(form_uploaded.file_id) as thisDayReviewedByUsername
                FROM
                form_uploaded
                INNER JOIN form_target ON form_target.ft_guid = form_uploaded.fk_ft_guid
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE form_uploaded.reviewed_by='$username' 
                AND form_uploaded.is_reviewed='reviewed' 
                AND form_uploaded.is_deleted=0 
                AND lib_modality.modality_group='$modalityGroup' 
                AND cycles.`status`='$status'
                AND DAYOFYEAR(form_uploaded.date_uploaded)=DAYOFYEAR(NOW()) 
                AND YEAR(form_uploaded.date_uploaded) = YEAR(now())";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['thisDayReviewedByUsername'];
        } else {
            return '0';
        }
    }

    public function editDqaTitle($dqa_id, $dqaTitle, $responsiblePerson)
    {
        $mysql = $this->connectDatabase();
        $dqa_id = $mysql->real_escape_string($dqa_id);
        $dqaTitle = $mysql->real_escape_string($dqaTitle);
        $responsiblePerson = $mysql->real_escape_string($responsiblePerson);
        $q = "UPDATE `tbl_dqa` SET `title`='$dqaTitle', `responsible_person`='$responsiblePerson' WHERE (`dqa_guid`='$dqa_id') LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($mysql->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getIpcddCoverage($status, $username)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        lib_cadt.cadt_name,
        cycles.`status`,
        lib_cadt.id as cadt_id,
        cycles.id as cycle_id
        FROM
        tbl_user_coverage_ipcdd
        INNER JOIN implementing_cadt_ipcdd ON implementing_cadt_ipcdd.fk_cadt = tbl_user_coverage_ipcdd.fk_cadt_id
        INNER JOIN cycles ON cycles.id = tbl_user_coverage_ipcdd.fk_cycle_id
        INNER JOIN lib_cadt ON lib_cadt.id = tbl_user_coverage_ipcdd.fk_cadt_id
        WHERE cycles.`status`='$status' AND tbl_user_coverage_ipcdd.fk_username='$username'";
        $results = $mysql->query($q) or die($mysql->error);
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function is_pending($user)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT users.status FROM users WHERE users.username= ? ") or die($mysql->error);
        $q->bind_param('s', $user);
        $q->execute();
        $result = $q->get_result();
        $row = $result->fetch_assoc();
        if ($result->num_rows > 0 && $row['status'] == 'pending') {
            return true;
        } else {
            return false;
        }
    }

    public function permission($user)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				lib_user_permission.permission,
				user_permission.fk_username
				FROM
				user_permission
				INNER JOIN lib_user_permission ON user_permission.fk_user_permission = lib_user_permission.id
				WHERE user_permission.fk_username = ? ");
        $q->bind_param('s', $user);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row['permission'];
            }
            return $_SESSION['user_auth'] = array("permission" => $data);
            //return $_SESSION['user_auth'] = array("permission" => $data, "user_details" => array('username' => $row['fk_username']));
        } else {
            return false;
        }
    }

    public function getAllCadtMembers($cadt_id, $userGroup)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        CONCAT(personal_info.first_name,' ',personal_info.last_name) as fullName,
        personal_info.pic_url,
        users.fk_position,
        lib_user_positions.user_position,
        lib_user_positions.user_position_abbrv,
        lib_user_positions.user_group,
        user_cadt_coverage.fk_username
        FROM
        user_cadt_coverage
        INNER JOIN personal_info ON personal_info.fk_username = user_cadt_coverage.fk_username
        INNER JOIN users ON users.username = user_cadt_coverage.fk_username
        INNER JOIN lib_user_positions ON lib_user_positions.id = users.fk_position
        WHERE user_cadt_coverage.fk_cadt='$cadt_id' AND lib_user_positions.user_group='$userGroup'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    //api for sdu
    public function apiForms($id)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->real_escape_string($id);
        $q = "SELECT
                lib_modality.id AS modality_id,
                lib_modality.modality_group,
                lib_category.id AS stage_id,
                lib_category.category_name as stage_name,
                lib_category.acronym,
                lib_activity.id AS activity_id,
                lib_activity.activity_name,
                lib_form.form_code,
                lib_form.form_name,
                lib_form.form_type
            FROM
                lib_form
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            INNER JOIN lib_category ON lib_category.id = lib_activity.fk_category
            INNER JOIN lib_modality ON lib_modality.id = lib_category.fk_modality
            WHERE lib_activity.id = '$id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $json_data = array("forms" => $data);
            echo json_encode($json_data, JSON_PRETTY_PRINT);
        }
    }

    public function apiActivity($id)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->real_escape_string($id);
        $q = "SELECT
        lib_category.id as stage_id,
        lib_category.category_name as stage_name,
        lib_category.acronym,
        lib_activity.id AS activity_id,
        lib_activity.activity_name
        FROM
        lib_activity
        INNER JOIN lib_category ON lib_category.id = lib_activity.fk_category
        INNER JOIN lib_modality ON lib_modality.id = lib_category.fk_modality
        WHERE lib_category.id ='$id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $json_data = array("activities" => $data);
            echo json_encode($json_data, JSON_PRETTY_PRINT);
        }
    }

    public function apiCategory($userGroup)
    {
        $mysql = $this->connectDatabase();
        $userGroup = $mysql->real_escape_string($userGroup);
        $q = "SELECT
        lib_category.id as stage_id,
        lib_category.category_name as stage_name,
        lib_modality.modality_group,
        lib_category.acronym
        FROM
        lib_category
        INNER JOIN lib_modality ON lib_modality.id = lib_category.fk_modality
        WHERE lib_modality.modality_group='$userGroup'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $json_data = array("category" => $data);
            echo json_encode($json_data, JSON_PRETTY_PRINT);
        }
    }

    public function myWorkDashboard_ipcddDrom($status)
    {
        $mysql = $this->connectDatabase();
        $status = $mysql->real_escape_string($status);
        $q = "SELECT
        lib_cadt.cadt_name,
        lib_cycle.cycle_name,
        Sum(form_target.target) AS target,
        Sum(form_target.actual) AS actual,
        CONCAT(
                    FORMAT(
                        Sum(form_target.actual) / Sum(form_target.target) * 100,
                        2
                    ),
                    '%'
                ) AS uploadStatus,
        cycles.id as cycle_id,
        lib_cadt.id as cadt_id,
        form_target.fk_psgc_mun
        FROM
                form_target
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
            INNER JOIN tbl_user_coverage_ipcdd ON tbl_user_coverage_ipcdd.fk_cadt_id = form_target.fk_cadt
            INNER JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
            LEFT JOIN form_uploaded ON form_uploaded.fk_ft_guid = form_target.ft_guid
        WHERE
                cycles.`status` = '$status'
            AND tbl_user_coverage_ipcdd.status='$status'
            AND tbl_user_coverage_ipcdd.fk_username = '$_SESSION[username]'
            AND form_target.target > 0
            AND (
                form_uploaded.is_deleted = 0
                OR form_uploaded.is_deleted IS NULL
            )
        GROUP BY
                form_target.fk_cadt,
                form_target.fk_cycle
        ";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['reviewed'] = $this->countReviewedByUsername($row['cadt_id'], $row['fk_psgc_mun'], $row['cycle_id']);
                $row['reviewedOverActual'] = number_format($row['reviewed'] / $row['actual'] * 100, 2);
                $row['findings'] = $this->countFindingByUsername($row['cadt_id'], $row['fk_psgc_mun'], $row['cycle_id']);
                $row['complied'] = $this->countCompliedByUsername($row['cadt_id'], $row['fk_psgc_mun'], $row['cycle_id']);
                $data[] = $row;
            }
            return $data;
        }
    }

    public function countReviewedByUsername($area_id, $area_id2, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                COUNT(
                    DISTINCT tbl_dqa_list.fk_file_guid
                ) as reviewed
            FROM
                tbl_dqa_list
            INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_list.ft_guid
            WHERE
                tbl_dqa_list.added_by = '$_SESSION[username]'
            AND (
                form_target.fk_cadt = '$area_id'
                OR form_target.fk_psgc_mun = '$area_id2'
            )
            AND form_target.fk_cycle = '$cycle_id'
            AND tbl_dqa_list.is_delete = 0";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            return $row['reviewed'];
        }
    }

    public function countFindingByUsername($area_id, $area_id2, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        COUNT(tbl_dqa_findings.findings_guid) as countFinding
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        WHERE (form_target.fk_cadt='$area_id' OR form_target.fk_psgc_mun='$area_id2') AND form_target.fk_cycle='$cycle_id' AND tbl_dqa_findings.added_by='$_SESSION[username]'
        AND tbl_dqa_findings.is_deleted=0";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['countFinding'];
        }
    }

    public function countCompliedByUsername($area_id, $area_id2, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        COUNT(tbl_dqa_findings.findings_guid) as countComplied
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        WHERE (form_target.fk_cadt='$area_id' OR form_target.fk_psgc_mun='$area_id2') AND form_target.fk_cycle='$cycle_id' AND tbl_dqa_findings.added_by='$_SESSION[username]'
        AND tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.is_checked=1";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['countComplied'];
        }
    }

    public function myWorkFindingAll($status)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        count(tbl_dqa_findings.added_by) as cntFinding
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE cycles.`status`='$status' AND tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.added_by='$_SESSION[username]'
        AND tbl_dqa_findings.technical_advice is NULL";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cntFinding'];
        }
    }

    public function myWorkThisWeekFinding($status)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        count(tbl_dqa_findings.added_by) as cntFindingThisWeek
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE cycles.`status`='$status' AND tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.added_by='$_SESSION[username]'
        AND WEEKOFYEAR(tbl_dqa_findings.created_at)=WEEKOFYEAR(NOW()) 
        AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())
        AND tbl_dqa_findings.technical_advice is NULL";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cntFindingThisWeek'];
        } else {
            return 0;
        }
    }

    public function myWorkThisDayFinding($status)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        count(tbl_dqa_findings.added_by) as cntFindingThisDay
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE cycles.`status`='$status' AND tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.added_by='$_SESSION[username]'
        AND DAYOFYEAR(tbl_dqa_findings.created_at)=DAYOFYEAR(NOW()) 
        AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())
        AND tbl_dqa_findings.technical_advice is NULL";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cntFindingThisDay'];
        } else {
            return 0;
        }
    }

    public function myWorkReviewedAll($username, $status)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            COUNT(tbl_dqa_list.id) as myWorkReviewedAll
            FROM
            tbl_dqa_list
            INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_list.ft_guid
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            WHERE cycles.`status`='$status' 
            AND tbl_dqa_list.added_by='$username' 
            AND tbl_dqa_list.is_delete=0 
            AND tbl_dqa_list.fk_file_guid is not NULL
            AND tbl_dqa_list.is_reviewed='reviewed'
            AND DAYOFYEAR(tbl_dqa_list.created_at)=DAYOFYEAR(NOW()) 
            AND YEAR(tbl_dqa_list.created_at) = YEAR(now())
            ";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['myWorkReviewedAll'];
        } else {
            return 0;
        }
    }

    public function myWorkThisWeekReviewed($status)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            COUNT(tbl_dqa_list.id) as myWorkThisWeekReviewed
            FROM
            tbl_dqa_list
            INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_list.ft_guid
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            WHERE cycles.`status`='$status' 
            AND tbl_dqa_list.added_by='$_SESSION[username]' 
            AND tbl_dqa_list.is_delete=0
            AND tbl_dqa_list.fk_file_guid is not NULL
            AND tbl_dqa_list.is_reviewed='reviewed'
            AND WEEKOFYEAR(tbl_dqa_list.created_at)=WEEKOFYEAR(NOW()) 
            AND YEAR(tbl_dqa_list.created_at) = YEAR(now())";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['myWorkThisWeekReviewed'];
        } else {
            return 0;
        }
    }

    public function myWorkThisDayReviewed($status)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            COUNT(tbl_dqa_list.id) as myWorkThisDayReviewed
            FROM
            tbl_dqa_list
            INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_list.ft_guid
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            WHERE cycles.`status`='$status' 
            AND tbl_dqa_list.added_by='$_SESSION[username]' 
            AND tbl_dqa_list.is_delete=0
            AND tbl_dqa_list.fk_file_guid is not NULL
            AND tbl_dqa_list.is_reviewed='reviewed'
            AND DAYOFYEAR(tbl_dqa_list.created_at)=DAYOFYEAR(NOW()) 
            AND YEAR(tbl_dqa_list.created_at) = YEAR(now())";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['myWorkThisDayReviewed'];
        } else {
            return 0;
        }
    }

    public function myWorkTaAll($status)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                count(tbl_dqa_findings.findings_guid) as myWorkTaAll
                FROM
                    tbl_dqa_findings
                INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                WHERE
                tbl_dqa_findings.is_deleted = 0
                AND tbl_dqa_findings.added_by = '$_SESSION[username]'
                AND cycles.`status` = '$status'
                AND tbl_dqa_findings.technical_advice='technical advice'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['myWorkTaAll'];
        } else {
            return '0';
        }
    }

    public function myWorkTaThisWeek($status)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        count(tbl_dqa_findings.findings_guid) as myWorkTaThisWeek
        FROM
            tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE
        tbl_dqa_findings.is_deleted = 0
        AND tbl_dqa_findings.added_by = '$_SESSION[username]'
        AND cycles.`status` = '$status'
        AND WEEKOFYEAR(tbl_dqa_findings.created_at)=WEEKOFYEAR(NOW()) 
        AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())
        AND tbl_dqa_findings.technical_advice='technical advice'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['myWorkTaThisWeek'];
        } else {
            return '0';
        }
    }

    public function myWorkTaThisDay($status)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        count(tbl_dqa_findings.findings_guid) as myWorkTaThisDay
        FROM
            tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE
        tbl_dqa_findings.is_deleted = 0
        AND tbl_dqa_findings.added_by = '$_SESSION[username]'
        AND cycles.`status` = '$status'
        AND DAYOFYEAR(tbl_dqa_findings.created_at)=DAYOFYEAR(NOW()) 
        AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())
        AND tbl_dqa_findings.technical_advice='technical advice'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['myWorkTaThisDay'];
        } else {
            return '0';
        }
    }

    public function searchFileResults($modality, $cycle, $stage, $activity, $form, $area)
    {
        $mysql = $this->connectDatabase();
        $modality = $mysql->real_escape_string($modality);
        $cycle = $mysql->real_escape_string($cycle);
        $stage = $mysql->real_escape_string($stage);
        $activity = $mysql->real_escape_string($activity);
        $form = $mysql->real_escape_string($form);
        $area = $mysql->real_escape_string($area);
        $where = '';
        if (!empty($area)) {
            $where = ' AND (form_target.fk_cadt IN (' . $area . ') OR form_target.fk_psgc_mun IN (' . $area . '))';
        }
        if (!empty($cycle)) {
            $where .= ' AND form_target.fk_cycle IN (' . $cycle . ')';
        }
        if (!empty($stage)) {
            $where .= ' AND lib_category.id IN (' . $stage . ')';
        }
        if (!empty($activity)) {
            $where .= ' AND lib_activity.id IN (' . $activity . ')';
        }
        if (!empty($form)) {
            $where .= ' AND lib_form.id IN (' . $form . ')';
        }

        $q = "SELECT
            form_uploaded.file_id,
            form_uploaded.original_filename,
            form_uploaded.file_path,
            form_uploaded.is_reviewed,
            form_uploaded.with_findings,
            lib_activity.activity_name,
            lib_form.form_name,
            COALESCE (
                    lib_municipality.mun_name,
                    lib_cadt.cadt_name,
                    '-'
                ) AS mun_name,
            COALESCE (
                    lib_barangay.brgy_name,
                    '-'
                ) AS brgy_name
            FROM
            form_target
            LEFT JOIN form_uploaded ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            INNER JOIN lib_category ON lib_category.id = lib_activity.fk_category
            INNER JOIN lib_modality ON lib_modality.id = lib_category.fk_modality
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            LEFT JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            WHERE lib_modality.modality_group = '$modality'
            AND form_target.target > 0
            AND (
                form_uploaded.is_deleted = 0
                OR form_uploaded.is_deleted IS NULL
            )";
        $q .= $where;
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

    public function kcpis_activeUsers()
    {
        $mysql = $this->connectHREDatabase();
    }

    public function register_sso($user_sso, $id_number)
    {
        $mysql = $this->connectDatabase();
        $id_number = $mysql->real_escape_string($id_number);
        //UNCOMMENT these in production site.
        $user_sso = $user_sso;
        $user_sso = $user_sso->toArray();
        $oauth = $user_sso['sub'];
        $username = $user_sso['preferred_username'];

        //check existing account
        //$oauth = '';
        $username;
        $pass = "default123$";
        $scenario = 'oauth_create';

        //get Person Info from HIReS
        $this->personInfo($id_number);
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $q = "INSERT INTO `tbl_users` (`id_number`,`username`, `password`,`created_at`,`scenario`,`oauth_client`,`oauth_client_user_id`) VALUES ('$id_number','$username', '$hash', NOW(), '$scenario', '$oauth', '$oauth')";
        $execute = $mysql->query($q) or die($mysql->error);
        if($mysql->affected_rows>0){
            $r = "INSERT INTO `tbl_person_info` (`fk_id_number`, `first_name`,`mid_name`, `last_name`,`sector_name`,`sector_desc`,`status`,`position_name`,`position_desc`,`office_name`,`office_desc`,`created_at`) VALUES ('$id_number','$this->firstName','$this->midName','$this->lastName','$this->sectorName','$this->sectorDesc','$this->status','$this->posName','$this->posDesc','$this->officeName','$this->officeDesc',NOW())";
            $execute = $mysql->query($r) or die($mysql->error);
            if($mysql->affected_rows>0){
                return true;
            }
        }else{
            return false;
        }
    }

    public function sso_isExist($user_sso)
    {
        $mysql = $this->connectDatabase();
        $user_sso = $user_sso->toArray();
        $oauth = $user_sso['sub'];

        $q = "SELECT
            tbl_users.oauth_client
            FROM
            tbl_users where oauth_client='$user_sso'";

        $result = $mysql->query($q);
        $row = $result->fetch_assoc();
        if ($row['oauth_client']) {
            return 1;
        } else {
            return 0;
        }
    }

    public function is_idNumberExist($id_number)
    {
        $mysql = $this->connectDatabase();
        $id_number = $mysql->real_escape_string($id_number);

        $q = "SELECT tbl_users.id_number FROM tbl_users where id_number='$id_number'";
        $result = $mysql->query($q);
        $row = $result->fetch_assoc();

        if($result->num_rows>0){
            return true;
        }else{
            return false;
        }

    }

    public function getImage($id_number)
    {
        $opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
        $context = stream_context_create($opts);
        $url = json_decode(file_get_contents('http://crg-kcapps-svr.entdswd.local:8080/get_kalahi_staff', true, $context), true);
        foreach ($url as $item) {
            if ($item['id_number'] == $id_number) {
                return $item['image_path'];
            }
        }
    }

    public function personInfo($id_number)
    {
        $mysql = $this->connectHREDatabase();
        $id_number = $mysql->real_escape_string($id_number);
        $q = "SELECT view_active_staff.* FROM view_active_staff WHERE id_number='$id_number'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->firstName = $row['fname'];
            $this->midName = $row['mname'];
            $this->lastName = $row['lname'];
            $this->sectorName = $row['sector_name'];
            $this->sectorDesc = $row['sect_desc'];
            $this->status = $row['status_name'];
            $this->posName = $row['position_name'];
            $this->posDesc = $row['position_desc'];
            $this->officeName = $row['office_name'];
            $this->officeDesc = $row['office_desc'];
            return $row;
        } else {
            return '0';
        }
    }

    public function saveUserInfo()
    {
        $mysql = $this->connectDatabase();
        $q = "";
    }

    public function api_allFiles()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            form_uploaded.file_id,
            lib_modality.modality_name,
            lib_municipality.mun_name,
            lib_barangay.brgy_name,
            lib_cycle.cycle_name,
            lib_activity.activity_name,
            lib_form.form_name,
            form_uploaded.original_filename,
            form_uploaded.generated_filename,
            form_uploaded.file_path,
            form_uploaded.date_uploaded
            
            FROM
            form_target
            INNER JOIN form_uploaded ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
            INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
            LEFT JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            WHERE form_uploaded.is_deleted=0";
        $result = $mysql->query($q) or die($mysql->error);

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $json_data = array("mrms_files" => $data);
        echo json_encode($json_data, JSON_PRETTY_PRINT);
    }

    public function api_findings()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            tbl_dqa_findings.fk_file_guid,
            tbl_dqa_findings.findings,
            tbl_dqa_findings.responsible_person,
            tbl_dqa_findings.is_checked,
            tbl_dqa_findings.added_by,
            tbl_dqa_findings.created_at
            FROM
            tbl_dqa
            INNER JOIN tbl_dqa_findings ON tbl_dqa_findings.fk_dqa_guid = tbl_dqa.dqa_guid
            WHERE tbl_dqa_findings.is_deleted=0";
        $result = $mysql->query($q) or die($mysql->error);

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $json_data = array("mrms_findings" => $data);
        echo json_encode($json_data, JSON_PRETTY_PRINT);
    }

    public function getUserCoverage(){
        $mysql = $this->connectDatabase();
        $q="";
    }

    public function tbl_users(){
        $mysql = $this->connectHREDatabase();
        $q="SELECT * FROM kcpis.view_active_staff";
        $mysql = $this->connectHREDatabase();
        $q="SELECT * FROM kcpis.view_active_staff";
        $result = $mysql->query($q) or die($mysql->error);
        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()) {
                $row['avatar_path'] ='';
               // $row['avatar_path'] =$this->getImage($row['id_number']);
                $data[] = $row;
            }
            $json_data = array("data" => $data);
            echo json_encode($json_data);
        }else{
            return false;
        }



    }
    public function activerUsers(){
        $mysql = $this->connectHREDatabase();
        $q="select COUNT(view_active_staff.id_number) as activeUser from view_active_staff";
        $result = $mysql->query($q) or die($mysql->error);
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            return $row['activeUser'];
        }else{
            return false;
        }


    }
}
