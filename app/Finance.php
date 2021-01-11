<?php


namespace app;

use DatabaseFinance;
use DatabaseFinance2018;


class Finance
{
    public function connectDatabase_cfms()
    {
        $database = DatabaseFinance::getInstance();
        $mysql = $database->getConnection();
        return $mysql;
    }

    public function connectDatabase_cfms2018()
    {
        $database = DatabaseFinance2018::getInstance();
        $mysql = $database->getConnection();
        return $mysql;
    }

    //START NCDDP
    public function ncddp_totalRfrs()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as totalRfrs  FROM tbl_incoming_ncddp,tbl_ncddp_sp WHERE tbl_incoming_ncddp.sp_id = tbl_ncddp_sp.sp_id AND cancelled = 0";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['totalRfrs'];
        } else {
            return false;
        }
    }

    public function ncddp_RfrsatRPMO()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as RfrsatRPMO  FROM tbl_incoming_ncddp, tbl_ncddp_sp
                                    WHERE tbl_incoming_ncddp.sp_id = tbl_ncddp_sp.sp_id
                                    AND received_rpmo <> '0000-00-00 00:00:00' 
                                    AND (incoming_date = '0000-00-00 00:00:00' 
                                        AND obligation_date = '0000-00-00 00:00:00' AND cash_date = '0000-00-00 00:00:00')
                                        AND tbl_ncddp_sp.cancelled = 0";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsatRPMO'];
        } else {
            return false;
        }
    }

    public function ncddp_RfrsatABC()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as RfrsatABC FROM tbl_incoming_ncddp,tbl_ncddp_sp 
                                    WHERE tbl_incoming_ncddp.sp_id = tbl_ncddp_sp.sp_id
                                    and (incoming_date <> '0000-00-00' OR obligation_date <> '0000-00-00' OR cash_date <> '0000-00-00') 
                                    and check_number = ''
                                    and tbl_ncddp_sp.is_waived = 0";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsatABC'];
        } else {
            return false;
        }
    }

    public function ncddp_RfrsDownloaded()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as RfrsDownloaded FROM tbl_incoming_ncddp WHERE check_number <> ''";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsDownloaded'];
        } else {
            return false;
        }
    }

    public function ncddp_RfrsWaived()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as RfrsWaived FROM tbl_incoming_ncddp,tbl_ncddp_sp 
                                    WHERE tbl_incoming_ncddp.sp_id = tbl_ncddp_sp.sp_id AND is_waived = 1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsWaived'];
        } else {
            return false;
        }
    }

    public function ncddp_RfrsCancelled()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as RfrsCancelled FROM tbl_incoming_ncddp,tbl_ncddp_sp 
                                    WHERE tbl_incoming_ncddp.sp_id = tbl_ncddp_sp.sp_id AND cancelled = 1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsCancelled'];
        } else {
            return false;
        }
    }
    //END NCDDP

    //START PAMANA
    public function pamana_totalRfrs()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as totalRfrs FROM tbl_incoming_bub,tbl_sp_bub
                                      WHERE tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id AND cancelled = 0 AND modality = 'PAMANA'";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['totalRfrs'];
        } else {
            return false;
        }
    }

    public function pamana_RfrsatRPMO()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as RfrsatRPMO FROM tbl_incoming_bub,tbl_sp_bub 
                                        WHERE  received_rpmo <> '0000-00-00 00:00:00 00:00:00' AND tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id  AND modality = 'PAMANA' 
                                        AND (incoming_date = '0000-00-00 00:00:00' 
                                        AND obligation_date = '0000-00-00 00:00:00'
                                         AND cash_date = '0000-00-00 00:00:00' 
                                         AND received_npmo = '0000-00-00 00:00:00'
                                         AND tbl_sp_bub.is_waived = 0 
                                         AND tbl_sp_bub.cancelled = 0)";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsatRPMO'];
        } else {
            return false;
        }
    }

    public function pamana_RfrsAtNPMO()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as RfrsAtNPMO FROM tbl_incoming_bub,tbl_sp_bub 
                                        WHERE tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id  AND modality = 'PAMANA' AND received_npmo <> '0000-00-00' AND tbl_sp_bub.cancelled = 0
                                        AND check_number = ''";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsAtNPMO'];
        } else {
            return false;
        }
    }

    public function pamana_RfrsDownloaded()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as RfrsDownloaded FROM tbl_incoming_bub,tbl_sp_bub 
                                        WHERE tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id  AND modality = 'PAMANA' AND check_number <> ''";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsDownloaded'];
        } else {
            return false;
        }
    }

    public function pamana_RfrsWaived()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as RfrsWaived FROM tbl_incoming_bub,tbl_sp_bub
                                      WHERE tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id AND modality = 'PAMANA' AND is_waived = 1";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsWaived'];
        } else {
            return false;
        }
    }

    public function pamana_RfrsCancelled()
    {
        $mysql = $this->connectDatabase_cfms();
        $q = "SELECT count(*) as RfrsCancelled FROM tbl_incoming_bub,tbl_sp_bub
                                      WHERE tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id AND modality = \"PAMANA\" AND cancelled = 1";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsCancelled'];
        } else {
            return false;
        }
    }
    //END PAMANA

    //START PAMANA 2018
    public function pamana_totalRfrs2018()
    {
        $mysql = $this->connectDatabase_cfms2018();
        $q = "SELECT count(*) as totalRfrs FROM tbl_incoming_bub,tbl_sp_bub
                                      WHERE tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id AND cancelled = 0 AND modality = 'PAMANA'";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['totalRfrs'];
        } else {
            return false;
        }
    }

    public function pamana_RfrsatRPMO2018()
    {
        $mysql = $this->connectDatabase_cfms2018();
        $q = "SELECT count(*) as RfrsatRPMO FROM tbl_incoming_bub,tbl_sp_bub 
                                        WHERE  received_rpmo <> '0000-00-00 00:00:00 00:00:00' AND tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id  AND modality = 'PAMANA' 
                                        AND (incoming_date = '0000-00-00 00:00:00' 
                                        AND obligation_date = '0000-00-00 00:00:00'
                                         AND cash_date = '0000-00-00 00:00:00' 
                                         AND received_npmo = '0000-00-00 00:00:00'
                                         AND tbl_sp_bub.is_waived = 0 
                                         AND tbl_sp_bub.cancelled = 0)";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsatRPMO'];
        } else {
            return false;
        }
    }

    public function pamana_RfrsAtNPMO2018()
    {
        $mysql = $this->connectDatabase_cfms2018();
        $q = "SELECT count(*) as RfrsAtNPMO FROM tbl_incoming_bub,tbl_sp_bub 
                                        WHERE tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id  AND modality = 'PAMANA' AND received_npmo <> '0000-00-00' AND tbl_sp_bub.cancelled = 0
                                        AND check_number = ''";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsAtNPMO'];
        } else {
            return false;
        }
    }

    public function pamana_RfrsDownloaded2018()
    {
        $mysql = $this->connectDatabase_cfms2018();
        $q = "SELECT count(*) as RfrsDownloaded FROM tbl_incoming_bub,tbl_sp_bub 
                                        WHERE tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id  AND modality = 'PAMANA' AND check_number <> ''";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsDownloaded'];
        } else {
            return false;
        }
    }

    public function pamana_RfrsWaived2018()
    {
        $mysql = $this->connectDatabase_cfms2018();
        $q = "SELECT count(*) as RfrsWaived FROM tbl_incoming_bub,tbl_sp_bub
                                      WHERE tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id AND modality = 'PAMANA' AND is_waived = 1";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsWaived'];
        } else {
            return false;
        }
    }

    public function pamana_RfrsCancelled2018()
    {
        $mysql = $this->connectDatabase_cfms2018();
        $q = "SELECT count(*) as RfrsCancelled FROM tbl_incoming_bub,tbl_sp_bub
                                      WHERE tbl_incoming_bub.sp_bub_id = tbl_sp_bub.id AND modality = \"PAMANA\" AND cancelled = 1";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['RfrsCancelled'];
        } else {
            return false;
        }
    }
}