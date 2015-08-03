<?php

class Tm extends Model
{

    public function s()
    {
        $sql = "INSERT INTO `{$this->dbname_}` SET `id`=12311";
        var_dump($this->pdo_()->query($sql));
    }
}