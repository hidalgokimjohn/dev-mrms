<?php

namespace app;

class City
{
    public function connectDatabase()
    {
        $database = Database::getInstance();
        return $database->getConnection();
    }

    public function getCadt(){
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
    public function getCities(){
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
    public function implementingCity()
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				implementing_muni_ncddp.fk_psgc_mun,
				implementing_muni_ncddp.fk_cycles,
				lib_modality.modality_name,
				lib_cycle.cycle_name,
				lib_municipality.mun_name,
				cycles.`year`,
				implementing_muni_ncddp.`status`,
       			lib_province.prov_name
				FROM
				implementing_muni_ncddp
				INNER JOIN cycles ON cycles.id = implementing_muni_ncddp.fk_cycles
				INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
				INNER JOIN lib_modality ON cycles.fk_modality = lib_modality.id
				INNER JOIN lib_municipality ON implementing_muni_ncddp.fk_psgc_mun = lib_municipality.psgc_mun
				INNER JOIN lib_province ON lib_province.psgc_province = lib_municipality.psgc_province
				WHERE implementing_muni_ncddp.`status`='open'
				ORDER BY lib_municipality.psgc_mun");
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

    public function enrolledCity()
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				implementing_muni_ncddp.fk_psgc_mun,
				implementing_muni_ncddp.fk_cycles,
				lib_modality.modality_name,
				lib_cycle.cycle_name,
				lib_municipality.mun_name,
				cycles.`year`,
				implementing_muni_ncddp.`status`
				FROM
				implementing_muni_ncddp
				INNER JOIN cycles ON cycles.id = implementing_muni_ncddp.fk_cycles
				INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
				INNER JOIN lib_modality ON cycles.fk_modality = lib_modality.id
				INNER JOIN lib_municipality ON implementing_muni_ncddp.fk_psgc_mun = lib_municipality.psgc_mun
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

    public function enrolled_cadt_ipcdd()
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
                lib_cadt.id as cadt_id,
                cycles.id as cycle_id,
                lib_cycle.cycle_name,
                cycles.batch,
                lib_cadt.cadt_name,
                cycles.`year`,
                implementing_cadt_ipcdd.`status`
                FROM
                implementing_cadt_ipcdd
                INNER JOIN cycles ON implementing_cadt_ipcdd.fk_cycles = cycles.id
                INNER JOIN lib_cadt ON implementing_cadt_ipcdd.fk_cadt = lib_cadt.id
                INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id");
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

    public function city_list($psgc_region)
    {

        $mysql = $this->connectDatabase();
        $psgc_region = $mysql->real_escape_string($psgc_region);
        $params = $_REQUEST;
        $columns = array(0 => 'lib_municipality.psgc_mun', 1 => 'lib_municipality.mun_name', 2 => 'lib_province.prov_name', 3 => 'lib_region.reg_name');
        $where_con = $sqlTot = $sqlRec = "";
        if (empty($params['columns']['search']['value'])) {
            $where_con .= " AND ( " . $columns[0] . " LIKE '%" . $params['columns'][0]['search']['value'] . "%'";
            $where_con .= " AND " . $columns[1] . " LIKE '%" . $params['columns'][1]['search']['value'] . "%'";
            $where_con .= " AND " . $columns[2] . " LIKE '%" . $params['columns'][2]['search']['value'] . "%'";
            $where_con .= " AND " . $columns[3] . " LIKE '%" . $params['columns'][3]['search']['value'] . "%')";
            $where_con .= " GROUP BY lib_municipality.psgc_mun ";
        } else {
            $where_con .= " GROUP BY lib_municipality.psgc_mun ";
        }
        $q = "SELECT
       			lib_municipality.psgc_mun,
				lib_municipality.mun_name,
				lib_province.prov_name,
				lib_region.reg_name

				FROM
				lib_municipality
				INNER JOIN lib_province ON lib_municipality.psgc_province = lib_province.psgc_province
				INNER JOIN lib_region ON lib_province.psgc_region = lib_region.psgc_region
				WHERE lib_region.psgc_region= '$psgc_region'";
        $sqlTot .= $q;
        $sqlRec .= $q;

        if (isset($where_con) && $where_con != '') {
            $sqlTot .= $where_con;
            $sqlRec .= $where_con;

        }

        $user = new \app\User();

        $sqlRec .= " ORDER BY " . $columns[$params['order'][0]['column']] . ' ' . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . ", " . $params['length'] . " ";
        $query_tot = $mysql->query($sqlTot) or die($mysql->error);
        $total_records = $query_tot->num_rows;
        $query_records = $mysql->query($sqlRec) or die($mysql->error);
        if ($query_records->num_rows > 0) {
            while ($row = $query_records->fetch_row()) {
                if (isset($_GET['user'])) {
                    if ($user->user_coverage($_GET['user'], $row[0])) {
                        $row[4] = $user->is_checked;
                    } else {
                        $row[4] = '';
                    }
                }

                $data[] = $row;
            }

        } else {
            $data = '';
        }
        $json_data = array("draw" => intval($params['draw']), "recordsTotal" => intval($total_records), "recordsFiltered" => intval($total_records), "data" => $data);
        echo json_encode($json_data);

    }

    public function act_coverage_city($username)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
       			user_city_coverage.fk_psgc_mun,
				user_city_coverage.fk_username,
				lib_municipality.mun_name,
				lib_province.prov_name
				FROM
				user_city_coverage
				INNER JOIN lib_municipality ON lib_municipality.psgc_mun = user_city_coverage.fk_psgc_mun
				INNER JOIN lib_province ON lib_municipality.psgc_province = lib_province.psgc_province
				WHERE user_city_coverage.fk_username=?");
        $q->bind_param('s', $username);
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

    public function act_coverage_ipcdd($username)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
                user_cadt_coverage.fk_cadt,
                lib_cadt.cadt_name,
                lib_cadt.id,
                user_cadt_coverage.fk_username
                FROM
                user_cadt_coverage
                INNER JOIN lib_cadt ON lib_cadt.id = user_cadt_coverage.fk_cadt
                WHERE user_cadt_coverage.fk_username=?");
        $q->bind_param('s', $username);
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

}
