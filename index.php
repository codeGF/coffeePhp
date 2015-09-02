<?php


/**
 * @var SYSTEM_ROUTER_RUN 项目是否运行，false时会die掉
 * @var ROOT 框架路径
 * @var APP 应用路径
 * @var MAIN 默认访问控制器类，系统默认访问配置文件中的配置方法
 */

$tmpdirname = str_replace("\\", "/", dirname(__FILE__));

define("SYSTEM_APP_RUN_K", "act");
define("DEBUG", E_ALL);
define("SYSTEM_ROUTER_RUN", true);
define("SEND_HEADER", true);
define("ROOT", $tmpdirname."/private");
define("APP", $tmpdirname."/project");
define("MAIN", "index");

require ROOT."/include.php";