<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

$conf = array();

$conf["MAIL_HOST"] = "smtp.163.com"; //SMTP服务器
$conf["MAIL_SMTPAUTH"] = true; //开启SMTP认证,true || false
$conf["MAIL_USERNAME"] = "car365org@163.com"; //SMTP用户名
$conf["MAIL_PSWD"] = "car3653206"; //SMTP密码
$conf["MAIL_TOEMAILER"] = "car365org@163.com"; //发件人地址
$conf["MAIL_FROMNAME"] = "[SYSTEM_EMAIL]"; //发件人
$conf["MAIL_WORDWRAP"] = 500; //设置每行字符长度
$conf["MAIL_ENCODING"] = "utf-8"; //字符集

ServiceManager::set("MAIL_CONF", $conf); //注册配置信息
unset($conf);