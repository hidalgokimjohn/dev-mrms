<?php

namespace app;

class Ceac
{
    public function connectDatabase()
    {
        $database = Database::getInstance();
        return $database->getConnection();
    }

    public function checklistExist($city, $cadt_id, $cycle)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				form_target.ft_guid
				FROM
				form_target
				WHERE (fk_psgc_mun = ? OR fk_cadt = ?) AND fk_cycle = ?");
        $q->bind_param('iii', $city, $cadt_id, $cycle);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function v4()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

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
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

    public function barangay($psgc_mun)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				lib_barangay.psgc_brgy,
				lib_barangay.brgy_name,
       			lib_barangay.psgc_mun
			FROM
				lib_barangay
			WHERE
				lib_barangay.psgc_mun = ?
				ORDER BY lib_barangay.psgc_brgy ASC");
        $q->bind_param('i', $psgc_mun);
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

    public function ipcdd_col($cadt, $cycle, $level)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            implementing_cadt_icc.fk_cycles,
            implementing_cadt_icc.fk_cadt_id,
            implementing_cadt_icc.fk_psgc_mun,
            implementing_cadt_icc.fk_psgc_brgy,
            lib_municipality.mun_name,
            lib_barangay.brgy_name,
            lib_sitio.sitio_name,
            implementing_cadt_icc.`level`
            FROM
            implementing_cadt_icc
            LEFT JOIN lib_sitio ON lib_sitio.psgc_sitio = implementing_cadt_icc.fk_psgc_sitio
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = implementing_cadt_icc.fk_psgc_brgy
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = implementing_cadt_icc.fk_psgc_mun
            WHERE implementing_cadt_icc.fk_cadt_id=? AND implementing_cadt_icc.fk_cycles=? and implementing_cadt_icc.level=?
            ORDER BY lib_municipality.mun_name,lib_barangay.brgy_name,lib_sitio.sitio_name");
        $q->bind_param('iis', $cadt, $cycle, $level);
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

    public function barangay_icc($cadt)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            implementing_cadt_icc.fk_cadt_id,
            implementing_cadt_icc.fk_psgc_mun,
            implementing_cadt_icc.fk_psgc_brgy,
            implementing_cadt_icc.fk_psgc_sitio
            FROM
            implementing_cadt_icc
            WHERE implementing_cadt_icc.`level`='barangay' AND implementing_cadt_icc.fk_cadt_id=?");
        $q->bind_param('i', $cadt);
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

    public function create_checklist($group, $version, $city, $cycle)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				lib_form.form_type,
				form_checklist.fk_form_code
				FROM
				form_checklist
				INNER JOIN lib_form ON lib_form.form_code = form_checklist.fk_form_code
				INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
				INNER JOIN lib_category ON lib_activity.fk_category = lib_category.id
				INNER JOIN lib_modality ON lib_category.fk_modality = lib_modality.id
				WHERE form_checklist.`group`= ? and form_checklist.version= ?
				ORDER BY form_checklist.id ASC");
        $q->bind_param('ss', $group, $version);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['form_type'] == 'municipal') {
                    $guid = $this->v4();
                    $qq = $mysql->prepare("SELECT
						form_target.ft_guid
						FROM
						form_target
						WHERE fk_form = ? AND fk_psgc_mun = ? and fk_cycle = ?");
                    $qq->bind_param('sii', $row['fk_form_code'], $city, $cycle);
                    $qq->execute();
                    $result1 = $qq->get_result();
                    if ($result1->num_rows <= 0) {
                        $insert_muni = $mysql->prepare("INSERT INTO `form_target` (`ft_guid`, `fk_form`, `fk_psgc_mun`, `fk_cycle`, `target`, `actual`, `can_upload`)
							VALUES (?, ?, ?, ?, '1', '0', '0')");
                        $insert_muni->bind_param('ssii', $guid, $row['fk_form_code'], $city, $cycle);
                        $insert_muni->execute();
                        echo $city . ' ' . $insert_muni->affected_rows . 'ok';
                    }
                }
                if ($row['form_type'] == 'barangay') {
                    $barangays = $this->barangay($city);
                    foreach ($barangays as $barangay) {
                        $guid = $this->v4();
                        $qq = $mysql->prepare("SELECT
								form_target.ft_guid
								FROM
								form_target
								WHERE fk_form = ? AND fk_psgc_mun = ? AND fk_psgc_brgy = ? and fk_cycle = ?");
                        $qq->bind_param('siii', $row['fk_form_code'], $city, $barangay['psgc_brgy'], $cycle);
                        $qq->execute();
                        $result1 = $qq->get_result();
                        if ($result1->num_rows <= 0) {
                            $insert_muni = $mysql->prepare("INSERT INTO `form_target` (`ft_guid`, `fk_form`, `fk_psgc_mun`,`fk_psgc_brgy`, `fk_cycle`, `target`, `actual`, `can_upload`)
							VALUES (?, ?, ?, ?, ?, '1', '0', '0')");
                            $insert_muni->bind_param('ssiii', $guid, $row['fk_form_code'], $city, $barangay['psgc_brgy'], $cycle);
                            $insert_muni->execute();
                        }
                    }
                }
            }
        }
    }

    public function create_checklist_ipcdd($group, $version, $cadt, $cycle)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				lib_form.form_type,
				form_checklist.fk_form_code
				FROM
				form_checklist
				INNER JOIN lib_form ON lib_form.form_code = form_checklist.fk_form_code
				INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
				INNER JOIN lib_category ON lib_activity.fk_category = lib_category.id
				INNER JOIN lib_modality ON lib_category.fk_modality = lib_modality.id
				WHERE form_checklist.`group`= ? and form_checklist.version= ? and lib_category.id IN (3,4,5)
				ORDER BY form_checklist.id ASC");
        $q->bind_param('ss', $group, $version);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['form_type'] == 'municipal') {
                    $get_muni = "SELECT
                                     implementing_cadt_icc.fk_cadt_id,
                                     implementing_cadt_icc.fk_psgc_mun
                                     FROM
                                     implementing_cadt_icc
                                     WHERE implementing_cadt_icc.`level`='municipal' AND implementing_cadt_icc.fk_cadt_id='$cadt'";
                    $get_muni_result = $mysql->query($get_muni) or die($mysql->error);
                    if ($get_muni_result) {
                        while ($row_muni = $get_muni_result->fetch_assoc()) {
                            echo $row_muni['fk_psgc_mun'] . '<br>';
                            $guid = $this->v4();
                            $insert_muni = $mysql->prepare("INSERT INTO `form_target` (`ft_guid`, `fk_form`, `fk_psgc_mun`, `fk_cycle`,`fk_cadt`, `target`, `actual`, `can_upload`)
                                       VALUES (?, ?, ?, ?, ?, '1', '0', '0')");
                            $insert_muni->bind_param('ssiii', $guid, $row['fk_form_code'], $row_muni['fk_psgc_mun'], $cycle, $cadt);
                            $insert_muni->execute();
                            echo $insert_muni->error;
                        }
                    }
                }
                if ($row['form_type'] == 'municipal-ad') {
                    //load cadt municipality-ad
                    $guid = $this->v4();
                    $insert_muni_ad = $mysql->prepare("INSERT INTO `form_target` (`ft_guid`, `fk_form`, `fk_cycle`,`fk_cadt`, `target`, `actual`, `can_upload`)
                                VALUES (?, ?, ?, ?, '1', '0', '0')");
                    $insert_muni_ad->bind_param('ssii', $guid, $row['fk_form_code'], $cycle, $cadt);
                    $insert_muni_ad->execute();
                    echo $insert_muni_ad->error;

                }

                if ($row['form_type'] == 'barangay-icc') {
                    $barangays = $this->barangay_icc($cadt);
                    foreach ($barangays as $barangay) {
                        $guid = $this->v4();
                        $qq = $mysql->prepare("SELECT
                               form_target.ft_guid
                               FROM
                               form_target
                               WHERE fk_form = ? AND fk_psgc_mun = ? AND fk_psgc_brgy = ? AND fk_cycle = ? AND fk_cadt = ? ");
                        $qq->bind_param('siiii', $row['fk_form_code'], $barangay['fk_psgc_mun'], $barangay['psgc_brgy'], $cycle, $cadt);
                        $qq->execute();
                        $result1 = $qq->get_result();
                        if ($result1->num_rows <= 0) {
                            $insert_brgy = $mysql->prepare("INSERT INTO `form_target` (`ft_guid`, `fk_form`, `fk_psgc_mun`,`fk_psgc_brgy`,`fk_cadt`,`fk_psgc_sitio`, `fk_cycle`, `target`, `actual`, `can_upload`)
                           VALUES (?, ?, ?, ?, ?, ?,?, '1', '0', '0')");
                            $insert_brgy->bind_param('ssiiiii', $guid, $row['fk_form_code'], $barangay['fk_psgc_mun'], $barangay['fk_psgc_brgy'], $cadt, $barangay['fk_psgc_sitio'], $cycle);
                            $insert_brgy->execute();
                            echo $insert_brgy->error;
                        }
                    }
                }
            }
        }
    }

    public function cycles($modality, $year)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				cycles.id,
				lib_cycle.cycle_name,
				cycles.`year`,
				lib_modality.modality_name
			FROM
				cycles
			INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
			INNER JOIN lib_modality ON cycles.fk_modality = lib_modality.id
			WHERE
				lib_modality.modality_name = ?
			AND cycles.`year` = ?");
        $q->bind_param('si', $modality, $year);
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

    public function cycles_for_upload($year)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				cycles.id,
				lib_cycle.cycle_name,
				cycles.`year`,
				lib_modality.modality_name
			FROM
				cycles
			INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
			INNER JOIN lib_modality ON cycles.fk_modality = lib_modality.id
			WHERE cycles.`year` = ? and cycles.`status`='open'");
        $q->bind_param('i', $year);
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

    public function city_progress($cycle, $psgc_mun, $category)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                SUM(
                    IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.with_findings = 'no'
                        AND form_uploaded.is_findings_complied = 'yes'
                        AND form_uploaded.is_deleted = 0,
                        form_target.actual,
                        '0'
                    )
                ) / sum(form_target.target) * 100 AS tot_percentage,
                form_uploaded.is_reviewed,
                form_uploaded.with_findings,
                form_uploaded.is_findings_complied
            FROM
                form_target
                INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
                INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
                INNER JOIN lib_category ON lib_activity.fk_category = lib_category.id
                LEFT JOIN form_uploaded ON form_uploaded.fk_ft_guid = form_target.ft_guid
                WHERE form_target.fk_cycle='$cycle' AND form_target.fk_psgc_mun='$psgc_mun' AND lib_category.category_name='$category'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            return number_format($row['tot_percentage'], 2);
        }
    }

    public function ncddp_progress($modality, $year)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                SUM(

                    IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.with_findings = 'no findings'
                        AND (
                            form_uploaded.is_findings_complied = 'complied'
                            OR form_uploaded.is_findings_complied IS NULL
                            OR form_uploaded.is_complied = 'complied'
                            OR form_uploaded.is_complied IS NULL
                        )
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )
                ) / sum(form_target.target) * 100 AS tot_percentage,
                form_uploaded.is_reviewed,
                form_uploaded.with_findings,    
                form_uploaded.is_findings_complied,
                lib_modality.modality_name,
                cycles.`year`
            FROM
                form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
            INNER JOIN lib_category ON lib_activity.fk_category = lib_category.id
            LEFT JOIN form_uploaded ON form_uploaded.fk_ft_guid = form_target.ft_guid
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_modality ON cycles.fk_modality = lib_modality.id
            WHERE
                lib_modality.modality_name = '$modality'
            AND cycles.`year` = '$year'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            return number_format($row['tot_percentage'], 2);
        }
    }

    public function cadt_progress($cycle, $cadt, $category)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            SUM(
                                IF (
                                    form_uploaded.is_reviewed = 'reviewed'
                                    AND form_uploaded.with_findings = 'no'
                                    AND form_uploaded.is_findings_complied = 'yes'
                                    AND form_uploaded.is_deleted = 0,
                                    form_target.actual,
                                    '0'
                                )
                            ) / sum(form_target.target) * 100 AS tot_percentage,
            form_uploaded.is_reviewed,
            form_uploaded.with_findings,
            form_uploaded.is_findings_complied,
            lib_cadt.cadt_name
            FROM
            form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
            INNER JOIN lib_category ON lib_activity.fk_category = lib_category.id
            LEFT JOIN form_uploaded ON form_uploaded.fk_ft_guid = form_target.ft_guid
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
            INNER JOIN lib_modality ON cycles.fk_modality = lib_modality.id
            INNER JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            WHERE form_target.fk_cycle='$cycle' AND lib_cadt.id='$cadt' AND lib_modality.modality_name='ipcdd' AND lib_category.category_name='$category'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            return number_format($row['tot_percentage'], 2);
        }
    }

    public function checklist_formRow($psgc_mun, $cycle)
    {
        $mysql = $this->connectDatabase();
        $psgc_mun = $mysql->escape_string($psgc_mun);
        $cycle = $mysql->escape_string($cycle);
        $q = "SELECT form_target.fk_form,
                lib_activity.activity_name,
                lib_form.form_name,
                form_target.fk_form,
                lib_form.form_type,
                form_uploaded.is_reviewed,
                form_uploaded.with_findings,
                form_target.can_upload,
               Sum(IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.with_findings = 'no findings'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS actual
            FROM
                form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
            INNER JOIN lib_category ON lib_activity.fk_category = lib_category.id
            LEFT JOIN form_uploaded ON form_uploaded.fk_ft_guid = form_target.ft_guid
            WHERE
                form_target.fk_cycle = '$cycle'
            AND form_target.fk_psgc_mun = '$psgc_mun' and (form_uploaded.is_deleted = 0 OR form_uploaded.is_deleted is null)   
            GROUP BY
                form_target.fk_form
            ORDER BY
                lib_form.id ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function checklist_formRowUploading($psgc_mun, $cycle)
    {
        $mysql = $this->connectDatabase();
        $psgc_mun = $mysql->escape_string($psgc_mun);
        $cycle = $mysql->escape_string($cycle);
        $q = "SELECT form_target.fk_form,
                lib_activity.activity_name,
                lib_form.form_name,
                form_target.fk_form,
                lib_form.form_type,
                form_target.target,
                sum(form_target.target) as tot_target,
                sum(form_target.actual) as tot_actual,
                form_target.actual,
				FORMAT(SUM(form_target.actual)/SUM(form_target.target)*100,2) as percentage
            FROM
                form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
            INNER JOIN lib_category ON lib_activity.fk_category = lib_category.id
            WHERE
                form_target.fk_cycle = '$cycle'
            AND form_target.fk_psgc_mun = '$psgc_mun' and form_target.target>0   
            GROUP BY
                form_target.fk_form
            ORDER BY
                lib_form.id ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function checklist_formRow_ipcddUploading($cadt, $cycle)
    {
        $mysql = $this->connectDatabase();
        $psgc_mun = $mysql->escape_string($cadt);
        $cycle = $mysql->escape_string($cycle);
        $q = "SELECT
                lib_activity.activity_name,
                lib_form.form_name,
                form_target.fk_form,
                lib_form.form_type,
                sum(form_target.target) as tot_target,
                sum(form_target.actual) as tot_actual,
                format(sum(form_target.actual)/sum(form_target.target)*100,2) as percentage,
                lib_form.form_type
            FROM
                form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
            INNER JOIN lib_category ON lib_activity.fk_category = lib_category.id
            WHERE
                form_target.fk_cycle = '$cycle'
            AND form_target.fk_cadt = '$cadt' AND form_target.target>0
            GROUP BY
                form_target.fk_form
            ORDER BY
                lib_form.id ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function checklist_formRow_ipcdd($cadt, $cycle)
    {
        $mysql = $this->connectDatabase();
        $psgc_mun = $mysql->escape_string($cadt);
        $cycle = $mysql->escape_string($cycle);
        $q = "SELECT
                lib_activity.activity_name,
                lib_form.form_name,
                form_target.fk_form,
                lib_form.form_type,
                form_uploaded.is_reviewed,
                form_uploaded.with_findings,
                form_target.can_upload,
               Sum(IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.with_findings = 'no findings'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )) AS actual,
                    lib_form.form_type
            FROM
                form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
            INNER JOIN lib_category ON lib_activity.fk_category = lib_category.id
            LEFT JOIN form_uploaded ON form_uploaded.fk_ft_guid = form_target.ft_guid
            WHERE
                form_target.fk_cycle = '$cycle'
            AND form_target.fk_cadt = '$cadt' and (form_uploaded.is_deleted=0 OR form_uploaded.is_deleted is null)
            GROUP BY
                form_target.fk_form
            ORDER BY
                lib_form.id ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function get_target($city, $cycle, $fk_form)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                SUM(form_target.target) as target
                FROM
                form_target
                WHERE form_target.fk_form='$fk_form' AND (form_target.fk_psgc_mun='$city' or form_target.fk_cadt='$city') 
                  and form_target.fk_cycle='$cycle'";
        $result = $mysql->query($q);
        $row = $result->fetch_assoc();
        return $row['target'];
    }

    public function checklist_colspan_ipcdd($cadt, $cycle)
    {
        $mysql = $this->connectDatabase();
        $psgc_mun = $mysql->escape_string($cadt);
        $cycle = $mysql->escape_string($cycle);
        $q = "SELECT
            COUNT(implementing_cadt_icc.fk_cadt_id) as colspan
            FROM
            implementing_cadt_icc
            WHERE implementing_cadt_icc.fk_cycles='$cycle' AND implementing_cadt_icc.`level`='municipal' AND implementing_cadt_icc.fk_cadt_id='$cadt'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['colspan'];
        } else {
            return false;
        }
    }

    public function checklist_brgyRow($psgc_mun, $cycle, $cdd_form)
    {
        $mysql = $this->connectDatabase();
        $psgc_mun = $mysql->escape_string($psgc_mun);
        $cycle = $mysql->escape_string($cycle);
        $q = "SELECT
                    form_target.fk_form,
                    form_target.fk_psgc_mun,
                    form_target.fk_cycle,
                    form_target.target,
                    SUM(
                    IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.with_findings = 'no findings'
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )
                ) AS actual,
                    form_target.ft_guid,
                    form_target.fk_psgc_brgy,
                    form_uploaded.fk_ft_guid,
                    lib_form.form_type,
                    form_target.can_upload,
                    form_uploaded.with_findings,
                    form_uploaded.is_reviewed,
                    form_uploaded.is_findings_complied
                FROM
                    form_target
                LEFT JOIN form_uploaded ON form_uploaded.fk_ft_guid = form_target.ft_guid
                INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
                WHERE
                    form_target.fk_cycle = '$cycle'
                AND form_target.fk_psgc_mun = '$psgc_mun'
                AND form_target.fk_form = '$cdd_form' and (form_uploaded.is_deleted=0 OR form_uploaded.is_deleted is null)
                GROUP BY form_target.ft_guid
                ORDER BY
                    form_target.fk_psgc_brgy";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function checklist_brgyRowUploading($psgc_mun, $cycle, $cdd_form)
    {
        $mysql = $this->connectDatabase();
        $psgc_mun = $mysql->escape_string($psgc_mun);
        $cycle = $mysql->escape_string($cycle);
        $q = "SELECT
                    form_target.fk_form,
                    form_target.fk_psgc_mun,
                    form_target.fk_cycle,
                    form_target.target,
                    form_target.actual,
                    form_target.ft_guid,
                    form_target.fk_psgc_brgy,
                    form_uploaded.fk_ft_guid,
                    lib_form.form_type,
                    form_target.can_upload,
                    form_uploaded.with_findings,
                    form_uploaded.is_reviewed,
                    form_uploaded.is_findings_complied
                FROM
                    form_target
                LEFT JOIN form_uploaded ON form_uploaded.fk_ft_guid = form_target.ft_guid
                INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
                WHERE
                    form_target.fk_cycle = '$cycle'
                AND form_target.fk_psgc_mun = '$psgc_mun'
                AND form_target.fk_form = '$cdd_form' 
                GROUP BY form_target.ft_guid
                ORDER BY
                    form_target.fk_psgc_brgy ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function checklist_brgyRow_ipcddUploading($cadt, $cycle, $cdd_form, $form_type)
    {
        $mysql = $this->connectDatabase();
        $psgc_mun = $mysql->escape_string($cadt);
        $cycle = $mysql->escape_string($cycle);
        $q = "SELECT
            form_target.fk_form,
            form_target.fk_psgc_mun,
            form_target.fk_cycle,
            form_target.target,
            form_target.actual,
            form_target.ft_guid,
            form_target.fk_psgc_brgy,
            lib_form.form_type,
            form_target.can_upload,
            lib_municipality.mun_name,
            lib_barangay.brgy_name,
            lib_sitio.sitio_name
            FROM
            form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            LEFT JOIN lib_sitio ON lib_sitio.psgc_sitio = form_target.fk_psgc_sitio
            WHERE form_target.fk_cycle = '$cycle' AND form_target.fk_cadt = '$psgc_mun'
            AND form_target.fk_form = '$cdd_form'
            AND lib_form.form_type IN $form_type
            GROUP BY form_target.ft_guid
            ORDER BY lib_municipality.mun_name, lib_barangay.brgy_name,lib_sitio.sitio_name";
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

    public function checklist_brgyRow_ipcdd($cadt, $cycle, $cdd_form, $form_type)
    {
        $mysql = $this->connectDatabase();
        $psgc_mun = $mysql->escape_string($cadt);
        $cycle = $mysql->escape_string($cycle);
        $q = "SELECT
            form_target.fk_form,
            form_target.fk_psgc_mun,
            form_target.fk_cycle,
            form_target.target,
            Sum(IF (
                                    form_uploaded.is_reviewed = 'reviewed'
                                    AND form_uploaded.with_findings = 'no findings'
                                    AND form_uploaded.is_deleted = 0,
                                    1,
                                    0
                                )) AS actual,
            form_target.ft_guid,
            form_target.fk_psgc_brgy,
            form_uploaded.fk_ft_guid,
            lib_form.form_type,
            form_target.can_upload,
            lib_municipality.mun_name,
            lib_barangay.brgy_name,
            lib_sitio.sitio_name,
            form_uploaded.with_findings,
            form_uploaded.is_reviewed,
            form_uploaded.is_findings_complied
            FROM
            form_target
            LEFT JOIN form_uploaded ON form_uploaded.fk_ft_guid = form_target.ft_guid
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
            LEFT JOIN lib_sitio ON lib_sitio.psgc_sitio = form_target.fk_psgc_sitio
            WHERE form_target.fk_cycle = '$cycle' AND form_target.fk_cadt = '$psgc_mun'
            AND form_target.fk_form = '$cdd_form'
            AND lib_form.form_type IN $form_type and (form_uploaded.is_deleted=0 OR form_uploaded.is_deleted is null)
            GROUP BY form_target.ft_guid
            ORDER BY lib_municipality.mun_name, lib_barangay.brgy_name,lib_sitio.sitio_name";
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

    public function report_ipcdd_implementation()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            cycles.id,
            lib_modality.id as modality_id,
            lib_cadt.cadt_name
            FROM
            implementing_cadt_ipcdd
            INNER JOIN lib_cadt ON lib_cadt.id = implementing_cadt_ipcdd.fk_cadt
            INNER JOIN cycles ON cycles.id = implementing_cadt_ipcdd.fk_cycles
            INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
            INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $results = array();
            while ($row = $result->fetch_assoc()) {
                $data = $this->highChart_cityProgress($row['cadt_name'], '', $row['id'], $row['modality_id']);
                array_push($results, $data);
            }
            return json_encode($results, JSON_NUMERIC_CHECK);
        } else {
            return false;
        }
    }

    public function report_ncddp_implementation()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            cycles.fk_modality,
            cycles.id,
            lib_municipality.mun_name,
            lib_municipality.psgc_mun
            FROM
            implementing_muni_ncddp
            INNER JOIN cycles ON cycles.id = implementing_muni_ncddp.fk_cycles
            INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
            INNER JOIN lib_municipality ON lib_municipality.psgc_mun = implementing_muni_ncddp.fk_psgc_mun
            INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $results = array();
            while ($row = $result->fetch_assoc()) {
                $data = $this->highChart_cityProgress('', $row['psgc_mun'], $row['id'], $row['fk_modality']);
                array_push($results, $data);
            }
            return json_encode($results, JSON_NUMERIC_CHECK);
        } else {
            return false;
        }
    }

    public function highChart_cityProgress($cadt_name, $city_id, $cycle_id, $modality)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                SUM(
                    IF (
                        form_uploaded.is_reviewed = 'reviewed'
                        AND form_uploaded.with_findings = 'no findings'
                        AND (
                            form_uploaded.is_findings_complied = 'complied'
                            OR form_uploaded.is_findings_complied IS NULL
                            OR form_uploaded.is_complied = 'complied'
                            OR form_uploaded.is_complied IS NULL
                        )
                        AND form_uploaded.is_deleted = 0,
                        1,
                        0
                    )
                )/SUM(form_target.target)*100 AS accomplished,
            lib_cadt.cadt_name,
            lib_municipality.mun_name
            FROM
            form_target
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            LEFT JOIN form_uploaded ON form_target.ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN lib_category ON lib_category.id = lib_activity.fk_category
            LEFT JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            WHERE
                cycles.fk_modality = '$modality'
            AND (
                lib_cadt.cadt_name='$cadt_name'
                OR form_target.fk_psgc_mun = '$city_id'
            )
            AND form_target.fk_cycle = '$cycle_id'
            GROUP BY
                lib_category.id
            ORDER BY
                lib_category.id ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $data = array();

            while ($row = $result->fetch_assoc()) {
                $name = (isset($row['cadt_name'])) ? $row['cadt_name'] : $row['mun_name'];
                $data['name'] = $name;
                $data['data'][] = number_format($row['accomplished'], 2);
            }
            return $data;
        } else {
            return false;
        }
    }

    public function uploadingStat($modality)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            Sum(form_target.actual)/Sum(form_target.target)*100 as uploading_stat
            FROM
            form_target
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
            WHERE fk_modality='$modality'";
        $result = $mysql->query($q);
        $row = $result->fetch_assoc();
        if ($result->num_rows > 0) {
            return $row['uploading_stat'];
        } else {
            return false;
        }
    }

    public function getForms($city, $cycle, $cadt, $activity)
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
            INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
            WHERE (form_target.fk_psgc_mun = ? OR form_target.fk_cadt = ?) AND form_target.fk_cycle = ? and lib_activity.id = ?
            ORDER BY lib_form.form_name,lib_barangay.brgy_name ASC");
        $q->bind_param('iiii', $city, $cadt, $cycle, $activity) or $q->error;
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
    public function api_allFiles(){
        $mysql = $this->connectDatabase();
        $q="SELECT
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

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        $json_data = array("mrms_files"=>$data);
        echo json_encode($json_data,JSON_PRETTY_PRINT);
    }

}
