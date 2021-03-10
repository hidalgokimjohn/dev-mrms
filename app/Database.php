<?php

namespace app;

date_default_timezone_set('Asia/Manila');

use mysqli;

class Database
{
    private static $_instance;
    private $_db_con;
    private $_db_con_kcpis;

    public function __construct()
    {
        //$this->_db_con = @new mysqli('172.26.158.250', 'admin', 'dswd123$', 'kalahi_mrms');
        $this->_db_con = new mysqli('172.26.158.126', 'kalahi_apps', 'ufXBAY2upABl8li0', 'kalahi_mrms');
        $this->_db_con_kcpis = new mysqli('172.26.158.126', 'kalahi_apps', 'ufXBAY2upABl8li0', 'kcpis');
        //$this->_db_con = new mysqli('127.0.0.1', 'root', '', 'kalahi_mrmsv11');
        //$this->_db_con_kcpis = new mysqli('127.0.0.1', 'root', '', 'kcpis');
        if (mysqli_connect_error()) {
            trigger_error('Faitled to connect to MYSQL. ' . mysqli_connect_error(), E_USER_ERROR);
        } else {
            $this->_db_con->set_charset("utf8");
            $this->_db_con_kcpis->set_charset("utf8");
        }
    }

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __clone()
    {
    }

    public function getConnection()
    {
        return $this->_db_con;
    }
    public function getConnectionKCPIS()
    {
        return $this->_db_con_kcpis;
    }
}
