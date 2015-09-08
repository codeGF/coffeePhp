<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

ob_start();

defined("SYSTEM_ROUTER_RUN") or define('SYSTEM_ROUTER_RUN', true);
defined("ROOT") or define("ROOT", dirname(__FILE__));
defined("DEBUG") or define("DEBUG", E_ALL);
defined("SEND_HEADER") or define("SEND_HEADER", true);
defined("SYSTEM_APP_RUN_K") or define("SYSTEM_APP_RUN_K", "act");

require sprintf("%s/core/system.class.php", ROOT);
require sprintf("%s/core/Pools.class.php", ROOT);
require sprintf("%s/%s", ROOT, "conf/ini.php");
require sprintf("%s/core/compile.class.php", ROOT);

Compile::run(false, "20150514");
Router::run();

ob_end_flush();