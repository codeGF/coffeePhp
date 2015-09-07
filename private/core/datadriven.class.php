<?php


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
        $action != null ? false : $action = ServiceManager::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $pdo = ServiceManager::get("pdo{$action}");
        if ($pdo == false)
        {
            $this->auto_->import->load("ezsql/pdo/ez_sql_pdo.php");
            $conf = ServiceManager::get("DB_CONF@{$action}@pdo", true);
            $pdo = new ezSQL_pdo($conf["dsn"], $conf["user"], $conf["password"], $conf["options"]);
            ServiceManager::set("pdo{$action}", $pdo);
        }
        $pdo->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $pdo;
    }

    final protected function mysql_($show_errors=true, $action=null) //mysql驱动
    {
        $action != null ? false : $action = ServiceManager::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $mysql = ServiceManager::get("mysql{$action}");
        if ($mysql == false)
        {
            $conf = ServiceManager::get("DB_CONF@{$action}@mysql", true);
            $this->auto_->import->load("ezsql/mysql/ez_sql_mysql.php");
            $mysql = new ezSQL_mysql($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["charset"]);
            ServiceManager::set("mysql{$action}", $mysql);
        }
        $mysql->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $mysql;
    }

    final protected function mysqli_($show_errors=true, $action=null) //mysql驱动，支持mysqli
    {
        $action != null ? false : $action = ServiceManager::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $mysqli = ServiceManager::get("mysqli{$action}");
        if ($mysqli == false)
        {
            $conf = ServiceManager::get("DB_CONF@{$action}@mysqli", true);
            $this->auto_->import->load("ezsql/mysqli/ez_sql_mysqli.php");
            $mysqli = new ezSQL_mysqli($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["charset"]);
            ServiceManager::set("mysqli{$action}", $mysqli);
        }
        $mysqli->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $mysqli;
    }

    final protected function mssql_($show_errors=true, $action=null) //sqlserver驱动
    {
        $action != null ? false : $action = ServiceManager::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $mssql = ServiceManager::get("mssql{$action}");
        if ($mssql == false)
        {
            $conf = ServiceManager::get("DB_CONF@{$action}@mssql", true);
            $this->auto_->import->load("ezsql/mssql/ez_sql_mssql.php");
            $mssql = new ezSQL_mssql($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["conv"]);
            ServiceManager::set("mssql{$action}", $mssql);
        }
        $mssql->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $mssql;
    }

    final protected function postgresql_($show_errors=true, $action=null) //postgresql驱动
    {
        $action != null ? false : $action = ServiceManager::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $postgresql = ServiceManager::get("postgresql{$action}");
        if ($postgresql == false)
        {
            $conf = ServiceManager::get("DB_CONF@{$action}@postgresql", true);
            $this->auto_->import->load("ezsql/postgresql/ez_sql_postgresql.php");
            $postgresql = new ezSQL_postgresql($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["port"]);
            ServiceManager::set("postgresql{$action}", $postgresql);
        }
        $postgresql->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $postgresql;
    }

    final protected function sqlsrv_($show_errors=true, $action=null) //sqlsrv驱动
    {
        $action != null ? false : $action = ServiceManager::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $sqlsrv = ServiceManager::get("sqlsrv{$action}");
        if ($sqlsrv)
        {
            $conf = ServiceManager::get("DB_CONF@{$action}@sqlsrv", true);
            $this->auto_->import->load("ezsql/sqlsrv/es_sql_sqlsrv.php");
            $sqlsrv = new ezSQL_sqlsrv($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["conv"]);
            ServiceManager::set("sqlsrv{$action}", $sqlsrv);
        }
        $sqlsrv->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $sqlsrv;
    }

    final protected function sybase_($show_errors=true, $action=null) //sybase驱动
    {
        $action != null ? false : $action = ServiceManager::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $sybase = ServiceManager::get("sybase{$action}");
        if ($sybase == false)
        {
            $conf = ServiceManager::get("DB_CONF@{$action}@sybase", true);
            $this->auto_->import->load("ezsql/sqlbase/ez_sql_sybase.php");
            $sybase = new ezSQL_sybase($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["conv"]);
            ServiceManager::set("sybase{$action}", $sybase);
        }
        $sybase->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $sybase;
    }

    final protected function cubird_($show_errors=true, $action=null) //cubird驱动
    {
        $action != null ? false : $action = ServiceManager::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $cubird = ServiceManager::get("cubird{$action}");
        if ($cubird == false)
        {
            $conf = ServiceManager::get("DB_CONF@{$action}@cubird", true);
            $this->auto_->import->load("ezsql/cubrid/ez_sql_cubrid.php");
            $cubird = new ezSQL_cubrid($conf["user"], $conf["password"], $conf["dbname"], $conf["host"], $conf["port"]);
            ServiceManager::set("cubird{$action}", $cubird);
        }
        $cubird->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $cubird;
    }

    final protected function oracle_($show_errors=true, $action=null) //oracle驱动
    {
        $action != null ? false : $action = ServiceManager::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE", true);
        $oracle = ServiceManager::get("oracle{$action}");
        if ($oracle == false)
        {
            $conf = ServiceManager::get("DB_CONF@{$action}@oracle", true);
            $this->auto_->import->load("ezsql/oracle8_9/ez_sql_oracle8_9.php");
            $oracle = new ezSQL_oracle8_9($conf["user"], $conf["password"], $conf["connstr"]);
            ServiceManager::set("oracle{$action}", $oracle);
        }
        $oracle->show_errors = is_bool($show_errors) ? $show_errors : true;
        return $oracle;
    }
}