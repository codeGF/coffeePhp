<?php

class Tm extends Model
{

    public function __construct()
    {
        parent::__construct();
        Pools::set("DBmanagementConn", $this->mysqli_(false));
    }

    public function s1()
    {
        //$sql = "SELECT * FROM `{$this->tabname_}`";
        for ($i=0; $i<=2; $i++)
        {
            $sql = $this->insertSql(array("id"=> time()));
            $this->mysqli_()->query($sql);
        }
    }
}