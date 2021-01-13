<?php

namespace app;

class Dqa
{

    public $dqa_title;
    public $fk_finding;
    public $with_findings;
    public $is_reviewed;
    public $is_complied;
    public $complied_count;
    public $notcomplied_count;
    public $total_findings;
    public $area_id;
    public $cycle_id;

    public function connectDatabase()
    {
        $database = Database::getInstance();
        return $database->getConnection();
    }

    public function create_dqa()
    {
        $mysql = $this->connectDatabase();
        $ceac = new Ceac();
        $dqa_gui = $ceac->v4();
        /*$post_d_conducted = $_POST['date_conducted'];
        $post_doc = $_POST['date_of_compliance'];
        $date_conducted = new \DateTime($post_d_conducted);
        $date_of_comp = new \DateTime($post_doc);*/
        //$d1 = $date_conducted->format('Y-m-d');
        //$d2 = $date_of_comp->format('Y-m-d');

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
    public function table_conducted_dqa()
    {
        $mysql = $this->connectDatabase();
        $params = $_REQUEST;
        $columns = array(0 => 'm.date_conducted', 1 => 'm.title', 2 => 'lib_municipality.mun_name', 3 => "no_of_forms", 4 => "no_of_findings", 5 => 'm.deadline_for_compliance', 6 => 'days_overdue', 7 => 'responsible_person', 8 => 'conducted_by', 9 => 'm.dqa_status', 10 => 'm.created_at');
        $where_con = $sqlTot = $sqlRec = "";

        include_once 'User.php';
        $user = new User();
        $user->info($_SESSION['username']);
        if ($user->position_abbrv !== 'RMES') {
            $where_con .= ' AND m.conducted_by="' . $_SESSION['username'] . '"';
        }
        if (!empty($params['search']['value'])) {
            $where_con .= " AND ( m.date_conducted LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_municipality.mun_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR m.title LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR m.deadline_for_compliance LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR m.dqa_status LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR responsible_of.first_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR responsible_of.last_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR conducted_bys.first_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR m.created_at LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_cadt.cadt_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR conducted_bys.last_name LIKE '%" . $params['search']['value'] . "%') GROUP BY m.dqa_guid ";
        } else {
            $where_con .= " GROUP BY m.dqa_guid ";
        }
        $q = "SELECT
                DATE_FORMAT(m.created_at, '%W, %M %e, %Y'),
                lib_municipality.mun_name,
                m.title,
                DATE_FORMAT(m.deadline_for_compliance, '%W, %M %e, %Y'),
                DATEDIFF(NOW(),m.deadline_for_compliance) AS days_overdue,
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
                lib_cadt.id
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
        $sqlTot .= $q;
        $sqlRec .= $q;

        if (isset($where_con) && $where_con != '') {
            $sqlTot .= $where_con;
            $sqlRec .= $where_con;
        }

        $sqlRec .= " ORDER BY " . $columns[$params['order'][0]['column']] . ' ' . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . ", " . $params['length'] . " ";
        $query_tot = $mysql->query($sqlTot) or die($mysql->error);
        $total_records = $query_tot->num_rows;
        $query_records = $mysql->query($sqlRec) or die($mysql->error);
        if ($query_records->num_rows > 0) {
            while ($row = $query_records->fetch_row()) {
                $data[] = $row;
            }
        } else {
            $data = '';
        }
        $json_data = array("draw" => intval($params['draw']), "recordsTotal" => intval($total_records), "recordsFiltered" => intval($total_records), "data" => $data);
        echo json_encode($json_data);
    }
    public function tbl_dqaConducted(){
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
        $q.=$where_con;
        $result = $mysql->query($q) or die($mysql->error);
        while ($row = $result->fetch_row()) {
            $data[] = $row;
        }
        $json_data = array("data" => $data);
        echo json_encode($json_data);
    }
    public function dqa_info($dqa_guid)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            tbl_dqa.title,
            tbl_dqa.deadline_for_compliance,
            personal_info.first_name,
            personal_info.last_name,
            tbl_dqa.conducted_by,
            tbl_dqa.created_at,
            lib_cadt.cad    t_name,
            lib_municipality.mun_name,
            lib_cycle.cycle_name,
            lib_modality.modality_name
            FROM
            tbl_dqa
            INNER JOIN personal_info ON personal_info.fk_username = tbl_dqa.responsible_person
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = tbl_dqa.fk_psgc_mun
            LEFT JOIN lib_cadt ON lib_cadt.id = tbl_dqa.fk_cadt_id
            INNER JOIN cycles ON cycles.id = tbl_dqa.fk_cycle
            INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
            INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
            WHERE
                tbl_dqa.dqa_guid = ?");
        $q->bind_param('s', $dqa_guid);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return false;
        }

    }

    public function forms_to_dqa($city, $cycle, $cadt)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            form_target.ft_guid,
            lib_form.form_name,
            lib_barangay.brgy_name,
            form_target.can_upload,
            lib_barangay.psgc_brgy,
            lib_sitio.sitio_name,
            lib_municipality.mun_name
            FROM
            form_target
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_sitio ON lib_sitio.psgc_sitio = form_target.fk_psgc_sitio
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            WHERE (form_target.fk_psgc_mun = ? OR form_target.fk_cadt = ?) AND form_target.fk_cycle = ?
            ORDER BY lib_form.form_name,lib_barangay.brgy_name ASC");
        $q->bind_param('iii', $city, $cadt, $cycle) or $q->error;
        $q->execute() or die($q->error);
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
    public function activity_form($city, $cycle, $cadt)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            lib_activity.activity_name,
            lib_activity.id
            FROM
            form_target
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_sitio ON lib_sitio.psgc_sitio = form_target.fk_psgc_sitio
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            WHERE (form_target.fk_psgc_mun = ? OR form_target.fk_cadt = ?) AND form_target.fk_cycle = ?
            GROUP BY lib_activity.id
            ORDER BY lib_activity.id ASC");
        $q->bind_param('iii', $city, $cadt, $cycle) or $q->error;
        $q->execute() or die($q->error);
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

    public function lib_findings()
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				lib_findings.id,
				lib_findings.findings_type
				FROM
				lib_findings");
        $q->execute() or die($q->error);
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

    public function activity_for_dqa($city, $cycle, $cadt)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            lib_activity.activity_name,
            lib_activity.id
            FROM
            form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
            WHERE (form_target.fk_psgc_mun = ? OR form_target.fk_cadt = ?) AND form_target.fk_cycle = ?
            GROUP BY lib_activity.id");
        $q->bind_param('iii', $city, $cadt, $cycle) or $q->error;
        $q->execute() or die($q->error);
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

    public function add_finding()
    {
        $mysql = $this->connectDatabase();
        $ceac = new Ceac();
        $f_guid = $ceac->v4();

        /*if ($_POST['fk_findings'] == 7) {
        $is_checked = 1;
        } else {
        $is_checked = 0;

        }*/

        $q = $mysql->prepare("INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`, `fk_dqa_guid`, `fk_findings`, `findings`, `responsible_person`, `is_deleted`,`created_at`,`is_checked`)
			VALUES (?, ?, ?, ?, ?, ?, 0,NOW(),0)");
        $q->bind_param('sssiss', $f_guid, $_POST['fk_forms'], $_GET['dqa_guid'], $_POST['fk_findings'], $_POST['text_findings'], $_POST['fk_username']);
        $q->execute();
        if ($q->affected_rows > 0) {
            $this->can_upload(1, $_POST['fk_forms']);
            return true;
        } else {
            return false;
        }
    }

    public function deduct_count($fk_target)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->escape_string($fk_target);
        $q_actual = "SELECT
            form_target.actual,
            form_uploaded.is_reviewed,
            form_uploaded.with_findings
            FROM
            form_target
            INNER JOIN form_uploaded ON form_target.ft_guid = form_uploaded.fk_ft_guid
            WHERE form_target.ft_guid='$id'";
        $q_actual_result = $mysql->query($q_actual) or die($mysql->error);
        $actual = $q_actual_result->fetch_assoc();

        if ($actual['actual'] > 0) {
            $update_actual = $actual['actual'] - 1;
            $q = "UPDATE `form_target` SET `actual` = '$update_actual',`can_upload`=1 WHERE `ft_guid` = '$id'";
            $result = $mysql->query($q) or die($mysql->error);
            return true;
        } else {
            return false;
        }
    }

    public function add_actual_count($fk_target)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->escape_string($fk_target);
        $q_actual = "SELECT
            form_target.actual
        FROM
            form_target
            WHERE form_target.ft_guid='$id'";
        $q_actual_result = $mysql->query($q_actual) or die($mysql->error);
        $actual = $q_actual_result->fetch_assoc();
        $update_actual = $actual['actual'] + 1;
        $q = "UPDATE `form_target` SET `actual` = '$update_actual',`can_upload`=1 WHERE `ft_guid` = '$id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            return true;
        }
    }

    public function update_count($fk_target)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->escape_string($fk_target);
        $q_actual = "SELECT
            COUNT(form_uploaded.file_id) as file_count
            FROM
            form_uploaded
            WHERE is_deleted=0 AND fk_ft_guid='$fk_target'";
        //with_findings='no findings' AND is_reviewed='reviewed'
        $q_actual_result = $mysql->query($q_actual) or die($mysql->error);
        $actual = $q_actual_result->fetch_assoc();
        $update_actual = $actual['file_count'];

        //get target
        $get_t = "SELECT
                    form_target.target
                    FROM
                    form_target
                    WHERE ft_guid='$fk_target'";
        $result = $mysql->query($get_t);
        $r_t = $result->fetch_assoc();
        //check if actual is greater than target or equal
        if ($update_actual >= $r_t['target']) {
            //then update target base on actual's value
            $update_target = $update_actual;
            $q = "UPDATE `form_target` SET  `actual` = '$update_actual',`can_upload`=1,`target`='$update_target' WHERE `ft_guid` = '$id'";
        } else {
            $q = "UPDATE `form_target` SET  `actual` = '$update_actual',`can_upload`=1 WHERE `ft_guid` = '$id'";
        }
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            return true;
        }
    }

    public function submit_reviews()
    {
        $mysql = $this->connectDatabase();
        $ceac = new Ceac();
        $f_guid = $ceac->v4();

        if ($_POST['with_findings'] == 1) {
            $dqa_level = 'level_1';
            $date_of_comp = new \DateTime($_POST['date_of_compliance']);
            $d2 = $date_of_comp->format('Y-m-d');
            $findings = $_POST['findings'];
            $dates = new \DateTime();
            $q = $mysql->prepare("INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`,`fk_dqa_guid`, `fk_file_guid`,`dqa_level`,`added_by`, `fk_findings`, `findings`, `responsible_person`, `is_deleted`,`created_at`,`is_checked`,`deadline_for_compliance`)
			VALUES (?, ?, ?,?, ?, ?, ?, ?, ?, 0,NOW(),0,?)");
            $q->bind_param('ssssssisss', $f_guid, $_GET['fk_ft'], $_GET['dqa_id'], $_GET['file_id'], $dqa_level, $_SESSION['user_fullname'], $_POST['fk_findings'], $findings, $_POST['fk_username'], $d2);
            if ($q->execute()) {
                $this->update_formStatus('reviewed', 'with findings');
                echo 'added';
            } else {
                return false;
            }
        }

        if ($_POST['with_findings'] == 0) {

            if ($this->check_findings_exist($_GET['fk_ft'], $_GET['file_id'])) { //Cant set no findings if there are findings recorded
                echo 'findings found';
            } else {
                $this->update_formStatus('reviewed', 'no findings');
                echo 'no findings';
            }
        }
        $this->updateActualNofindings($_GET['fk_ft']);
    }

    public function review_onCompliance()
    {
        $mysql = $this->connectDatabase();
        $ceac = new Ceac();
        $f_guid = $ceac->v4();
        $dqa_level = 'level_2';

        if ($_POST['with_findings'] == 1) {
            $findings = $_POST['findings'];
            $dates = new \DateTime();
            $q = $mysql->prepare("INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`,`fk_dqa_guid`, `fk_file_guid`,`dqa_level`,`added_by`, `fk_findings`, `findings`, `responsible_person`, `is_deleted`,`created_at`,`is_checked`)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0,NOW(),0)");
            $q->bind_param('ssssssiss', $f_guid, $_GET['fk_ft'], $_GET['dqa_id'], $_GET['file_id'], $dqa_level, $_SESSION['user_fullname'], $_POST['fk_findings'], $findings, $_POST['fk_username']);
            if ($q->execute()) {
                echo 'added';
            } else {
                return false;
            }
            //update file with finding
            $file_wfinding = $_GET['file_wfinding'];
            $complied_findings = "UPDATE `form_uploaded` SET `is_findings_complied`=null WHERE (`file_id`='$file_wfinding' AND is_compliance is null) LIMIT 1";
            $execute = $mysql->query($complied_findings) or die($mysql->error);
            //update compliance nga file
            $f_id = $mysql->real_escape_string($_GET['file_id']);
            $compliance = "UPDATE `form_uploaded` SET `is_complied`='not complied' WHERE (`file_id`='$f_id' AND is_compliance is null) LIMIT 1";
            $execute = $mysql->query($compliance) or die($mysql->error);

        }

        if ($_POST['with_findings'] == 0) {
            if ($this->check_findings_exist($_GET['fk_ft'], $_GET['file_id'])) {
                echo 'findings found';
            } else {
                echo 'no findings';
            }
        }

        if ($_POST['is_complied'] == 1) {
            $file_wfinding = $_GET['file_wfinding'];
            $complied_findings = "UPDATE `form_uploaded` SET `is_findings_complied`='complied' WHERE (`file_id`='$file_wfinding' AND is_compliance is null) LIMIT 1";
            $execute = $mysql->query($complied_findings) or die($mysql->error);

            //update compliance to complied
            $compliance_complied = "UPDATE `form_uploaded` SET `is_complied`='complied' WHERE (`file_id`='$file_wfinding' AND is_compliance='compliance') LIMIT 1";
            $execute = $mysql->query($compliance_complied) or die($mysql->error);

            //update with_findings to complied
            $update_to_complied = "UPDATE form_uploaded SET is_findings_complied = 'complied' where (fk_ft_guid='$_GET[fk_ft]' and is_complied ='not complied')";
            $execute = $mysql->query($update_to_complied);

        }

        $update = "UPDATE `tbl_dqa_findings` SET `is_checked`='0' WHERE (`fk_ft_guid`='$_GET[fk_ft]')";
        $update_result = $mysql->query($update) or die($mysql->error);


        if (isset($_POST['complied_findings'])) {
            foreach ($_POST['complied_findings'] as $item) {
                $update = "UPDATE `tbl_dqa_findings` SET `is_checked`='1' WHERE (`findings_guid`='$item')";
                $update_result = $mysql->query($update) or die($mysql->error);
            }
        }

        $this->update_formStatus_compliance();
        $this->updateActualNofindings($_GET['fk_ft']);
    }

    public function addToDqaList()
    {
        $mysql = $this->connectDatabase();
        $ceac = new Ceac();
        $f_guid = $ceac->v4();
        $ft_guid = $mysql->real_escape_string($_POST['ft_guid']);
        $file_id = $mysql->real_escape_string($_POST['file_guid']);
        $dqa_id = $mysql->real_escape_string($_POST['dqa_id']);

        $q = "INSERT INTO `tbl_dqa_list` (`fk_file_guid`,`fk_dqa_guid`, `added_by`, `created_at`, `is_delete`)
            VALUES ('$file_id','$dqa_id', '$_SESSION[username]', NOW(), '0')";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $q = "UPDATE `form_uploaded` SET `is_added_to_dqa`='0' WHERE (`file_id`='$file_id') LIMIT 1";
            $mysql->query($q) or die($mysql->error);
            echo 'added';
        }
    }

    public function check_deadline($dqa_guid)
    {
        $mysql = $this->connectDatabase();

        $q = "SELECT
            IF(DATEDIFF(NOW(),tbl_dqa.deadline_for_compliance)>=0,'deadline_met','ok') as deadline_status,
            tbl_dqa.deadline_for_compliance
            FROM
            tbl_dqa
            WHERE tbl_dqa.dqa_guid='$dqa_guid'";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        return $row['deadline_status'];
    }

    public function get_date_doc($dqa_id)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->real_escape_string($dqa_id);
        $q = "SELECT
            tbl_dqa.date_conducted
            FROM
            tbl_dqa
            WHERE dqa_guid='$id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['date_conducted'];
        }
    }

    public function can_upload($val, $fk_ft)
    {
        $mysql = $this->connectDatabase();
        $ceac = new Ceac();
        $f_guid = $ceac->v4();
        $q = $mysql->prepare("UPDATE `form_target` SET `can_upload`=? WHERE (`ft_guid`=?) LIMIT 1");
        $q->bind_param('is', $val, $fk_ft);
        $q->execute();
        if ($q->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function display_findings($fk_ft)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
                    tbl_dqa_findings.findings,
                    lib_findings.findings_type,
                    form_target.can_upload,
                    tbl_dqa_findings.fk_findings,
                    tbl_dqa_findings.is_checked,
                    tbl_dqa_findings.date_complied
                FROM
                    lib_form
                    INNER JOIN
                    form_target
                    ON
                        lib_form.form_code = form_target.fk_form
                    INNER JOIN
                    tbl_dqa_findings
                    ON
                        tbl_dqa_findings.fk_ft_guid = form_target.ft_guid
                    INNER JOIN
                    lib_findings
                    ON
                        tbl_dqa_findings.fk_findings = lib_findings.id
                WHERE
                    tbl_dqa_findings.fk_ft_guid = ? and tbl_dqa_findings.is_deleted=0");

        $q->bind_param('s', $fk_ft) or $q->error;
        $q->execute() or die($q->error);
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
                $this->fk_finding = $row['fk_findings'];
            }
            return $data;
        } else {
            return false;
        }
    }

    public function display_dqa_info($fk_ft)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
					m.date_conducted,
					lib_municipality.mun_name,
					m.title,
					COUNT(tbl_dqa_findings.fk_findings) as no_of_findings,
					m.deadline_for_compliance,
					DATEDIFF(NOW(),m.deadline_for_compliance) as days_overdue,
					CONCAT(conducted_bys.first_name,' ',conducted_bys.last_name) AS conducted_by,
       				m.dqa_status
					FROM
					tbl_dqa AS m
					INNER JOIN users AS u1 ON (u1.username = m.responsible_person)
					INNER JOIN users AS u2 ON (u2.username = m.conducted_by)
					INNER JOIN personal_info AS conducted_bys ON conducted_bys.fk_username = u2.username
					INNER JOIN personal_info AS responsible_of ON responsible_of.fk_username = u1.username
					LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = m.fk_psgc_mun
					INNER JOIN cycles ON cycles.id = m.fk_cycle
					INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
					LEFT JOIN tbl_dqa_findings ON m.dqa_guid = tbl_dqa_findings.fk_dqa_guid
					LEFT JOIN lib_findings ON tbl_dqa_findings.fk_findings = lib_findings.id
					WHERE tbl_dqa_findings.fk_ft_guid=? and tbl_dqa_findings.is_deleted=0");
        $q->bind_param('s', $fk_ft);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return false;
        }
    }

    public function responsible_person($fk_ft)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            CONCAT(personal_info.first_name,' ',personal_info.last_name) as fullname
        FROM
            tbl_dqa_findings
            INNER JOIN
            personal_info
            ON
                tbl_dqa_findings.responsible_person = personal_info.fk_username
		WHERE tbl_dqa_findings.fk_ft_guid=? LIMIT 1");
        $q->bind_param('s', $fk_ft);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return false;
        }
    }

    public function tbl_act_compliance()
    {
        $mysql = $this->connectDatabase();
        $params = $_REQUEST;
        $columns = array(0 => 'form_uploaded.date_uploaded', 1 => 'lib_municipality.mun_name', 2 => 'form_uploaded.original_filename', 3 => 'tbl_dqa.title', 4 => 'tbl_dqa.deadline_for_compliance', 5 => 'days_overdue', 6 => 'form_uploaded.with_findings', 7 => 'form_uploaded.is_findings_completed', 8 => 'fullname');
        $where_con = $sqlTot = $sqlRec = "";
        include_once 'User.php';
        $user = new User();
        $user->info($_SESSION['username']);

        if ($user->position_abbrv == 'MEO III' or $user->position_abbrv == 'MEO II' or $user->position_abbrv == 'AA' or $user->position_abbrv == 'CDO' or $user->position_abbrv == 'ITO' or $user->position_abbrv == 'System Developer') {
            $where_con .= " AND
                (form_uploaded.uploaded_by = '$_SESSION[username]' OR tbl_dqa.conducted_by='$_SESSION[username]')";
        }

        if ($user->position_abbrv == 'AC' or $user->position_abbrv == 'CEF') {
            $where_con .= " AND
                (form_uploaded.uploaded_by = '$_SESSION[username]' OR tbl_dqa_findings.responsible_person='$_SESSION[username]' OR tbl_dqa.responsible_person='$_SESSION[username]')";
        }

        if (!empty($params['search']['value'])) {
            $where_con .= " AND (form_uploaded.date_uploaded LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.original_filename LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR tbl_dqa.deadline_for_compliance LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.is_reviewed LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.with_findings LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.is_findings_complied LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_form.form_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_barangay.brgy_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.file_id LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR tbl_dqa.title LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.last_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_municipality.mun_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_cadt.cadt_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.first_name LIKE '%" . $params['search']['value'] . "%') GROUP BY
	            tbl_dqa_findings.fk_ft_guid, form_uploaded.file_id ";
        } else {
            $where_con .= " GROUP BY
	            tbl_dqa_findings.fk_ft_guid, form_uploaded.file_id ";
        }
        $q = "SELECT
            form_uploaded.date_uploaded,
            lib_municipality.mun_name,
            CONCAT(lib_form.form_name,IF (lib_barangay.brgy_name IS NOT NULL,', ',''),COALESCE (lib_barangay.brgy_name, '')) AS forms,
            form_uploaded.original_filename,
            tbl_dqa.title,
            tbl_dqa.deadline_for_compliance,
            DATEDIFF(form_uploaded.date_uploaded,tbl_dqa.deadline_for_compliance) AS days_overdue,
            form_uploaded.is_reviewed,
            form_uploaded.with_findings,
            form_uploaded.is_complied,
            CONCAT(personal_info.first_name,' ',personal_info.last_name) AS fullname,
            lib_form.form_name,
            lib_barangay.brgy_name,
            personal_info.first_name,
            personal_info.last_name,
            form_uploaded.file_id,
            tbl_dqa_findings.fk_dqa_guid,
            form_uploaded.file_path,
            tbl_dqa_findings.fk_ft_guid,
            DATEDIFF(tbl_dqa.date_conducted,tbl_dqa.deadline_for_compliance) AS days_overdue_nyu,
            lib_sitio.sitio_name,
            lib_cadt.cadt_name,
            form_uploaded.is_complied,
            tbl_dqa_findings.fk_file_guid
            FROM
            tbl_dqa_findings
            INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN form_uploaded ON form_uploaded.fk_ft_guid = form_target.ft_guid
            INNER JOIN tbl_dqa ON tbl_dqa.dqa_guid = tbl_dqa_findings.fk_dqa_guid
            LEFT JOIN users ON users.username = form_uploaded.uploaded_by
            LEFT JOIN personal_info ON personal_info.fk_username = users.username
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            LEFT JOIN lib_sitio ON lib_sitio.psgc_sitio = form_target.fk_psgc_sitio
            LEFT JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            WHERE (form_uploaded.is_deleted = 0 OR form_uploaded.is_deleted is NULL) AND form_uploaded.is_compliance='compliance'";
        $sqlTot .= $q;
        $sqlRec .= $q;

        if (isset($where_con) && $where_con != '') {
            $sqlTot .= $where_con;
            $sqlRec .= $where_con;

        }

        $sqlRec .= " ORDER BY " . $columns[$params['order'][0]['column']] . ' ' . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . ", " . $params['length'] . " ";
        $query_tot = $mysql->query($sqlTot) or die($mysql->error);
        $total_records = $query_tot->num_rows;
        $query_records = $mysql->query($sqlRec) or die($mysql->error);
        if ($query_records->num_rows > 0) {
            while ($row = $query_records->fetch_row()) {
                $data[] = $row;
            }
        } else {
            $data = '';
        }
        $json_data = array("draw" => intval($params['draw']), "recordsTotal" => intval($total_records), "recordsFiltered" => intval($total_records), "data" => $data);
        echo json_encode($json_data);
    }

    public function tbl_fileWithFindings()
    {
        $mysql = $this->connectDatabase();
        $params = $_REQUEST;
        $columns = array(0 => 'form_uploaded.date_uploaded', 1 => 'form_uploaded.date_uploaded', 2 => 'form_uploaded.original_filename', 3 => 'personal_info.first_name', 4 => 'form_uploaded.is_findings_complied');
        $where_con = $sqlTot = $sqlRec = "";

        if (!empty($params['search']['value'])) {
            $where_con .= " AND (lib_form.form_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.original_filename LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.first_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.last_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_barangay.brgy_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.is_findings_complied LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.with_findings LIKE '%" . $params['search']['value'] . "%') GROUP BY form_uploaded.file_id ";
        } else {
            $where_con .= " GROUP BY form_uploaded.file_id ";
        }
        $q = "SELECT
            form_uploaded.date_uploaded,
            CONCAT(lib_form.form_name,IF (lib_barangay.brgy_name IS NOT NULL,', ',''),COALESCE (lib_barangay.brgy_name, '')) AS forms,
            form_uploaded.original_filename,
            CONCAT(personal_info.first_name,' ',personal_info.last_name) as uploaded_by,
            form_uploaded.file_path,
            form_uploaded.is_reviewed,
            form_uploaded.with_findings,
            form_uploaded.reviewed_by,
            form_uploaded.date_reviewed,
            tbl_dqa.deadline_for_compliance,
            DATEDIFF(NOW(),tbl_dqa.deadline_for_compliance) AS days_overdue,
            tbl_dqa.dqa_guid,
            form_uploaded.file_id,
            form_target.ft_guid,
            tbl_dqa_findings.added_by,
            form_uploaded.is_findings_complied
            FROM
            form_uploaded
            INNER JOIN form_target ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            LEFT JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            INNER JOIN personal_info ON personal_info.fk_username = form_uploaded.uploaded_by
            LEFT JOIN tbl_dqa_findings ON tbl_dqa_findings.fk_file_guid = form_uploaded.file_id
            LEFT JOIN tbl_dqa ON tbl_dqa.dqa_guid = tbl_dqa_findings.fk_dqa_guid
            WHERE form_uploaded.is_deleted=0 AND form_uploaded.with_findings='with findings' AND tbl_dqa_findings.responsible_person='$_SESSION[username]'";
        $sqlTot .= $q;
        $sqlRec .= $q;

        if (isset($where_con) && $where_con != '') {
            $sqlTot .= $where_con;
            $sqlRec .= $where_con;

        }

        $sqlRec .= " ORDER BY " . $columns[$params['order'][0]['column']] . ' ' . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . ", " . $params['length'] . " ";
        $query_tot = $mysql->query($sqlTot) or die($mysql->error);
        $total_records = $query_tot->num_rows;
        $query_records = $mysql->query($sqlRec) or die($mysql->error);
        if ($query_records->num_rows > 0) {
            while ($row = $query_records->fetch_row()) {
                $data[] = $row;
            }
        } else {
            $data = '';
        }
        $json_data = array("draw" => intval($params['draw']), "recordsTotal" => intval($total_records), "recordsFiltered" => intval($total_records), "data" => $data);
        echo json_encode($json_data);
    }

    public function display_2_lvl_dqa_findings($fk_ft)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            tbl_dqa_findings.findings,
            tbl_dqa_findings.is_deleted,
            tbl_dqa_findings.is_checked,
            tbl_dqa_findings.findings_guid
            FROM
            tbl_dqa_findings
            WHERE tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.fk_ft_guid=?");
        $q->bind_param('s', $fk_ft);
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

    public function total_findings_per_user($username)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            COUNT(tbl_dqa_findings.is_checked) as total_findings
            FROM
            tbl_dqa_findings
            WHERE tbl_dqa_findings.responsible_person='$username' AND tbl_dqa_findings.is_deleted=0 ";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total_findings'];
        }
    }

    public function complied_findings()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            COUNT(tbl_dqa_findings.is_checked) AS total_findings,
            SUM(tbl_dqa_findings.is_checked='1') AS total_complied,
            SUM(tbl_dqa_findings.is_checked='1') / COUNT(tbl_dqa_findings.is_checked) AS per_complied
        FROM
            tbl_dqa_findings
        WHERE
            tbl_dqa_findings.is_deleted = 0 AND tbl_dqa_findings.fk_findings NOT IN (7)";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            return $row = $result->fetch_assoc();
        }
    }

    public function total_findings_complied_per_user($username)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            COUNT(tbl_dqa_findings.is_checked) as total_findings
            FROM
            tbl_dqa_findings
            WHERE tbl_dqa_findings.responsible_person='$username' and tbl_dqa_findings.is_checked=1 AND tbl_dqa_findings.is_deleted=0";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total_findings'];
        }
    }

    public function total_findings_notcomplied_per_user($username)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            COUNT(tbl_dqa_findings.is_checked) as total_findings
            FROM
            tbl_dqa_findings
            WHERE tbl_dqa_findings.responsible_person='$username' and tbl_dqa_findings.is_checked=0 AND tbl_dqa_findings.is_deleted=0";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total_findings'];
        }
    }

    public function total_late_compliance($username)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                tbl_dqa.deadline_for_compliance,
                form_uploaded.date_uploaded,
                SUM(IF (DATEDIFF(form_uploaded.date_uploaded,tbl_dqa.deadline_for_compliance) > 0,1,0)) AS count_of_late,
                tbl_dqa_findings.responsible_person
            FROM
                form_uploaded
            INNER JOIN tbl_dqa_findings ON tbl_dqa_findings.fk_ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN tbl_dqa ON tbl_dqa.dqa_guid = tbl_dqa_findings.fk_dqa_guid
            WHERE(form_uploaded.is_deleted = 0 AND tbl_dqa_findings.is_deleted = 0)
            AND (tbl_dqa_findings.responsible_person = '$username' OR form_uploaded.uploaded_by = '$username')";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['count_of_late'];
        } else {
            return '0';
        }
    }

    public function findings_complied()
    {
        $mysql = $this->connectDatabase();

        foreach ($_POST['complied_findings'] as $finding) {

        }
    }

    public function update_formStatus($is_reviewed, $with_findings)
    {
        $mysql = $this->connectDatabase();
        $fk_file_id = $mysql->escape_string($_GET['file_id']);
        $fk_ft = $mysql->escape_string($_GET['fk_ft']);
        $update_file = "UPDATE `form_uploaded` SET `with_findings`='$with_findings',`is_findings_complied`=NULL, `is_reviewed`='$is_reviewed', `reviewed_by`='$_SESSION[user_fullname]',`date_reviewed`=NOW() WHERE (`file_id`='$fk_file_id') LIMIT 1";
        $update_file_result = $mysql->query($update_file) or die($mysql->error);
        if ($update_file_result) {
            return true;
        } else {
            echo 'error';
        }
    }

    public function updateActualNofindings($fk_ft)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            Count(form_uploaded.file_id) AS uploaded,
            form_target.ft_guid,
            form_target.target,
            form_target.actual
            FROM
            form_uploaded
            INNER JOIN form_target ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            WHERE  form_target.ft_guid='$fk_ft' AND form_uploaded.is_deleted=0 AND (form_uploaded.with_findings = 'no findings')";
        $result = $mysql->query($q) or die($mysql->error);
        while ($row = $result->fetch_assoc()) {
            $update = "UPDATE `form_target` SET `actual_nofindings`='$row[uploaded]' WHERE (`ft_guid`='$fk_ft')";
            $mysql->query($update) or die($mysql->error);
        }
    }

    public function update_formStatus_compliance()
    {
        $mysql = $this->connectDatabase();
        //$fk_guid = $mysql->escape_string($_GET['dqa_guid']);
        $fk_file_id = $mysql->escape_string($_GET['file_id']);
        $fk_ft = $mysql->escape_string($_GET['fk_ft']);

        $is_reviewed = ($_POST['is_reviewed'] == 1) ? 'reviewed' : 'for review';
        $with_findings = ($_POST['with_findings'] == 1) ? 'with findings' : 'no findings';
        $is_complied = ($_POST['is_complied'] == 1) ? 'complied' : 'not complied';

        /*if ($is_complied == 'yes') {
            $complied = "UPDATE `form_uploaded` SET `is_findings_complied`='complied' WHERE (fk_ft_guid='$_GET[fk_ft]')";
            $update_complied = $mysql->query($complied) or die($mysql->error);
        }*/

        $update_file = "UPDATE `form_uploaded` SET `with_findings`='$with_findings',`is_reviewed`='$is_reviewed',`is_complied`='$is_complied', `reviewed_by`='$_SESSION[user_fullname]',`date_reviewed`=NOW() WHERE (`file_id`='$fk_file_id') LIMIT 1";
        $update_file_result = $mysql->query($update_file) or die($mysql->error);
        if ($update_file_result) {
            //$this->update_count($_GET['fk_ft']);
            return true;
        } else {
            echo 'error';
        }
    }

    public function check_findings_exist($fk_ft, $file_id)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            tbl_dqa_findings.findings_guid,
            tbl_dqa_findings.fk_ft_guid
            FROM
            tbl_dqa_findings
            WHERE  tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.fk_ft_guid='$fk_ft' AND tbl_dqa_findings.fk_file_guid='$file_id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows >= 1) {
            return true;
        }
    }

    public function file_status()
    {
        $mysql = $this->connectDatabase();
        $file_id = $mysql->escape_string($_GET['file_id']);
        $q = "SELECT
            form_uploaded.with_findings,
            form_uploaded.is_complied,
            form_uploaded.is_reviewed
            FROM
            form_uploaded
            WHERE file_id='$file_id'";
        $result = $mysql->query($q) or die($mysql->error);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->with_findings = $row['with_findings'];
            $this->is_complied = $row['is_complied'];
            $this->is_reviewed = ($row['is_reviewed'] == 'reviewed') ? 'checked' : 'for review';
        } else {
            return false;
        }
    }

    public function get_file_findings($file_id)
    {
        $mysql = $this->connectDatabase();
        $params = $_REQUEST;
        $columns = array(0 => 'tbl_dqa_findings.created_at');
        $where_con = $sqlTot = $sqlRec = "";
        if (!empty($params['search']['value'])) {
            $where_con .= " AND (tbl_dqa_findings.findings LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR tbl_dqa_findings.added_by LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR tbl_dqa_findings.deadline_for_compliance LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR tbl_dqa_findings.created_at LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR tbl_dqa_findings.is_checked LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.first_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.last_name LIKE '%" . $params['search']['value'] . "%')";
        }
        $q = "SELECT
            tbl_dqa_findings.findings,
            tbl_dqa_findings.added_by,
            tbl_dqa_findings.deadline_for_compliance,
            tbl_dqa_findings.created_at,
            DATEDIFF(NOW(),tbl_dqa_findings.deadline_for_compliance) as days_overdue,
            tbl_dqa_findings.is_checked,
            concat(personal_info.first_name,' ',personal_info.last_name) AS responsible_person,
            personal_info.first_name,
            personal_info.last_name,
            tbl_dqa_findings.findings_guid,
            tbl_dqa_findings.fk_file_guid
            FROM
            form_uploaded
            INNER JOIN tbl_dqa_findings ON form_uploaded.file_id = tbl_dqa_findings.fk_file_guid
            INNER JOIN personal_info ON personal_info.fk_username = tbl_dqa_findings.responsible_person
            WHERE (tbl_dqa_findings.fk_file_guid='$file_id') AND tbl_dqa_findings.is_deleted=0";
        $sqlTot .= $q;
        $sqlRec .= $q;

        if (isset($where_con) && $where_con != '') {
            $sqlTot .= $where_con;
            $sqlRec .= $where_con;
        }
        $sqlRec .= " ORDER BY " . $columns[$params['order'][0]['column']] . ' ' . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . ", " . $params['length'] . " ";
        $query_tot = $mysql->query($sqlTot) or die($mysql->error);
        $total_records = $query_tot->num_rows;
        $query_records = $mysql->query($sqlRec) or die($mysql->error);
        if ($query_records->num_rows > 0) {
            while ($row = $query_records->fetch_row()) {
                $data[] = $row;
            }
        } else {
            $data = '';
        }
        $json_data = array("draw" => intval($params['draw']), "recordsTotal" => intval($total_records), "recordsFiltered" => intval($total_records), "data" => $data);
        echo json_encode($json_data);
    }

    public function delete_finding($finding_id)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->real_escape_string($finding_id);
        $q = "UPDATE `tbl_dqa_findings` SET `is_deleted`='1' WHERE (`findings_guid`='$id') LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            return true;
        }

    }

    public function display_compliance($fk_ft)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->real_escape_string($fk_ft);
        $q = "SELECT
            form_uploaded.original_filename,
            form_uploaded.file_path,
            form_uploaded.date_uploaded,
            form_uploaded.is_reviewed,
            form_uploaded.with_findings,
            form_uploaded.is_complied,
            form_uploaded.reviewed_by,
            CONCAT(personal_info.first_name,' ',personal_info.last_name) AS uploaded_by,
            personal_info.first_name,
            personal_info.last_name,
            lib_form.form_name,
            form_uploaded.file_id
            FROM
            form_uploaded
            INNER JOIN personal_info ON personal_info.fk_username = form_uploaded.uploaded_by
            INNER JOIN form_target ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            WHERE form_uploaded.fk_ft_guid='$fk_ft' AND form_uploaded.is_compliance='compliance' AND form_uploaded.is_deleted=0";
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

    public function count_pending()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            COUNT(form_uploaded.is_reviewed) as count_pending
            FROM
            form_uploaded
            where form_uploaded.is_deleted=0 AND form_uploaded.is_reviewed='for review'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['count_pending'];
        }
    }

    public function count_reviewed()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            COUNT(form_uploaded.is_reviewed) as count_reviewed
            FROM
            form_uploaded
            where form_uploaded.is_deleted=0 AND form_uploaded.is_reviewed='reviewed'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['count_reviewed'];
        }
    }

    public function userReviewed()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                COUNT(
                    DISTINCT form_uploaded.file_id
                ) as youReviewed
            FROM
                form_uploaded
            INNER JOIN tbl_dqa_list ON tbl_dqa_list.fk_file_guid = form_uploaded.file_id
            WHERE
                (
                    form_uploaded.is_deleted = 0
                    OR form_uploaded.is_deleted IS NULL
                    OR tbl_dqa_list.is_delete = 0
                )
            AND tbl_dqa_list.added_by = '$_SESSION[username]'
            AND form_uploaded.is_reviewed = 'reviewed'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['youReviewed'];
        }
    }

    public function ncddpForReview_status()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            Sum(IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS sum_reviewed,
            Sum(IF (
                        form_uploaded.is_reviewed = 'for review'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS sum_pending,
            Sum(form_target.target) AS target,
            Sum(IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.with_findings = 'no findings'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS accomplished,
            lib_municipality.mun_name,
            form_target.fk_psgc_mun,
            lib_cycle.cycle_name,
            form_target.fk_cycle
            FROM
            form_target
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            LEFT JOIN form_uploaded ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
            WHERE
                cycles.fk_modality = 3
            GROUP BY
            form_target.fk_psgc_mun,form_target.fk_cycle
            ORDER BY
                lib_municipality.mun_name ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function ncddpForReview_CityStatus($city_id, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $city_id = $mysql->real_escape_string($city_id);
        $cycle_id = $mysql->real_escape_string($cycle_id);
        $q = "SELECT
            Sum(IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS sum_reviewed,
            Sum(IF (
                        form_uploaded.is_reviewed = 'for review'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS sum_pending,
            Sum(form_target.target) AS target,
            Sum(IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.with_findings = 'no findings'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS accomplished,
            lib_municipality.mun_name,
            form_target.fk_psgc_mun,
            lib_cycle.cycle_name,
            form_target.fk_cycle
            FROM
            form_target
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            LEFT JOIN form_uploaded ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
            WHERE
                cycles.fk_modality = 3 AND form_target.fk_psgc_mun='$city_id' AND form_target.fk_cycle='$cycle_id'
            GROUP BY
            form_target.fk_psgc_mun,form_target.fk_cycle
            ORDER BY
                lib_municipality.mun_name ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function ncddpForReview_Activity($modality_id, $cadt_id, $city_id, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $cycle_id = $mysql->real_escape_string($cycle_id);
        $q = "SELECT
                SUM(
                    IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )
                ) AS sum_reviewed,
                SUM(

                    IF (
                        form_uploaded.is_reviewed = 'for review'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )
                ) AS sum_pending,
            SUM(form_target.target) as target,
            SUM(
                    IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.with_findings = 'no findings'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )
                ) AS accomplished,
                lib_activity.activity_name
            FROM
                form_target
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            LEFT JOIN form_uploaded ON form_target.ft_guid = form_uploaded.fk_ft_guid
            WHERE
                cycles.fk_modality = '$modality_id'
            AND (form_target.fk_cadt = '$cadt_id' OR form_target.fk_psgc_mun='$city_id')
            AND form_target.fk_cycle = $cycle_id
            GROUP BY
                lib_form.fk_activity
            ORDER BY
                lib_form.id ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function uploadingStat_ncddp()
    {
        $mysql = $this->connectDatabase();

        $q = "SELECT
            lib_municipality.mun_name,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 46 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 46 THEN form_target.target END) * 100, 1) as MDRRMC,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 47 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 47 THEN form_target.target END) * 100, 1) as BDRRMC,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 48 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 48 THEN form_target.target END) * 100, 1) as PDW,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 49 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 49 THEN form_target.target END) * 100, 1) as MIAC_Tech,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 50 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 50 THEN form_target.target END) * 100, 1) as OBA,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 52 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 52 THEN form_target.target END) * 100, 1) as SPI,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 53 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 53 THEN form_target.target END) * 100, 1) as Reflection,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 54 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 54 THEN form_target.target END) * 100, 1) as AR,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 55 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 55 THEN form_target.target END) * 100, 1) as FA,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 56 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 56 THEN form_target.target END) * 100, 1) as SPW,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 57 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 57 THEN form_target.target END) * 100, 1) as RAL,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity =  8 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 8 THEN form_target.target END)* 100, 1) as GRS,
                FORMAT(SUM(CASE WHEN lib_form.fk_activity = 59 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity =59 THEN form_target.target END)* 100, 1) as CT,
                FORMAT(SUM(form_target.actual)/SUM(form_target.target)* 100, 1) as overall,
                FORMAT(SUM(form_target.reviewed)/SUM(form_target.target)* 100, 1) as reviewed,
                FORMAT(SUM(form_target.actual_nofindings)/SUM(form_target.target)* 100, 1) as dqa
            FROM
            form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            WHERE
             form_target.fk_cycle IN (11,13) AND form_target.target>0
            GROUP BY
            lib_municipality.psgc_mun";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function uploadingStat_ipcdd()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                lib_cadt.cadt_name,
                    FORMAT(SUM(CASE WHEN lib_form.fk_activity = 29 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 29 THEN form_target.target END )*100,1) as OWTS,
                    FORMAT(SUM(CASE WHEN lib_form.fk_activity = 30 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 30 THEN form_target.target END )*100,1) as CSD,
                    FORMAT(SUM(CASE WHEN lib_form.fk_activity = 31 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 31 THEN form_target.target END )*100,1) as ADA,
                    FORMAT(SUM(CASE WHEN lib_form.fk_activity = 32 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 32 THEN form_target.target END )*100,1) as 1stICC,
                    FORMAT(SUM(CASE WHEN lib_form.fk_activity = 33 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 33 THEN form_target.target END )*100,1) as ADSDPP_REV,
                    FORMAT(SUM(CASE WHEN lib_form.fk_activity = 34 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 34 THEN form_target.target END )*100,1) as 2ndICC,
                    FORMAT(SUM(CASE WHEN lib_form.fk_activity = 35 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 35 THEN form_target.target END )*100,1) as PWD,
                    FORMAT(SUM(CASE WHEN lib_form.fk_activity = 36 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 36 THEN form_target.target END )*100,1) as MIAC_Tech,
                    FORMAT(SUM(CASE WHEN lib_form.fk_activity = 37 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 37 THEN form_target.target END )*100,1) as DPF,
                    FORMAT(SUM(CASE WHEN lib_form.fk_activity = 38 THEN form_target.actual END )/SUM(CASE WHEN lib_form.fk_activity = 38 THEN form_target.target END )*100,1) as ADSDPP_Linking,
                    FORMAT(SUM(form_target.actual)/SUM(form_target.target)* 100, 1) as overall,
                    FORMAT(SUM(form_target.reviewed)/SUM(form_target.target)* 100, 1) as reviewed,
                    FORMAT(SUM(form_target.actual_nofindings)/SUM(form_target.target)* 100, 1) as dqa
                FROM
                form_target
                INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
                left JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
                WHERE
                             form_target.fk_cycle IN (9,10)
                 GROUP BY
                            form_target.fk_cadt ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function ipcddForReview_status()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            Sum(IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS sum_reviewed,
            Sum(IF (
                        form_uploaded.is_reviewed = 'for review'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS sum_pending,
            Sum(form_target.target) AS target,
            Sum(IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.with_findings = 'no findings'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS accomplished,
            lib_cadt.cadt_name,
            lib_cycle.cycle_name,
            lib_cadt.cadt_name,
            form_target.fk_cycle,
            form_target.fk_cadt
            FROM
            form_target
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            LEFT JOIN form_uploaded ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
            INNER JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            WHERE
                cycles.fk_modality = 2
            GROUP BY
            form_target.fk_cadt,form_target.fk_cycle
            ORDER BY
                lib_cadt.cadt_name ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function ipcddForReview_CadtStatus($cadt_id, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $cadt_id = $mysql->real_escape_string($cadt_id);
        $cycle_id = $mysql->real_escape_string($cycle_id);
        $q = "SELECT
            Sum(IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS sum_reviewed,
            Sum(IF (
                        form_uploaded.is_reviewed = 'for review'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS sum_pending,
            Sum(form_target.target) AS target,
            Sum(IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.with_findings = 'no findings'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS accomplished,
            lib_cadt.cadt_name,
            lib_cycle.cycle_name,
            lib_cadt.cadt_name,
            form_target.fk_cycle,
            form_target.fk_cadt
            FROM
            form_target
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            LEFT JOIN form_uploaded ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
            INNER JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            WHERE
                cycles.fk_modality = 2 AND form_target.fk_cadt='$cadt_id' AND form_target.fk_cycle='$cycle_id'
            GROUP BY
            form_target.fk_cadt,form_target.fk_cycle
            ORDER BY
                lib_cadt.cadt_name ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function get_dqa_info($dqaguid)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            DATE_FORMAT(tbl_dqa.date_conducted,'%m/%d/%Y')as date_conducted,
            DATE_FORMAT(tbl_dqa.deadline_for_compliance,'%m/%d/%Y')as deadline_for_compliance,
            tbl_dqa.title,
            tbl_dqa.fk_psgc_mun,
            tbl_dqa.fk_cadt_id,
            tbl_dqa.fk_cycle
            FROM
            tbl_dqa
            where dqa_guid = '$dqaguid'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['title'] = $row['title'];
                $data['date_conducted'] = $row['date_conducted'];
                $data['deadline_for_compliance'] = $row['deadline_for_compliance'];
                $this->dqa_title = $row['title'];
                $this->cycle_id = $row['fk_cycle'];
                $this->area_id = ($row['fk_psgc_mun'] == '') ? $row['fk_cadt_id'] : $row['fk_psgc_mun'];
            }
            return json_encode($data);
        }
    }

    public function update_dqa_info($dqaguid)
    {
        $mysql = $this->connectDatabase();
        // $post_doc = $_POST['date_of_compliance'];
        // $date_of_comp = new \DateTime($post_doc);
        // $d2 = $date_of_comp->format('Y-m-d');
        $title = $mysql->real_escape_string($_POST['dqa_title']);
        $staff = $mysql->real_escape_string($_POST['staff']);
        $q = "UPDATE `tbl_dqa` SET `responsible_person`='$staff', `title`='$title' WHERE (`dqa_guid`='$dqaguid') LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($mysql->affected_rows > 0) {
            return true;
        }
    }

    public function totFindings_byMuni($psgc_mun, $cycle)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            SUM(IF(tbl_dqa_findings.is_checked=1,1,0)) as complied,
            SUM(IF(tbl_dqa_findings.is_checked=0,1,0)) as not_complied,
            SUM(IF(tbl_dqa_findings.is_deleted=0,1,0)) as total_findings
            FROM
            tbl_dqa_findings
            INNER JOIN tbl_dqa ON tbl_dqa_findings.fk_dqa_guid = tbl_dqa.dqa_guid
            WHERE (tbl_dqa.fk_psgc_mun='$psgc_mun' OR tbl_dqa.fk_cadt_id='$psgc_mun') AND tbl_dqa.fk_cycle='$cycle' AND tbl_dqa_findings.is_deleted=0";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        if ($result->num_rows > 0) {
            $this->complied_count = $row['complied'];
            $this->notcomplied_count = $row['not_complied'];
            $this->total_findings = $row['total_findings'];
        }

    }

    public function totFindings_byUser($psgc_mun, $cycle, $user)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            SUM(IF(tbl_dqa_findings.is_checked=1,1,0)) as complied,
            SUM(IF(tbl_dqa_findings.is_checked=0,1,0)) as not_complied,
            SUM(IF(tbl_dqa_findings.is_deleted=0,1,0)) as total_findings
            FROM
            tbl_dqa_findings
            INNER JOIN tbl_dqa ON tbl_dqa_findings.fk_dqa_guid = tbl_dqa.dqa_guid
            WHERE (tbl_dqa.fk_psgc_mun='$psgc_mun' OR tbl_dqa.fk_cadt_id='$psgc_mun') AND tbl_dqa.fk_cycle='$cycle' AND tbl_dqa_findings.responsible_person='$user' AND tbl_dqa_findings.is_deleted=0";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        if ($result->num_rows > 0) {
            $this->complied_count = $row['complied'];
            $this->notcomplied_count = $row['not_complied'];
            $this->total_findings = $row['total_findings'];
        }

    }
}
