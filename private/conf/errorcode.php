<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

$conf = array();

$conf[11111] = "ezsql扩展不存在该类型文件配置，请您检查core/model.class.php 的 loadezsql方法";
$conf[11112] = "dbconf配置文件中不存在您指定的数据资源类型{e}";
$conf[11113] = "您调用的扩展不存在";
$conf[11114] = "不可以重复设置属性{e}";
$conf[11115] = "您访问了不存在的属性{e}";
$conf[11116] = "您访问了不存在的方法{e}";
$conf[11117] = "您调用了不存在的{e}类";
$conf[11118] = "加载{e}.php文件失败，请您确认文件是否存在";
$conf[11119] = "视图文件{e}不存在";
$conf[11120] = "不可以设置属性{e}";
$conf[11121] = "不可以获取属性{e}";
$conf[11122] = "您访问了不存在方法{e}";
$conf[11123] = "控制参数{e}不能为空";
$conf[11124] = "路径{e}不可写，权限不足";
$conf[11125] = "{e}参数不能为空，执行操作需提前指定";
$conf[11126] = "参数不能为空";
$conf[11127] = "{e}文件不存在";
$conf[11128] = "require_cache函数调用文件{e}错误";
$conf[11129] = "<!-- :( System memory size exceeds limit %sMB -->";
$conf[11130] = "<!-- :) Within the range of normal memory %sMB -->";
$conf[11131] = "{e}目录不可写";
$conf[11132] = "mb_convert_encoding和iconv俩个方法无法再您系统中运行";
$conf[11133] = "您设置的钩子文件{e}不存在";
$conf[11134] = "您设置的钩子类{e}不存在";
$conf[11135] = "您设置的钩子方法{e}不存在";
$conf[11136] = "autoload只支持system和app俩个应用钩子，您调用的{e}不存在";
$conf[11137] = "系统不支持eval函数，钩子机制无法使用";
$conf[11138] = "写入文件{e}失败";
$conf[11139] = "加载配置文件函数{e}出错";

ServiceManager::set("ERRORCODE", $conf); //注册配置信息
unset($conf);