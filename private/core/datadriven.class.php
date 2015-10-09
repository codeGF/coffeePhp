<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

/**
 * 加载数据库驱动
 * @author changguofeng <281441619@qq.com>
 * @method 支持：pdo mysql mssql mysqli cubrid oracle postgresql sqlsrv sybase
 * @return object
 */

class DataDriven extends Base
{

    final protected function pdo_($show_errors=true, $action=null) //pdo驱动
    {
        $action != null ? false : $action = Pools::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $pdo = Pools::get("pdo{$action}");
        if ($pdo == false)
        {
            $this->auto_->import->load("ezsql/pdo/ez_sql_pdo.php");
            $conf = Pools::get("DB_CONF@{$action}@pdo", true);
            $pdo = new ezSQL_pdo($conf["dsn"], $conf["user"], $conf["password"], $conf["options"]);
            Pools::set("pdo{$action}", $pdo);
        }
        $pdo->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $pdo;
    }

    final protected function mysql_($show_errors=true, $action=null) //mysql驱动
    {
        $action != null ? false : $action = Pools::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $mysql = Pools::get("mysql{$action}");
        if ($mysql == false)
        {
            $conf = Pools::get("DB_CONF@{$action}@mysql", true);
            $this->auto_->import->load("ezsql/mysql/ez_sql_mysql.php");
            $mysql = new ezSQL_mysql($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["charset"]);
            Pools::set("mysql{$action}", $mysql);
        }
        $mysql->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $mysql;
    }

    final protected function mysqli_($show_errors=true, $action=null) //mysql驱动，支持mysqli
    {
        $action != null ? false : $action = Pools::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $mysqli = Pools::get("mysqli{$action}");
        if ($mysqli == false)
        {
            $conf = Pools::get("DB_CONF@{$action}@mysqli", true);
            $this->auto_->import->load("ezsql/mysqli/ez_sql_mysqli.php");
            $mysqli = new ezSQL_mysqli($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["charset"]);
            Pools::set("mysqli{$action}", $mysqli);
        }
        $mysqli->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $mysqli;
    }

    final protected function mssql_($show_errors=true, $action=null) //sqlserver驱动
    {
        $action != null ? false : $action = Pools::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $mssql = Pools::get("mssql{$action}");
        if ($mssql == false)
        {
            $conf = Pools::get("DB_CONF@{$action}@mssql", true);
            $this->auto_->import->load("ezsql/mssql/ez_sql_mssql.php");
            $mssql = new ezSQL_mssql($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["conv"]);
            Pools::set("mssql{$action}", $mssql);
        }
        $mssql->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $mssql;
    }

    final protected function postgresql_($show_errors=true, $action=null) //postgresql驱动
    {
        $action != null ? false : $action = Pools::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $postgresql = Pools::get("postgresql{$action}");
        if ($postgresql == false)
        {
            $conf = Pools::get("DB_CONF@{$action}@postgresql", true);
            $this->auto_->import->load("ezsql/postgresql/ez_sql_postgresql.php");
            $postgresql = new ezSQL_postgresql($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["port"]);
            Pools::set("postgresql{$action}", $postgresql);
        }
        $postgresql->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $postgresql;
    }

    final protected function sqlsrv_($show_errors=true, $action=null) //sqlsrv驱动
    {
        $action != null ? false : $action = Pools::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $sqlsrv = Pools::get("sqlsrv{$action}");
        if ($sqlsrv)
        {
            $conf = Pools::get("DB_CONF@{$action}@sqlsrv", true);
            $this->auto_->import->load("ezsql/sqlsrv/es_sql_sqlsrv.php");
            $sqlsrv = new ezSQL_sqlsrv($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["conv"]);
            Pools::set("sqlsrv{$action}", $sqlsrv);
        }
        $sqlsrv->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $sqlsrv;
    }

    final protected function sybase_($show_errors=true, $action=null) //sybase驱动
    {
        $action != null ? false : $action = Pools::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $sybase = Pools::get("sybase{$action}");
        if ($sybase == false)
        {
            $conf = Pools::get("DB_CONF@{$action}@sybase", true);
            $this->auto_->import->load("ezsql/sqlbase/ez_sql_sybase.php");
            $sybase = new ezSQL_sybase($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["conv"]);
            Pools::set("sybase{$action}", $sybase);
        }
        $sybase->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $sybase;
    }

    final protected function cubird_($show_errors=true, $action=null) //cubird驱动
    {
        $action != null ? false : $action = Pools::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $cubird = Pools::get("cubird{$action}");
        if ($cubird == false)
        {
            $conf = Pools::get("DB_CONF@{$action}@cubird", true);
            $this->auto_->import->load("ezsql/cubrid/ez_sql_cubrid.php");
            $cubird = new ezSQL_cubrid($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["port"]);
            Pools::set("cubird{$action}", $cubird);
        }
        $cubird->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $cubird;
    }

    final protected function oracle_($show_errors=true, $action=null) //oracle驱动
    {
        $action != null ? false : $action = Pools::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $oracle = Pools::get("oracle{$action}");
        if ($oracle == false)
        {
            $conf = Pools::get("DB_CONF@{$action}@oracle", true);
            $this->auto_->import->load("ezsql/oracle8_9/ez_sql_oracle8_9.php");
            $oracle = new ezSQL_oracle8_9($conf["user"], $conf["password"], $conf["connstr"]);
            Pools::set("oracle{$action}", $oracle);
        }
        $oracle->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $oracle;
    }
}