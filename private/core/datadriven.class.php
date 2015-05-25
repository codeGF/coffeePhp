<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

/**
 * 数据库驱动
 * @author changguofeng <281441619@qq.com>
 * @method 支持：pdo mysql mssql mysqli cubrid oracle postgresql sqlsrv sybase
 * @return object
 */

class DataDriven extends Base
{

    private static $_DBaction = false; //默认访问db配置

    final private function _dbAction($action)
    {
    	if (!$action)
    	{
    		if (self::$_DBaction == false)
    		{
    			self::$_DBaction = ServiceManager::get("SYSTEMCONF@SYSTEM_DEFINE_DB_GATE");
    		}
    		$action = self::$_DBaction;
    	}
    	return $action;
    }

    final private function _loadConf($gate)
    {
    	if (ServiceManager::get("DB_CONF@{$gate}"))
    	{
    		return ServiceManager::get("DB_CONF@{$gate}");
    	}
    	System::error(11112, $gate);
    	return;
    }

    final private function _loadezsql($gate)
    {
        $conf = array
        (
            "mysql"=> "mysql/ez_sql_mysql.php", //mysql
            "mssql"=> "mssql/ez_sql_mssql.php", //mssql
            "cubrid"=> "cubrid/ez_sql_cubrid.php", //cubrid
            "mysqli"=> "mysqli/ez_sql_mysqli.php", //mysqli
            "oracle"=> "oracle8_9/ez_sql_oracle8_9.php", //oracle
            "postgresql"=> "postgresql/ez_sql_postgresql.php", //postgresql
            "sqlsrv"=> "sqlsrv/es_sql_sqlsrv.php", //sqlsrv
            "sybase"=> "sqlbase/ez_sql_sybase.php", //sybase
            "pdo"=> "pdo/ez_sql_pdo.php", //pdo
        );
        if (isset($conf[$gate]))
        {
        	$filename = sprintf("%s/ezsql/%s", ServiceManager::get("SYSTEMCONF@SYSTEM_IMPORT_PATH"), $conf[$gate]);
        	return require_cache($filename);
        }
        System::error(11111);
        return;
    }

    final protected function pdo_($action="") //pdo驱动
    {
        $action = $this->_dbAction($action);
        $pdo = ServiceManager::get("pdo{$action}");
        if ($pdo == false)
        {
            $conf = $this->_loadConf("pdo");
            $this->_loadezsql("pdo");
            $pdo = new ezSQL_pdo
            (
                $conf[$action]["dsn"],
                $conf[$action]["user"],
                $conf[$action]["password"],
                $conf[$action]["options"]
            );
            $pdo->charset = $conf[$action]["charset"];
            ServiceManager::set("pdo{$action}", $pdo);
            ServiceManager::set("DBErrorManagementBaseconn", $pdo);
        }
        return $pdo;
    }

    final protected function mysql_($action="") //mysql驱动
    {
        $action = $this->_dbAction($action);
        $mysql = ServiceManager::get("mysql{$action}");
        if ($mysql == false)
        {
            $conf = $this->_loadConf("mysql");
            $this->_loadezsql("mysql");
            $mysql = new ezSQL_mysql
            (
                $conf[$action]["user"],
                $conf[$action]["password"],
                $conf[$action]["dbname"],
                $conf[$action]["host"],
                $conf[$action]["charset"]
            );
            ServiceManager::set("mysql{$action}", $mysql);
            ServiceManager::set("DBErrorManagementBaseconn", $mysql);
        }
        return $mysql;
    }

    final protected function mysqli_($action="") //mysql驱动，支持mysqli
    {
        $action = $this->_dbAction($action);
        $mysqli = ServiceManager::get("mysqli{$action}");
        if ($mysqli == false)
        {
            $conf = $this->_loadConf("mysql");
            $this->_loadezsql("mysqli");
            $mysqli = new ezSQL_mysqli
            (
                $conf[$action]["user"],
                $conf[$action]["password"],
                $conf[$action]["dbname"],
                $conf[$action]["host"],
                $conf[$action]["charset"]
            );
            ServiceManager::set("mysqli{$action}", $mysqli);
            ServiceManager::set("DBErrorManagementBaseconn", $mysqli);
        }
        return $mysqli;
    }

    final protected function mssql_($action="") //sqlserver驱动
    {
        $action = $this->_dbAction($action);
        $mssql = ServiceManager::get("mssql{$action}");
        if ($mssql == false)
        {
            $conf = $this->_loadConf("mssql");
            $this->_loadezsql("mssql");
            $mssql = new ezSQL_mssql
            (
                $conf[$action]["user"],
                $conf[$action]["password"],
                $conf[$action]["dbname"],
                $conf[$action]["host"],
                $conf[$action]["conv"]
            );
            ServiceManager::set("mssql{$action}", $mssql);
            ServiceManager::set("DBErrorManagementBaseconn", $mssql);
        }
        return $mssql;
    }

    final protected function postgresql_($action="") //postgresql驱动
    {
        $action = $this->_dbAction($action);
        $postgresql = ServiceManager::get("postgresql{$action}");
        if ($postgresql == false)
        {
            $conf = $this->_loadConf("postgresql");
            $this->_loadezsql("postgresql");
            $postgresql = new ezSQL_postgresql
            (
                $conf[$action]["user"],
                $conf[$action]["password"],
                $conf[$action]["dbname"],
                $conf[$action]["host"],
                $conf[$action]["port"]
            );
            ServiceManager::set("postgresql{$action}", $postgresql);
            ServiceManager::set("DBErrorManagementBaseconn", $postgresql);
        }
        return $postgresql;
    }

    final protected function sqlsrv_($action="") //sqlsrv驱动
    {
        $action = $this->_dbAction($action);
        $sqlsrv = ServiceManager::get("sqlsrv{$action}");
        if ($sqlsrv)
        {
            $conf = $this->_loadConf("sqlsrv");
            $this->_loadezsql("sqlsrv");
            $sqlsrv = new ezSQL_sqlsrv
            (
                $conf[$action]["user"],
                $conf[$action]["password"],
                $conf[$action]["dbname"],
                $conf[$action]["host"],
                $conf[$action]["conv"]
            );
            ServiceManager::set("sqlsrv{$action}", $sqlsrv);
            ServiceManager::set("DBErrorManagementBaseconn", $sqlsrv);
        }
        return $sqlsrv;
    }

    final protected function sybase_($action="") //sybase驱动
    {
        $action = $this->_dbAction($action);
        $sybase = ServiceManager::get("sybase{$action}");
        if ($sybase == false)
        {
            $conf = $this->_loadConf("sybase");
            $this->_loadezsql("sybase");
            $sybase = new ezSQL_sybase
            (
                $conf[$action]["user"],
                $conf[$action]["password"],
                $conf[$action]["dbname"],
                $conf[$action]["host"],
                $conf[$action]["conv"]
            );
            ServiceManager::set("sybase{$action}", $sybase);
            ServiceManager::set("DBErrorManagementBaseconn", $sybase);
        }
        return $sybase;
    }

    final protected function cubird_($action="") //cubird驱动
    {
        $action = $this->_dbAction($action);
        $cubird = ServiceManager::get("cubird{$action}");
        if ($cubird == false)
        {
            $conf = $this->_loadConf("cubird");
            $this->_loadezsql("cubird");
            $cubird = new ezSQL_cubrid
            (
                $conf[$action]["user"],
                $conf[$action]["password"],
                $conf[$action]["dbname"],
                $conf[$action]["host"],
                $conf[$action]["port"]
            );
            ServiceManager::set("cubird{$action}", $cubird);
            ServiceManager::set("DBErrorManagementBaseconn", $cubird);
        }
        return $cubird;
    }

    final protected function oracle_($action="") //oracle驱动
    {
        $action = $this->_dbAction($action);
        $oracle = ServiceManager::get("oracle{$action}");
        if ($oracle == false)
        {
            $conf = $this->_loadConf("oracle");
            $this->_loadezsql("oracle");
            $oracle = new ezSQL_oracle8_9
            (
                $conf[$action]["user"],
                $conf[$action]["password"],
                $conf[$action]["connstr"]
            );
            ServiceManager::set("oracle{$action}", $oracle);
            ServiceManager::set("DBErrorManagementBaseconn", $oracle);
        }
        return $oracle;
    }
}