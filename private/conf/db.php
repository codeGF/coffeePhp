<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

$conf = array();
$conf["pdo"] = array();

$conf["pdo"]["default"]["dsn"] = "mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8;";
$conf["pdo"]["default"]["user"] = "root";
$conf["pdo"]["default"]["password"] = "root";
$conf["pdo"]["default"]["options"] = "";
$conf["pdo"]["default"]["charset"] = "utf8";

$conf["pdo"]["slave"]["dsn"] = "mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8;";
$conf["pdo"]["slave"]["user"] = "root";
$conf["pdo"]["slave"]["password"] = "root";
$conf["pdo"]["slave"]["options"] = "";
$conf["pdo"]["slave"]["charset"] = "utf8";

ServiceManager::set("DB_CONF", $conf); //注册配置信息
unset($conf);