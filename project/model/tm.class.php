<?php

class Tm extends Model
{

    public static $conf = array
    (
        "name" => "test",
        "expand" => "Y",
        "createTable" => "CREATE TABLE IF NOT EXISTS `{{table}}` (
                              `id` int(9) NOT NULL COMMENT '序号（唯一）',
                              PRIMARY KEY (`id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
    );

    public function s()
    {
        $sql = "INSERT INTO `{$this->dbname_}` SET `id`=123111";
        $this->pdo_()->query($sql);
    }
}