<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

$conf = array();

$conf["master"] = array
(
    "mysqli"=> array("user"=> "root", "password"=> "root", "dbname"=> "zscn360", "host"=> "127.0.0.1:3306", "charset"=> "utf8"),
    "pdo"=> array("dsn"=> "mysql:host=127.0.0.1;port=3306;dbname=zscn360;charset=utf8;", "user"=> "root", "password"=> "root", "options"=> array(PDO::ATTR_TIMEOUT => 1)),
);

$conf["slave"] = array
(
    "mysqli"=> array("user"=> "root", "password"=> "root", "dbname"=> "zscn360", "host"=> "127.0.0.1:3306", "charset"=> "utf8"),
    "pdo"=> array("dsn"=> "mysql:host=127.0.0.1;port=3306;dbname=zscn360;charset=utf8;", "user"=> "root", "password"=> "root", "options"=> array(PDO::ATTR_TIMEOUT => 1)),
);

ServiceManager::set("DB_CONF", $conf); //注册配置信息
unset($conf);