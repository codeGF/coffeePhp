<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

$conf = array();

//【框架配置】
$conf["SYSTEM_ENCODING"] = "utf-8"; //系统字符集
$conf["SYSTEM_APP_RUN_K"] = SYSTEM_APP_RUN_K; //框架接收动作参数
$conf["SYSTEM_APP_FUN_MAIN"] = "main"; //注册框架默认执行方法
$conf["SYSTEM_DEFINE_DB_GATE"] = "master"; //默认访问数据库主库配置
$conf["SYSTEM_SESSION_K"] = "__SESSION__"; //session前缀
$conf["SYSTEM_COOKIE_K"] = "__COOKIE__"; //cookie前缀
$conf["SYSTEM_MEMORY_LIMIT"] = "20M"; //设置框架运行周期内存大小限制
$conf["SYSTEM_SET_TIME_LIMIT"] = 30; //php最大运行时间设置
$conf["SYSTEM_TIMEZONE_SET"] = "Asia/ShangHai"; //时区设置
$conf["SYSTEM_TIME"] = $_SERVER["REQUEST_TIME"]; //初始化全局时间
$conf["SYSTEM_ERROR_PROMPT"] = true; //是否显示错误，发布上线时，建议设置为false
$conf["SYSTEM_ERROR_TO_EMAIL"] = array("changguofeng@kingsoft.com"); //是否将错误发送到指定邮箱
$conf["SYSTEM_SUFFIX"] = ".class.php"; //框架文件后缀规范

//【框架路径设置，结尾必须没有“/”】
$conf["SYSTEM_CONF_PATH"] = ROOT."/conf"; //配置文件存放目录
$conf["SYSTEM_CORE_PATH"] = ROOT."/core"; //框架核心文件
$conf["SYSTEM_DISPLAY_PATH"] = ROOT."/display"; //框架模板存放路径
$conf["SYSTEM_HELPERS_PATH"] = ROOT."/helpers"; //框架扩展存放路径
$conf["SYSTEM_IMPORT_PATH"] = ROOT."/import"; //框架第三方扩展路径
$conf["SYSTEM_COMMON_PATH"] = ROOT."/common"; //系统函数库

//【框架应用路径设置，结尾必须没有“/”】
$conf["APP_PATH"] = defined("APP") ? APP : dirname($_SERVER['SCRIPT_FILENAME']); //app应用路径
$conf["APP_CONFIG_PATH"] = $conf["APP_PATH"]."/config"; //app config文件路径
$conf["APP_PRIVATE_CONFIG_PATH"] = $conf["APP_CONFIG_PATH"]."/private"; //高权限config文件路径
$conf["APP_CONTROLLER_PATH"] = $conf["APP_PATH"]."/controller"; //app controller脚本路径
$conf["APP_MODEL_PATH"] = $conf["APP_PATH"]."/model"; //app model脚本路径
$conf["APP_VIEW_PATH"] = $conf["APP_PATH"]."/view"; //app view脚本路径
$conf["APP_LAYOUT_PATH"] = $conf["APP_VIEW_PATH"]."/layout"; //公共视图路径
$conf["APP_SERVICE_PATH"] = $conf["APP_PATH"]."/service"; //业务层脚本路径
$conf["APP_TEMP_PATH"] = $conf["APP_PATH"]."/temp"; //业务层临时文件路径
$conf["APP_EXT_PATH"] = $conf["APP_PATH"]."/ext"; //内部扩展层文件路径
$conf["APP_LIB_PATH"] = $conf["APP_PATH"]."/lib"; //外部文件路径
$conf["APP_CACHE_PATH"] = $conf["APP_TEMP_PATH"]."/cache"; //应用层缓存路径
$conf["APP_SESSION_PATH"] = $conf["APP_TEMP_PATH"]."/session"; //session储存路径
$conf["APP_COMPILE_FILE_PATH"] = $conf["APP_TEMP_PATH"]."/boot"; //系统编译文件存放路径
$conf["APP_LOGS_PATH"] = $conf["APP_TEMP_PATH"]."/logs"; //临时文件目录

//后缀设置
$conf["APP_DISPLAY_NAME"] = ".html.php"; //模板名称
$conf["APP_COMPILE_FILE_SAVE"] ="~compile_%s.php"; //编译后文件名称

//应用配置文件设置
$conf["APP_HOOK_CONF"] = $conf["APP_PRIVATE_CONFIG_PATH"]."/hook.php"; //钩子配置文件
$conf["APP_REGISTRY_CONF"] = $conf["APP_PRIVATE_CONFIG_PATH"]."/registry.php"; //路由白名单配置文件
$conf["APP_SQL_CONF"] = $conf["APP_PRIVATE_CONFIG_PATH"]."/sql.php"; //数据库语句配置文件
$conf["APP_DB_CONF"] = $conf["APP_PRIVATE_CONFIG_PATH"]."/db.php"; //数据库配置文件路径
$conf["APP_CACHE_CONF"] = $conf["APP_PRIVATE_CONFIG_PATH"]."/cache.php"; //缓存配置文件路径

//其他设置
$conf["APP_SESSION_LOCAL_DIST"] = false; //是否执行本服务器session分布式储存，有利于提高session性能

//【补充PHP不支持的常量】
defined("__DIR__") or define("__DIR__", dirname(__FILE__));

//【PHP设置】
date_default_timezone_set($conf["SYSTEM_TIMEZONE_SET"]); //设置时区
ini_set('memory_limit', $conf["SYSTEM_MEMORY_LIMIT"]); //设置周期内存限制
ini_set("max_execution_time", $conf["SYSTEM_SET_TIME_LIMIT"]); //设置运行时间
if (function_exists("mb_internal_encoding")) //设置内部字符集
{
    mb_internal_encoding($conf["SYSTEM_ENCODING"]);
}
if ($conf["APP_SESSION_LOCAL_DIST"] == true) //设置session分布式储存保存路径
{
    session_save_path('2;'.$conf["APP_SESSION_PATH"]);
}
error_reporting(DEBUG); //系统错误提示
register_shutdown_function(array("System", "shutdown")); //结束时执行收尾程序

ServiceManager::set("SYSTEMCONF", $conf);
unset($conf);