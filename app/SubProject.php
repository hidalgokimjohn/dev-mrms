<?php


namespace app;
use DatabaseEngineering;


class SubProject
{
    public function connectDatabase_eng()
    {
        $database = DatabaseEngineering::getInstance();
        $mysql = $database->getConnection();
        return $mysql;
    }

    public function tbl_spStatus(){
        $mysql = $this->connectDatabase_eng();
        $q="SELECT
            sp.sp_title,
            Max(sp_logs.sp_logs_actual) AS percentage,
            sp.sp_status,
            sp_logs.sp_id,
            sp_batch.batch,
            sp_groupings.grouping,
            sp.sp_cycle,
            sp_cycle.cycle,
            sp_logs.updated_at,
            concat(users.Fname,' ',users.Lname) as updated_by,
            users.Fname,
            users.Lname,
            sp.sp_date_started,
            sp.sp_province,
            sp.sp_municipality,
            sp.sp_brgy
            FROM
            sp
            INNER JOIN sp_logs ON sp_logs.sp_id = sp.sp_id
            INNER JOIN sp_batch ON sp_batch.id = sp.sp_batch
            INNER JOIN sp_groupings ON sp_groupings.id = sp.sp_groupings
            INNER JOIN sp_cycle ON sp_cycle.id = sp.sp_cycle
            LEFT JOIN users ON users.id = sp_logs.sp_logs_last_user_update
            WHERE YEAR(sp.sp_date_started) IN (2019,2020) AND sp.sp_status='On-going'
            GROUP BY sp_logs.sp_id
            ORDER BY sp_logs.updated_at DESC";
        $result = $mysql->query($q) or die($mysql->error);
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            return $data;
        }else{
            return false;
        }
    }

    public function spStatus_count($sp_status){
        $mysql = $this->connectDatabase_eng();
        $q="SELECT
                sp.sp_status
            FROM
                sp
            INNER JOIN sp_logs ON sp_logs.sp_id = sp.sp_id
            INNER JOIN sp_batch ON sp_batch.id = sp.sp_batch
            INNER JOIN sp_groupings ON sp_groupings.id = sp.sp_groupings
            INNER JOIN sp_cycle ON sp_cycle.id = sp.sp_cycle
            LEFT JOIN users ON users.id = sp_logs.sp_logs_last_user_update
            WHERE
                YEAR (sp.sp_date_started) IN (2019, 2020)
            AND sp.sp_status = '$sp_status'
            GROUP BY
                sp_logs.sp_id
            ORDER BY
                sp_logs.updated_at DESC";
        $result = $mysql->query($q) or die($mysql->error);
        return $result->num_rows;
    }

    public function spStatus_by($sp_status)
    {
        $mysql = $this->connectDatabase_eng();
        $q = "SELECT
            COUNT(sp.sp_status) as sp_count
            FROM
            sp
            WHERE sp_status='$sp_status' and sp_implementation in (2020)";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        return $row['sp_count'];
    }

    public function spStatus_completed($year)
    {
        $mysql = $this->connectDatabase_eng();
        $q = "SELECT
            COUNT(sp.id) as completed
            FROM
            sp
            where sp_implementation in ($year) AND sp_status='Completed' AND sp_groupings in (3,4)";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        return $row['completed'];
    }

    public function spStatus_onGoing($year)
    {
        $mysql = $this->connectDatabase_eng();
        $q = "SELECT
            COUNT(sp.id) as completed
            FROM
            sp
            where sp_status='On-going'";
        $result = $mysql->query($q) or die($mysql->error);
        $row = $result->fetch_assoc();
        return $row['completed'];
    }

    public function sp_onGoing()
    {
        $mysql = $this->connectDatabase_eng();
        $q = "SELECT
            Count(sp.id) AS sp,
            sp_groupings.grouping,
            sp.sp_implementation
            FROM
            sp
            INNER JOIN sp_groupings ON sp_groupings.id = sp.sp_groupings
            WHERE sp.sp_implementation in ('2018','2019','2020') AND sp.sp_status='On-going'
            GROUP BY sp.sp_groupings,sp.sp_implementation
            ORDER BY sp.sp_implementation DESC";
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

    public function sp_Completed()
    {
        $mysql = $this->connectDatabase_eng();
        $q = "SELECT
                Count(sp.id) AS sp,
                sp_groupings.grouping,
                sp.sp_implementation,
                sp.sp_groupings,
                sp.sp_cycle
                FROM
                sp
                LEFT JOIN sp_groupings ON sp_groupings.id = sp.sp_groupings
                WHERE sp.sp_implementation in ('2018','2019','2020') AND sp.sp_cycle in (4,2) AND sp.sp_status='Completed'
                GROUP BY sp.sp_groupings,sp.sp_implementation";
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

    public function sp_nys()
    {
        $mysql = $this->connectDatabase_eng();
        $q = "SELECT
                Count(sp.id) AS sp,
                sp_groupings.grouping,
                sp.sp_implementation,
                sp.sp_groupings,
                sp.sp_cycle
                FROM
                sp
                LEFT JOIN sp_groupings ON sp_groupings.id = sp.sp_groupings
                WHERE sp.sp_implementation in ('2018','2019','2020') AND sp.sp_status='NYS'
                GROUP BY sp.sp_groupings,sp.sp_implementation";
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
}