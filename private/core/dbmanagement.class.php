<?php


/**
 * 数据库执行错误管理
 * @author changguofeng <changguofeng3@163.com>
 * @param msg 错误信息
 */

class DBmanagEment extends Base
{

    public  static $msg = array();
    private static $_act = array
    (
        1146 => "createTable"
    );

    public static function main() //开始分析错误
    {
        if (empty(self::$msg["errno"]) == false)
        {
            if (isset(self::$_act[self::$msg["errno"]]) == true)
            {
                $fun = self::$_act[self::$msg["errno"]];
                self::$fun();
            }
        }
        return;
    }

    private static function _conn() //获取一个数据连接资源
    {
        return ServiceManager::get("DBmanagementConn");
    }

    public static function createTable() //创建表
    {
        $conf = ServiceManager::get("DBmanagementConf");
        if ($conf != false && empty($conf["tabname"])==false && empty($conf["createTable"])==false)
        {
            if (self::_conn() != false)
            {
                $sql = str_replace("{table}", $conf["tabname"], $conf["createTable"]);
                self::_conn()->query($sql);
            }
        }
        return;
    }
}