<?php

namespace app;

class Upload
{
    public $dqa_title;
    public $can_upload;

    public function connectDatabase()
    {
        $database = Database::getInstance();
        return $database->getConnection();
    }

    public function checkFileMime()
    {
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($file_info, $_FILES['fileToUpload']['tmp_name']);
        if ($mime == 'application/pdf') {
            return true;
        } else {
            return false;
        }
    }

    public function upload_file()
    {
        $fileName = basename($_FILES['fileToUpload']['name']);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $fk_ft = $_POST['act_ft'];
        $date = new \DateTime();
        $mysql = $this->connectDatabase();
        $uniqueFileName = uniqid($date->getTimestamp(), false) . "." . $extension;
        $ceac = new Ceac();
        $file_id = $ceac->v4();

        if ($this->check_modality($fk_ft) == 'ncddp_drom_2020') {
            $dir = '../../../../Storage/ncddp_drom_2020';
            $mov_path = '/kc-movs/Storage/ncddp_drom_2020/' . $uniqueFileName;
        }
        if ($this->check_modality($fk_ft) == 'ncddp') {
            $dir = '../../../../Storage' . '\ncddp';
            $mov_path = '/kc-movs/Storage/ncddp/' . $uniqueFileName;
        }
        if ($this->check_modality($fk_ft) == 'ipcdd') {
            $dir = '../../../../Storage' . '\ipcdd';
            $mov_path = '/kc-movs/Storage/ipcdd/' . $uniqueFileName;
        }


        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $dir . '/' . $uniqueFileName)) {
            $q = $mysql->prepare("INSERT INTO `form_uploaded`(`file_id`, `fk_ft_guid`, `original_filename`, `generated_filename`, `file_path`, `date_uploaded`, `with_findings`, `is_findings_complied`, `is_reviewed`,`is_deleted`,`uploaded_by`) VALUES (?, ?, ?, ?,?,NOW(),NULL,NULL,'for review',0,?)");
            $q->bind_param('ssssss', $file_id, $fk_ft, $fileName, $uniqueFileName, $mov_path, $_SESSION['username']);
            $q->execute();
            if ($q->affected_rows > 0) {
                $dqa = new Dqa();
                if ($this->set_canUpload($fk_ft) && $dqa->update_count($fk_ft))
                    echo 'uploaded';
            } else {
                echo 'Something went upon saving';
            }
        } else {
            echo 'Something went wrong while uploading the file';
        }
    }

    public function upload_compliance()
    {
        $fileName = basename($_FILES['fileToUpload']['name']);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $fk_ft = $_GET['fk_ft'];
        $date = new \DateTime();
        $mysql = $this->connectDatabase();
        $uniqueFileName = uniqid($date->getTimestamp(), false) . "." . $extension;
        $ceac = new Ceac();
        $file_id = $ceac->v4();
        if ($this->check_modality($fk_ft) == 'ncddp_drom_2020') {
            $dir = '../../../../Storage/ncddp_drom_2020';
            $mov_path = '/kc-movs/Storage/ncddp_drom_2020/' . $uniqueFileName;
        }
        if ($this->check_modality($fk_ft) == 'ncddp') {
            $dir = '../../../../Storage' . '\ncddp';
            $mov_path = '/kc-movs/Storage/ncddp/' . $uniqueFileName;
        }
        if ($this->check_modality($fk_ft) == 'ipcdd') {
            $dir = '../../../../Storage' . '\ipcdd';
            $mov_path = '/kc-movs/Storage/ipcdd/' . $uniqueFileName;
        }

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $dir . '/' . $uniqueFileName)) {
            $q = $mysql->prepare("INSERT INTO `form_uploaded`(`file_id`, `fk_ft_guid`, `original_filename`, `generated_filename`, `file_path`, `date_uploaded`, `with_findings`, `is_findings_complied`, `is_reviewed`,`is_deleted`,`uploaded_by`,is_compliance) VALUES (?, ?, ?, ?,?,NOW(),NULL,NULL,'for review',0,?,'compliance')");
            $q->bind_param('ssssss', $file_id, $fk_ft, $fileName, $uniqueFileName, $mov_path, $_SESSION['username']);
            $q->execute();
            if ($q->affected_rows > 0) {
                if ($this->set_canUpload($fk_ft))
                    echo 'uploaded';
            } else {
                echo 'Something went upon saving';
            }
        } else {
            echo 'Something went wrong while uploading the file';
        }
    }

    public function set_canUpload($fk_target)
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

        $q = "UPDATE `form_target` SET `can_upload`=1 WHERE `ft_guid` = '$id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            return true;
        }
    }


    public function deduct_actual_count($fk_guid)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->escape_string($fk_guid);
        $q = "SELECT
            form_target.actual
        FROM
            form_target
            WHERE form_target.ft_guid='$id'";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        $val = $row['actual'] - 1;
        $q = "UPDATE `form_target` SET `actual` = '$val' WHERE `ft_guid` = '$id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            return true;
        }
    }

    public function check_target($fk_ft)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->escape_string($fk_ft);
        $q = "SELECT IF(form_target.target > form_target.actual,'true','false') AS check_target,form_target.can_upload
            FROM
                form_target
            WHERE
                form_target.ft_guid = '$id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->can_upload = $row['can_upload'];
            return $row['check_target'];
        }
    }

    public function check_modality($fk_ft)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->escape_string($fk_ft);
        $q = "SELECT
            lib_modality.modality_name
            FROM
            form_target
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_modality ON cycles.fk_modality = lib_modality.id
            WHERE form_target.ft_guid='$fk_ft'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['modality_name'];
        }
    }

    public function viewDqaItems()
    {
        $mysql = $this->connectDatabase();
        $dqaId = $_GET['dqaId'];
        $q = "SELECT
                lib_municipality.mun_name,
                lib_barangay.brgy_name,
                CONCAT(lib_form.form_name,IF (lib_barangay.brgy_name IS NOT NULL,', ',''),COALESCE (lib_barangay.brgy_name, '')) AS forms,
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
        while ($row = $result->fetch_row()) {
            $data[] = $row;
        }
        $json_data = array("data" => $data);
        echo json_encode($json_data);
    }

    public function dqa_items_all()
    {
        $mysql = $this->connectDatabase();
        $dqa_id = $mysql->real_escape_string($_GET['dqa_id']);
        $params = $_REQUEST;
        $columns = array(0 => 'tbl_dqa_list.created_at', 1 => 'form_uploaded.original_filename', 2 => 'personal_info.first_name', 3 => 'form_uploaded.reviewed_by', 4 => 'form_uploaded.with_findings');
        $where_con = $sqlTot = $sqlRec = "";

        if (!empty($params['search']['value'])) {
            $where_con .= " AND (tbl_dqa_list.created_at LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.original_filename LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.is_reviewed LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.with_findings LIKE '%" . $params['search']['value'] . "'";
            $where_con .= " OR form_uploaded.is_findings_complied LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_form.form_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_barangay.brgy_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.file_id LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.last_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_municipality.mun_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_cadt.cadt_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.first_name LIKE '%" . $params['search']['value'] . "%') GROUP BY form_uploaded.file_id ";
        } else {
            $where_con .= " GROUP BY form_uploaded.file_id ";
        }
        $q = "SELECT
                lib_municipality.mun_name,
                lib_barangay.brgy_name,
                CONCAT(lib_form.form_name,IF (lib_barangay.brgy_name IS NOT NULL,', ',''),COALESCE (lib_barangay.brgy_name, '')) AS forms,
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
            LEFT JOIN tbl_dqa_list ON tbl_dqa.dqa_guid = tbl_dqa_list.fk_dqa_guid
            INNER JOIN form_uploaded ON form_uploaded.file_id = tbl_dqa_list.fk_file_guid
            INNER JOIN form_target ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN users ON users.username = form_uploaded.uploaded_by
            INNER JOIN personal_info ON personal_info.fk_username = users.username
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            LEFT JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            where
             tbl_dqa.conducted_by='$_SESSION[username]' AND tbl_dqa_list.is_delete='0' AND form_uploaded.is_deleted='0'";
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

    public function uploaded_files_subject_for_dqa()
    {
        $mysql = $this->connectDatabase();
        $params = $_REQUEST;
        $columns = array(0 => 'tbl_dqa_list.created_at', 1 => 'form_uploaded.original_filename', 2 => 'personal_info.first_name', 3 => 'form_uploaded.with_findings', 4 => 'form_uploaded.reviewed_by', 5 => 'tbl_dqa.deadline_for_compliance', 6 => 'days_overdue');
        $where_con = $sqlTot = $sqlRec = "";

        if (!empty($params['search']['value'])) {
            $where_con .= " AND (tbl_dqa_list.created_at LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.original_filename LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.is_reviewed LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.with_findings LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.is_findings_complied LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_form.form_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_barangay.brgy_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.file_id LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.last_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_municipality.mun_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_cadt.cadt_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.first_name LIKE '%" . $params['search']['value'] . "%') GROUP BY form_uploaded.file_id ";
        } else {
            $where_con .= " GROUP BY form_uploaded.file_id ";
        }
        $q = "SELECT
                    lib_municipality.mun_name,
                    lib_barangay.brgy_name,
                    CONCAT(lib_form.form_name,IF (lib_barangay.brgy_name IS NOT NULL,', ',''),COALESCE (lib_barangay.brgy_name, '')) AS forms,
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
                    form_uploaded
                    INNER JOIN form_target ON form_target.ft_guid = form_uploaded.fk_ft_guid
                    LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
                    LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
                    INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
                    INNER JOIN users ON users.username = form_uploaded.uploaded_by
                    INNER JOIN personal_info ON personal_info.fk_username = users.username
                    LEFT JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
                    INNER JOIN tbl_dqa_list ON tbl_dqa_list.fk_file_guid = form_uploaded.file_id
                    WHERE (form_uploaded.is_deleted = 0 OR form_uploaded.is_deleted IS NULL) AND tbl_dqa_list.added_by='$_SESSION[username]'";
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

    public function tbl_select_file_to_dqa($fk_psgc_mun, $cadt_id, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $params = $_REQUEST;
        $columns = array(0 => 'form_uploaded.date_uploaded', 1 => 'form_uploaded.original_filename', 2 => 'form_uploaded.date_uploaded');
        $where_con = $sqlTot = $sqlRec = "";

        if (!empty($params['search']['value'])) {
            $where_con .= " AND (form_uploaded.date_uploaded LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.original_filename LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_activity.activity_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_form.form_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_barangay.brgy_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.file_id LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.last_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_municipality.mun_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_cadt.cadt_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.first_name LIKE '%" . $params['search']['value'] . "%') ";
        }
        $q = "SELECT
                form_uploaded.date_uploaded,
                CONCAT(
                    lib_form.form_name,
                IF (
                    lib_barangay.brgy_name IS NOT NULL,
                    ',',
                    ''
                ),
                COALESCE (lib_barangay.brgy_name, '')
                ) AS forms,
                form_uploaded.original_filename,
                CONCAT(
                    personal_info.first_name,
                    ' ',
                    personal_info.last_name
                ) AS uploaded_by,
                form_uploaded.file_path,
                form_uploaded.file_id,
                form_target.ft_guid,
                lib_activity.activity_name,
                form_uploaded.is_added_to_dqa
            FROM
                form_uploaded
            LEFT JOIN tbl_dqa_list ON tbl_dqa_list.fk_file_guid = form_uploaded.file_id
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
                    tbl_dqa_list.added_by = '$_SESSION[username]'
            )";
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
                if ($row[8] == 0) {
                    array_push($row, $this->conducted_dqa($row[5]));
                }
                $data[] = $row;
            }
        } else {
            $data = '';
        }
        $json_data = array("draw" => intval($params['draw']), "recordsTotal" => intval($total_records), "recordsFiltered" => intval($total_records), "data" => $data);
        echo json_encode($json_data);
    }

    public function conducted_dqa($file_id)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            tbl_dqa_list.fk_file_guid,
            tbl_dqa_list.fk_dqa_guid,
            tbl_dqa_list.added_by,
            tbl_dqa.title,
            tbl_dqa.deadline_for_compliance,
            personal_info.first_name,
            personal_info.last_name,
            personal_info.pic_url
            FROM
            tbl_dqa_list
            INNER JOIN tbl_dqa ON tbl_dqa.dqa_guid = tbl_dqa_list.fk_dqa_guid
            INNER JOIN personal_info ON personal_info.fk_username = tbl_dqa.conducted_by
            WHERE fk_file_guid='$file_id'";
        $result = $mysql->query($q) or die($mysql->error);
        $c = '';
        while ($row = $result->fetch_assoc()) {
            $c = $row;
        }
        return $c;
    }

    public function tbl_latestUpload()
    {
        $mysql = $this->connectDatabase();
        $params = $_REQUEST;
        $columns = array(0 => 'form_uploaded.date_uploaded', 1 => 'form_uploaded.date_uploaded', 2 => 'form_uploaded.original_filename', 3 => 'form_uploaded.uploaded_by', 4 => 'form_uploaded.with_findings', 5 => 'form_uploaded.reviewed_by');
        $where_con = $sqlTot = $sqlRec = "";

        if (!empty($params['search']['value'])) {
            $where_con .= " AND (lib_form.form_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.original_filename LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.first_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.last_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_barangay.brgy_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.is_reviewed LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.with_findings LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_uploaded.date_reviewed LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR tbl_dqa.deadline_for_compliance LIKE '%" . $params['search']['value'] . "%') GROUP BY form_uploaded.file_id ";
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
            DATEDIFF(form_uploaded.date_uploaded,tbl_dqa.deadline_for_compliance) AS days_overdue,
            tbl_dqa.dqa_guid,
            form_uploaded.file_id,
            form_target.ft_guid,
            tbl_dqa_findings.added_by
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
            WHERE form_uploaded.is_deleted = 0 AND (form_uploaded.with_findings='no findings' OR form_uploaded.with_findings is NULL) 
            AND form_uploaded.uploaded_by='$_SESSION[username]'";
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

    public function del_file($file_id, $fk_guid)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->escape_string($file_id);
        $q = "UPDATE `form_uploaded` SET `is_deleted`='1' WHERE (`file_id`='$id' AND is_reviewed='for review')";
        $result = $mysql->query($q) or die($mysql->error);
        if ($mysql->affected_rows > 0) {
            $this->deduct_actual_count($fk_guid);
            return true;
        } else {
            return false;
        }
    }
    public function del_file_compliance($file_id)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->escape_string($file_id);
        $q = "UPDATE `form_uploaded` SET `is_deleted`='1' WHERE (`file_id`='$file_id' AND is_compliance='compliance' AND is_reviewed='for review') LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($mysql->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}