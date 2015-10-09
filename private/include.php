<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */

ob_start();

defined("SYSTEM_ROUTER_RUN") or define('SYSTEM_ROUTER_RUN', true);
defined("ROOT") or define("ROOT", dirname(__FILE__));
defined("DEBUG") or define("DEBUG", 0);
defined("SYSTEM_APP_RUN_K") or define("SYSTEM_APP_RUN_K", "act");
defined("SYSTEM_MAX_EXECUTION_TIME") or define("SYSTEM_MAX_EXECUTION_TIME", 30);

require sprintf("%s/core/system.class.php", ROOT);
require sprintf("%s/core/pools.class.php", ROOT);
require sprintf("%s/%s", ROOT, "conf/ini.php");
require sprintf("%s/core/compile.class.php", ROOT);

Compile::run(false, "20150514");
Router::run();

ob_end_flush();