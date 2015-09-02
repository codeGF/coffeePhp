<?php

class Tm extends Model
{

    public function __construct()
    {
        parent::__construct();
        ServiceManager::set("DBmanagementConn", $this->mysqli_(false));
    }

    public function s1()
    {
        $sql = "SELECT * FROM `{$this->tabname_}`";
        $this->mysqli_()->query($sql);
    }
}