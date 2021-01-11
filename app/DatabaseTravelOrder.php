<?php

class DatabaseTravelOrder
{
    private static $_instance;
    private $_db_con;

    public function __construct()
    {
        $this->_db_con = @new mysqli('172.26.158.126', 'kalahi_apps', 'ufXBAY2upABl8li0', 'kcpis');
        if (mysqli_connect_error()) {
            trigger_error('Failed to connect to MYSQL. ' . mysqli_connect_error(), E_USER_ERROR);
        } else {
            $this->_db_con->set_charset("utf8");
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
}