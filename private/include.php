<?php


ob_start();

defined("SYSTEM_ROUTER_RUN") or define('SYSTEM_ROUTER_RUN', true);
defined("ROOT") or define("ROOT", dirname(__FILE__));
defined("DEBUG") or define("DEBUG", E_ALL);
defined("SEND_HEADER") or define("SEND_HEADER", true);
defined("SYSTEM_APP_RUN_K") or define("SYSTEM_APP_RUN_K", "act");

require sprintf("%s/core/system.class.php", ROOT);
require sprintf("%s/core/servicemanager.class.php", ROOT);
require sprintf("%s/%s", ROOT, "conf/ini.php");
require sprintf("%s/core/compile.class.php", ROOT);

Compile::run(false, "20150514");
Router::run();

ob_end_flush();