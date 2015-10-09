<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */
class Log
{

    public function set($filename, $str)
    {
        if (is_writable(ROOT)) {
            if (file_exists(Pools::get("SYSTEMCONF@APP_LOGS_PATH", true)) == false) {
                mkdir(Pools::get("SYSTEMCONF@APP_LOGS_PATH", true), 0777, TRUE);
            }
            error_log($str . "\r\n", 3, sprintf("%s/%s", Pools::get("SYSTEMCONF@APP_LOGS_PATH", true), trim($filename, '/')));
        } else {
            System::error(11131, ROOT);
        }
        return;
    }
}