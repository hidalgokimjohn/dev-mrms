<?php

namespace app;

class App
{
    public $city_name;
    public $cadt_name;
    public $uploading_stat;
    public $notif_for_review;
    public $notif_for_compliance;
    public $notif_for_findings;
    public $cycle_name;
    public $cycle_id;
    public $mode;
    public $psgc_mun;
    public $cadt_id;

    public function connectDatabase()
    {
        $database = Database::getInstance();
        return $database->getConnection();
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
/*

        var_dump('abotshit');
        die();*/
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
        session_destroy();
        header('location: ../');
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

            case 'monitoring';
                $title = '';
                if (isset($_GET['p'])) {
                    $title = "MRMS | Monitoring";
                }
                return $title;
            case 'account';
                $title = '';
                if (isset($_GET['p'])) {
                    $title = "MRMS | Account";
                }
                return $title;
            case 'compliance';
                $title = '';
                if (isset($_GET['p'])) {
                    $title = "MRMS | Compliance";
                }
                return $title;
            case 'uploaded_mov';
                $title = '';
                if (isset($_GET['p'])) {
                    $title = "MRMS | Search MOV";
                }
                return $title;
            case 'checklist';
                $title = '';
                if (isset($_GET['p'])) {
                    $title = "MRMS | Checklist";
                }
                return $title;
            case 'libraries';
                $title = '';
                if (isset($_GET['p'])) {
                    $title = "MRMS | Libraries";
                }
            case 'search_mov_ipcdd';
                $title = '';
                if (isset($_GET['p'])) {
                    $title = "MRMS | Search IPCDD files";
                }
                return $title;
            case 'search_mov_ncddp';
                $title = '';
                if (isset($_GET['p'])) {
                    $title = "MRMS | Search NCDDP Files";
                }
                return $title;
            case 'all_reviewed';
                $title = '';
                if (isset($_GET['p'])) {
                    $title = "MRMS | All Reviewed";
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

    public function breadcrumbs()
    {
        $b = '<li class="breadcrumb-item"><a href="index.php">Home</a></li>';
        if (isset($_GET['mod']) && isset($_GET['view']) && $_GET['mod'] == 'for_review' && $_GET['view'] == 'activity') {
            if (isset($_GET['cadt_id'])) {
                $this->cadt_name($_GET['cadt_id']);
            }
            if (isset($_GET['psgc_mun'])) {
                $this->city_name($_GET['psgc_mun']);
            }
            $b .= '<li class="breadcrumb-item"><a href="index.php?f=joint_dqa&mod=for_review">For review</a></li>';
            $b .= '<li class="breadcrumb-item"><a href="#" class="text-capitalize">' . strtolower($this->city_name) . '</a></li>';
        } elseif (isset($_GET['mod']) && $_GET['mod'] == 'for_review') {
            $b .= '<li class="breadcrumb-item"><a href="index.php?f=joint_dqa&mod=for_review">For review</a></li>';
        } elseif (isset($_GET['p']) && $_GET['p'] == 'dashboard') {
            $b .= '<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>';
        } elseif (isset($_GET['p']) && $_GET['p'] == 'monitoring') {
            $b .= '<li class="breadcrumb-item"><a href="index.php">Monitoring</a></li>';
        } elseif (isset($_GET['p']) && $_GET['p'] == 'review') {
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=review&dqa_id=' . $_GET['dqa_id'] . '">Review</a></li>';
        } elseif (isset($_GET['p']) && $_GET['p'] == 'account') {
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=account">Account</a></li>';
        } elseif (isset($_GET['p']) && $_GET['p'] == 'compliance') {
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=account">Compliance</a></li>';
        } elseif (isset($_GET['p']) && $_GET['p'] == 'upload') {
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=upload">Upload</a></li>';
        } elseif (isset($_GET['p']) && $_GET['p'] == 'checklist') {
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=checklist">Checklist</a></li>';
        } elseif (isset($_GET['p']) && $_GET['p'] == 'findings') {
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=findings">Findings</a></li>';
        } elseif (isset($_GET['p']) && $_GET['p'] == 'search_mov_ncddp') {
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=search_mov_ncddp">Search</a></li>';
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=search_mov_ncddp">NCDPP</a></li>';
        }elseif (isset($_GET['p']) && $_GET['p'] == 'search_mov_ipcdd') {
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=search_mov_ncddp">Search</a></li>';
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=search_mov_ipcdd">IPCDD</a></li>';
        }elseif (isset($_GET['p']) && $_GET['p'] == 'all_reviewed') {
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=search_mov_ipcdd">Review</a></li>';
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=search_mov_ipcdd">All</a></li>';
        } elseif (isset($_GET['p']) && $_GET['p'] == 'libraries') {
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=libraries">Libraries</a></li>';
                if (isset($_GET['m']) && $_GET['m'] == 'checklist') {
                    if(isset($_GET['psgc'])){
                        $area_id = $_GET['psgc'];
                    }
                    if(isset($_GET['cadt'])){
                        $area_id = $_GET['cadt'];

                    }
            $b .= '<li class="breadcrumb-item"><a href="index.php?p=libraries&m=checklist&psgc='.$area_id.'&cycle='.$_GET['cycle'].'">Target</a></li>';
        }
        }

        return $b;

    }

    public function cycle_name($cycle_id)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				cycles.id,
				lib_cycle.cycle_name,
				cycles.`year`,
                cycles.batch
				FROM
				cycles
				INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
				WHERE cycles.id=?");
        $q->bind_param('i', $cycle_id);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->cycle_name = $row['cycle_name'];
            return $row;
        } else {
            return false;
        }
    }

    public function city_name($psgc_mun)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				lib_municipality.mun_name
				FROM
				lib_municipality
				WHERE lib_municipality.psgc_mun = ? ");
        $q->bind_param('i', $psgc_mun);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->city_name = $row['mun_name'];
            return $row;
        } else {

        }
    }

    public function cadt_name($cadt_id)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
                lib_cadt.cadt_name
                FROM
                lib_cadt
                WHERE id=?");
        $q->bind_param('i', $cadt_id);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->city_name = $row['cadt_name'];
            return $row;
        } else {
            return false;
        }
    }

    public function page_footer()
    {
        echo '<div class="footer fixed"><div class="pull-right"></div><div>
        &copy; ' . date("Y ") . ' DSWD CARAGA Kalahi-CIDSS | Monitoring & Evaluation Unit.</div></div>';
    }

    public function notif_for_review()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            COUNT(tbl_dqa.conducted_by) as notif_for_review
            FROM
            form_uploaded
            INNER JOIN tbl_dqa_findings ON tbl_dqa_findings.fk_ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN tbl_dqa ON tbl_dqa.dqa_guid = tbl_dqa_findings.fk_dqa_guid
            WHERE tbl_dqa.conducted_by='$_SESSION[username]' AND form_uploaded.is_reviewed='pending'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            $this->notif_for_review = $row['notif_for_review'];
        } else {
            return false;
        }
    }

    public function notif_for_compliance()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            COUNT(DISTINCT form_uploaded.file_id) as notif_for_compliance
            FROM
            form_uploaded
            INNER JOIN tbl_dqa_findings ON tbl_dqa_findings.fk_ft_guid = form_uploaded.fk_ft_guid
            INNER JOIN tbl_dqa ON tbl_dqa.dqa_guid = tbl_dqa_findings.fk_dqa_guid
            WHERE tbl_dqa.conducted_by='$_SESSION[username]' AND form_uploaded.is_reviewed='for review' AND form_uploaded.is_compliance='compliance' AND form_uploaded.is_deleted=0";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            $this->notif_for_compliance = $row['notif_for_compliance'];
        } else {
            return false;
        }
    }

    public function notif_for_findings()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                    COUNT(DISTINCT form_uploaded.file_id) AS notif_count,
                    tbl_dqa_findings.responsible_person
                FROM
                    form_uploaded
                INNER JOIN tbl_dqa_findings ON tbl_dqa_findings.fk_ft_guid = form_uploaded.fk_ft_guid
                WHERE
                    tbl_dqa_findings.responsible_person = '$_SESSION[username]'
                AND form_uploaded.with_findings = 'with findings'
                AND form_uploaded.is_findings_complied is NULL AND form_uploaded.is_reviewed='reviewed'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            $this->notif_for_findings = $row['notif_count'];
        } else {
            return false;
        }
    }

    public function update_profile_pic()
    {
        $date = new \DateTime();
        $fileName = basename($_FILES['fileToUpload']['name']);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $uniqueFileName = uniqid($date->getTimestamp(), false) . "." . $extension;
        $target_dir = "../../../../Storage/image/profile_pictures/";
        $target_file = '/dev.mrms/Storage/image/profile_pictures/' . $uniqueFileName;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $mysql = $this->connectDatabase();

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 10000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Sorry, only JPG, JPEG, PNG files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], '../../../../Storage/image/profile_pictures/' . $uniqueFileName)) {
                $this->generateThumbs($target_dir, $target_dir . 'thumbnails/', 200, $uniqueFileName);
                $q = "UPDATE `personal_info` SET `pic_url`='$uniqueFileName' WHERE (`fk_username`='$_SESSION[username]') LIMIT 1";
                $mysql->query($q) or die($mysql->error);
                echo 'uploaded';
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    public function generateThumbs($pathToScreens, $pathToThumbs, $thumbWidth = 200, $file_name)
    {
        $dir = opendir($pathToScreens) or die("Could not open directory");
        // Remove folders.
        $valid_extensions = ["jpg", "jpeg", "png"]; // Only jpeg images allowed.
        $info = pathinfo($pathToScreens . $file_name); // Get info on the screenshot
        if (in_array(strtolower($info["extension"]), $valid_extensions)) {
            // Make sure the file is an image file by checking its extension to the array of image extensions.
            $img = imagecreatefromjpeg($pathToScreens . $file_name); // Select the file as an image from the directory.
            $width = imagesx($img);
            $height = imagesy($img);
            // Collect its width and height.
            $newHeight = floor($height * ($thumbWidth / $width)); // Calculate new height for thumbnail.
            $tempImage = imagecreatetruecolor($thumbWidth, $newHeight); // Create a temporary image of the thumbnail.
            // Copy and resize old image into new image.
            imagecopyresized($tempImage, $img, 0, 0, 0, 0, $thumbWidth, $newHeight, $width, $height);

            $genThumb = imagejpeg($tempImage, $pathToThumbs . $file_name);
            // Create the thumbnail with the new width and height in the thumbnails directory.
            // I added a rand 3
        }
        closedir($dir); // Close the directory.
    }

    public function act_ncddp()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                user_city_coverage.fk_username,
                user_city_coverage.fk_psgc_mun,
                user_city_coverage.is_checked,
                implementing_muni_ncddp.fk_cycles,
                lib_cycle.cycle_name,
                lib_modality.modality_name,
                cycles.`year`,
                cycles.`status`,
                lib_municipality.mun_name
                FROM
                user_city_coverage
                INNER JOIN implementing_muni_ncddp ON user_city_coverage.fk_psgc_mun = implementing_muni_ncddp.fk_psgc_mun
                INNER JOIN cycles ON implementing_muni_ncddp.fk_cycles = cycles.id
                INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
                INNER JOIN lib_modality ON cycles.fk_modality = lib_modality.id
                INNER JOIN lib_municipality ON user_city_coverage.fk_psgc_mun = lib_municipality.psgc_mun
                WHERE user_city_coverage.fk_username='$_SESSION[username]' AND implementing_muni_ncddp.status='open'
                LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        if ($result->num_rows > 0) {
            $this->city_name = $row['mun_name'];
            $this->cycle_name = $row['cycle_name'];
            $this->mode = $row['modality_name'];
            $this->psgc_mun = $row['fk_psgc_mun'];
            $this->cycle_id = $row['fk_cycles'];
        }

    }

    public function act_ipcdd()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            lib_modality.modality_name,
            lib_cadt.cadt_name,
            lib_cycle.cycle_name,
            implementing_cadt_ipcdd.fk_cadt,
            implementing_cadt_ipcdd.fk_cycles,
            implementing_cadt_ipcdd.`status`,
            user_cadt_coverage.fk_username,
            cycles.batch,
            users.fk_position
            FROM
            user_cadt_coverage
            INNER JOIN implementing_cadt_ipcdd ON user_cadt_coverage.fk_cadt = implementing_cadt_ipcdd.fk_cadt
            INNER JOIN cycles ON implementing_cadt_ipcdd.fk_cycles = cycles.id
            INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
            INNER JOIN lib_modality ON cycles.fk_modality = lib_modality.id
            INNER JOIN lib_cadt ON lib_cadt.id = implementing_cadt_ipcdd.fk_cadt
            INNER JOIN users ON user_cadt_coverage.fk_username = users.username
            INNER JOIN lib_user_positions ON lib_user_positions.id = users.fk_position
            WHERE lib_user_positions.id IN (4,5,10) AND user_cadt_coverage.fk_username='$_SESSION[username]' LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        if ($result->num_rows > 0) {
            $this->cadt_name = $row['cadt_name'];
            $this->cycle_name = $row['cycle_name'];
            $this->mode = $row['modality_name'];
            $this->cadt_id = $row['fk_cadt'];
            $this->cycle_id = $row['fk_cycles'];
        }

    }

    public function team_ncddp($psgc_mun)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            personal_info.pic_url,
            CONCAT(personal_info.first_name,' ',personal_info.last_name) as full_name,
            personal_info.fk_username,
            user_city_coverage.fk_psgc_mun
            FROM
            user_city_coverage
            INNER JOIN personal_info ON user_city_coverage.fk_username = personal_info.fk_username
            INNER JOIN users ON users.username = user_city_coverage.fk_username
            WHERE user_city_coverage.fk_psgc_mun='$psgc_mun' AND user_city_coverage.is_checked='yes' AND users.fk_position IN (4,5,10)
            ";
        $result = $mysql->query($q) or die($mysql->error);
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function team_ipcdd($cadt_id)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            personal_info.pic_url,
            CONCAT(personal_info.first_name,' ',personal_info.last_name) as full_name,
            personal_info.fk_username,
            user_cadt_coverage.fk_cadt
            FROM
            user_cadt_coverage
            INNER JOIN personal_info ON user_cadt_coverage.fk_username = personal_info.fk_username
            INNER JOIN users ON users.username = user_cadt_coverage.fk_username
            WHERE user_cadt_coverage.fk_cadt='$cadt_id' AND user_cadt_coverage.is_checked='yes' AND users.fk_position IN (4,5,10)
            ";
        $result = $mysql->query($q) or die($mysql->error);
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function uploading_stat($psgc_mun, $cycle)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                FORMAT(SUM(form_target.actual)/SUM(form_target.target) *100,1) AS overall
            FROM
            form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            WHERE
             form_target.fk_cycle = '$cycle' and form_target.fk_psgc_mun='$psgc_mun' and form_target.target>0";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        $this->uploading_stat = $row['overall'];
    }

    public function uploadingStat_ipcdd($cadt_id, $cycle)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                    FORMAT(SUM(form_target.actual)/SUM(form_target.target)*100,1) AS overall
                FROM
                form_target
                INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
                left JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
                WHERE form_target.fk_cycle ='$cycle' and form_target.fk_cadt='$cadt_id'";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        $this->uploading_stat = $row['overall'];

    }

    public function uploading_per_activity_ncddp($psgc_mun, $cycle)
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
                FORMAT(SUM(form_target.actual)/SUM(form_target.target)* 100, 1) as ovataerall,
                FORMAT(SUM(form_target.reviewed)/SUM(form_target.target)* 100, 1) as reviewed,
                FORMAT(SUM(form_target.actual_nofindings)/SUM(form_target.target)* 100, 1) as dqa
            FROM
            form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            LEFT JOIN lib_municipality ON lib_municipality.psgc_mun = form_target.fk_psgc_mun
            WHERE
             form_target.fk_cycle = '$cycle' AND form_target.target>0 AND form_target.fk_psgc_mun='$psgc_mun'";

        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function uploading_per_activity_ipcdd($cadt_id, $cycle)
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
                             form_target.fk_cycle='$cycle' and form_target.fk_cadt='$cadt_id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function modality()
    {
        $mysql = $this->connectDatabase();
        $params = $_REQUEST;
        $columns = array(0 => 'lib_modality.id', 1 => 'lib_modality.id', 2 => 'lib_modality.modality_name');
        $where_con = $sqlTot = $sqlRec = "";
        if (empty($params['search']['value'])) {
            $where_con .= "WHERE lib_modality.id LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_modality.modality_name LIKE '%" . $params['search']['value'] . "%'";
        }
        $q = "SELECT
            lib_modality.id,
            lib_modality.modality_name
            FROM
            lib_modality ";
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

    public function getModalities()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            lib_modality.id,
            lib_modality.modality_name,
            lib_modality.is_deleted,
            lib_modality.modality_group
            FROM
            lib_modality";
        $result = $mysql->query($q) or die($mysql->error);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getCycles()
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->real_escape_string($_POST['modality_id']);
        $q = "SELECT
            lib_cycle.cycle_name,
            lib_modality.modality_name,
            cycles.batch,
            cycles.`year`,
            cycles.id
            FROM
            cycles
            INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
            INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
            WHERE cycles.`year`='2020' AND cycles.fk_modality='$id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getMuni($id,$modalityGroup)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->real_escape_string($id);
        $group = $mysql->real_escape_string($modalityGroup);
        if($group=='ipcdd'){
            $q="SELECT
                lib_cadt.id,
                lib_cadt.cadt_name
                FROM
                implementing_cadt_ipcdd
                INNER JOIN lib_cadt ON lib_cadt.id = implementing_cadt_ipcdd.fk_cadt
                where implementing_cadt_ipcdd.fk_cycles='$id'";
        }
        if($group=='ncddp'){
            $q = "SELECT
            lower(lib_municipality.mun_name) as mun_name,
            lib_cycle.cycle_name,
            implementing_muni_ncddp.fk_psgc_mun,
            lower(lib_province.prov_name) as prov_name,
            cycles.id,
            lib_municipality.psgc_mun
            FROM
            implementing_muni_ncddp
            INNER JOIN lib_municipality ON lib_municipality.psgc_mun = implementing_muni_ncddp.fk_psgc_mun
            INNER JOIN cycles ON implementing_muni_ncddp.fk_cycles = cycles.id
            INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
            INNER JOIN lib_province ON lib_province.psgc_province = lib_municipality.psgc_province
            where cycles.id='$id'
            ORDER BY lib_municipality.mun_name";
        }
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getBrgy($psgc_mun){
        $mysql = $this->connectDatabase();
        $id = $mysql->real_escape_string($psgc_mun);
        $q = "SELECT
                lib_barangay.psgc_brgy,
                lib_barangay.brgy_name,
                lib_barangay.psgc_mun
                FROM
                lib_barangay
                WHERE psgc_mun='$psgc_mun' ORDER BY lib_barangay.brgy_name ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getActivity($cycle,$psgc_mun){
        $mysql = $this->connectDatabase();
        $id = $mysql->real_escape_string($psgc_mun);
        $q = "SELECT
            lib_activity.id,
            lib_activity.activity_name
            FROM
            form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            WHERE form_target.fk_cycle='$cycle' AND (form_target.fk_cadt='' OR form_target.fk_psgc_mun='$psgc_mun')
            GROUP BY lib_activity.id
            ORDER BY lib_activity.id ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getForms($cycle,$psgc_mun,$id){
        $mysql = $this->connectDatabase();
        $q = "SELECT
            lib_form.form_code,
            lib_form.form_name
            FROM
            form_target
            INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
            INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
            WHERE form_target.fk_cycle='$cycle' AND lib_activity.id='$id' AND (form_target.fk_cadt='' OR form_target.fk_psgc_mun='$psgc_mun')
            GROUP BY lib_form.form_code
            ORDER BY lib_form.form_code ASC";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function edit_modality($id, $val)
    {
        echo $id . ' ' . $val;
        $mysql = $this->connectDatabase();
        $q = "UPDATE `lib_modality` SET `modality_name`='$val' WHERE (`id`='$id') LIMIT 1";
        $result = $mysql->query($q) or die ($mysql->error);
        if ($mysql->affected_rows > 0) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    public function edit_target($id, $val)
    {
        //echo $id . ' ' . $val;
        $mysql = $this->connectDatabase();
        $val = $mysql->real_escape_string($val);
        $reason = $mysql->real_escape_string($_POST['reason']);
        $q = "SELECT
                form_target.ft_guid,
                form_target.target,
                form_target.actual
                FROM
                form_target
                WHERE ft_guid='$id'";
        $result = $mysql->query($q) or die ($mysql->error);
        if ($mysql->affected_rows > 0) {
            $row = $result->fetch_assoc();
            if($val>=$row['actual']){
                $q="UPDATE `form_target` SET `target`='$val' WHERE (`ft_guid`='$id') LIMIT 1";
                $mysql->query($q);
                $q_reason = "INSERT INTO `tbl_adjustment_reason` (`fk_ft`, `reason`, `added_by`,`date_created`) VALUES ('$id', '$reason', '$_SESSION[username]',NOW())";
                $mysql->query($q_reason);
                echo 'saved';
            }else{
                echo 'false';
            }
        } else {
            echo 'false';
        }
    }

    public function create_modality($val)
    {
        $mysql = $this->connectDatabase();
        $val = $mysql->real_escape_string($val);
        $q = "INSERT INTO `lib_modality` (`modality_name`,`is_deleted`) VALUES ('$val',0)";
        $result = $mysql->query($q) or die ($mysql->error);
        if ($mysql->affected_rows > 0) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    public function update_actual()
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
            WHERE  form_uploaded.is_deleted=0 AND (form_uploaded.is_compliance is null)
            GROUP BY fk_ft_guid";
        $result = $mysql->query($q);
        while ($row = $result->fetch_assoc()) {
            $update = "UPDATE `form_target` SET `actual`='$row[uploaded]' WHERE (`ft_guid`='$row[ft_guid]')";
            $mysql->query($update);
            echo $row['actual'] . ' -> ' . $row['uploaded'] . '<br/>';
        }
    }

    public function update_target()
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
            WHERE form_uploaded.is_deleted=0 AND (form_uploaded.is_compliance is null)
            GROUP BY fk_ft_guid";
        $result = $mysql->query($q);
        while ($row = $result->fetch_assoc()) {
            $update = "UPDATE `form_target` SET `target`='$row[uploaded]' WHERE (`ft_guid`='$row[ft_guid]')";
            $mysql->query($update);
            echo $row['target'] . ' -> ' . $row['uploaded'] . '<br/>';
        }
    }

    public function update_actualNofindings()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                COUNT(form_uploaded.file_id) AS count,
                form_uploaded.fk_ft_guid
            FROM
                form_uploaded
            WHERE
                is_deleted = 0
            AND with_findings = 'no findings' AND is_deleted=0
            GROUP BY
                fk_ft_guid";
        $result = $mysql->query($q);
        while ($row = $result->fetch_assoc()) {
            $update = "UPDATE `form_target` SET `actual_nofindings`='$row[count]' WHERE (`ft_guid`='$row[fk_ft_guid]')";
            $mysql->query($update);
            echo $row['fk_ft_guid'] . ' -> ' . $row['count'] . '<br/>';
        }
    }

    public function update_reviewed()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                COUNT(form_uploaded.file_id) AS count,
                form_uploaded.fk_ft_guid
            FROM
                form_uploaded
            WHERE
                is_deleted = 0
            AND is_reviewed = 'reviewed' AND is_deleted=0
            GROUP BY
                fk_ft_guid";
        $result = $mysql->query($q);
        while ($row = $result->fetch_assoc()) {
            $update = "UPDATE `form_target` SET `reviewed`='$row[count]' WHERE (`ft_guid`='$row[fk_ft_guid]')";
            $mysql->query($update);
            echo $row['fk_ft_guid'] . ' -> ' . $row['count'] . '<br/>';
        }
    }

    public function getTarget($cycle,$psgc){
        $mysql = $this->connectDatabase();
        $params = $_REQUEST;
        $columns = array(0 => 'action', 1 => 'lib_activity.activity_name',2=>'lib_form.form_name',3=>'lib_barangay.brgy_name',4=>'form_target.target',5=>'form_target.actual');
        $where_con = $sqlTot = $sqlRec = "";
        if (!empty($params['search']['value'])) {
            $where_con .= "AND (lib_form.form_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_activity.activity_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_target.target LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR form_target.actual LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_barangay.brgy_name LIKE '%" . $params['search']['value'] . "%')";
        }
        $q = "SELECT
                lib_activity.activity_name,
                lib_form.form_name,
                lib_barangay.brgy_name,
                form_target.target,
                form_target.actual,
                form_target.actual_nofindings,
                form_target.reviewed,
                form_target.ft_guid
                FROM
                form_target
                INNER JOIN lib_form ON lib_form.form_code = form_target.fk_form
                INNER JOIN lib_activity ON lib_activity.id = lib_form.fk_activity
                LEFT JOIN lib_barangay ON lib_barangay.psgc_brgy = form_target.fk_psgc_brgy
                WHERE form_target.fk_cycle='$cycle' AND (form_target.fk_psgc_mun='$psgc' OR form_target.fk_cadt='$psgc') ";
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
}
