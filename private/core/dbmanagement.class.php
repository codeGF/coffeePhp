<?php


/**
 * 数据库执行错误管理 
 * @author changguofeng <changguofeng3@163.com>
 * @param stus 错误信息分类
 * @param _conn 数据连接资源
 * @param _ecode 错误代码执行方法 
 */

class DBmanagEment extends Base
{
    
    public  static $stus = array();
    private static $_baseconn = null;
    private static $_basename = null;
    private static $_dbname = null;
    private static $_baseconf = array();
    private static $_ecode = array
    (
        "1146"=> "createTable" //表不存在错误码：创建表
    );
    
    public static function deal() //参数分析
    {
        $arr = explode(",", self::$stus["error_str"]);
        if (isset(self::$_ecode[trim($arr[1])]) == true)
        {
            self::$_basename = ServiceManager::get("DBErrorManagementBasename");
            self::$_baseconn = ServiceManager::get("DBErrorManagementBaseconn");
            self::$_baseconf = ServiceManager::get("DBErrorManagementBaseconf");
            self::$_dbname = ServiceManager::get("DBErrorManagementDbname");
            $fun = self::$_ecode[trim($arr[1])];
            self::$fun();
        }
        return;
    }
    
    private static function createTable()
    {
        if (self::lock() == true)
        {
            $sql = str_replace("{{table}}", self::$_dbname, self::$_baseconf["createTable"]);
            self::$_baseconn->query($sql);
        }
        return;
    }
    
    private static function lock()
    {
        static $lock = array();
        if (isset($lock[self::$_dbname]) == false)
        {
            $lock[self::$_dbname] = true;
            return true;
        }
        return false;
    }
}