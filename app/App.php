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
                WHERE cycles.`year`= ? AND lib_modality.modality_group='drom' AND lib_modality.modality_name=?");
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
            INNER JOIN form_uploaded ON form_uploaded.file_id = tbl_dqa_list.fk_file_guid
            INNER JOIN form_target ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN users ON users.username = form_uploaded.uploaded_by
            INNER JOIN personal_info ON personal_info.fk_username = users.username
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            LEFT JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            where
             tbl_dqa.dqa_guid='$dqaId' AND tbl_dqa_list.is_delete='0' AND form_uploaded.is_deleted='0'";
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
            form_uploaded.fk_ft_guid,
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
            CONCAT(personal_info.first_name,' ',personal_info.last_name) as uploader
            FROM
                form_uploaded
            INNER JOIN form_target ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            LEFT JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            INNER JOIN personal_info ON personal_info.fk_username = form_uploaded.uploaded_by
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            WHERE
                form_uploaded.is_deleted = 0
            AND form_uploaded.is_compliance IS NULL
            AND (
                form_target.fk_psgc_mun = '$fk_psgc_mun'
                OR form_target.fk_cadt = '$cadt_id'
            )
            AND form_target.fk_cycle = '$cycle_id'
            AND form_uploaded.file_id NOT IN (
                SELECT
                    tbl_dqa_list.fk_file_guid
                FROM
                    tbl_dqa_list
                WHERE
                    tbl_dqa_list.added_by = '$username'
            )";
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
}
