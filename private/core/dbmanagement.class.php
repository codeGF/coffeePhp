<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 * 数据库执行错误管理
 * @param msg 错误信息
 */

class DBmanagEment extends Base
{

    public  static $msg = array();
    private static $_act = array
    (
        1146 => "createTable"
    );
    private static $_errSqlPreg = "/^insert|^delete|^update/is";

    public static function main() //开始分析错误
    {
        self::errno(); self::errsql();
        return;
    }

    private static function errno() //针对错误码分析处理
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

    private static function errsql() //sql错误分析处理
    {
        $rtn = false;
        if (preg_match(self::$_errSqlPreg, self::$msg["query"]) == true && self::$msg["show_errors"] == false)
        {
            $rtn = self::_conn()->query(self::$msg["query"]);
        }
        return $rtn;
    }

    private static function _conn() //获取一个数据连接资源
    {
        return Pools::get("DBmanagementConn");
    }

    private static function createTable() //创建表
    {
        $conf = Pools::get("DBmanagementConf");
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