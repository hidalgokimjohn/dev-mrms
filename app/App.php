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
            INNER JOIN lib_user_positions ON users.fk_position = lib_user_positions.id
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
                $_SESSION['user_fullname'] = $row['first_name']. ' '.$row['last_name'];
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
                if(isset($_GET['m']) && $_GET['m']=='mov_uploading_2021'){
                    $title .= " - MOV Uploading 2021 | MRMS";
                }
                if(isset($_GET['m']) && $_GET['m']=='mov_uploading_2020'){
                    $title .= " - MOV Uploading 2020 | MRMS";
                }
                if(isset($_GET['m']) && $_GET['m']=='mov_reviewed'){
                    $title .= " - MOV Reviewed | MRMS";
                }
                if(isset($_GET['m']) && $_GET['m']=='exec_db'){
                    $title .= " - Executive | MRMS";
                }
                return $title;
            default:
                echo 'MRMS | Home';
                break;
        }
    }

    public function sidebar_active($m,$url){
        echo ($m==$url)?'active':'';
    }
    public function sidebar_showList($m,$url){
        echo ($m==$url)?'show':'';
    }

    public function page_footer()
    {
        echo '<div class="footer fixed"><div class="pull-right"></div><div>
        &copy; ' . date("Y ") . ' DSWD CARAGA Kalahi-CIDSS | Monitoring & Evaluation Unit.</div></div>';
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
    public function getCycle($year,$modality)
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
        $q->bind_param('is',$year,$modality);
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
    public function name(){
        
    }
}
