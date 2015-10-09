<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */
class Memory
{

    private static $_m = "";

    public function s()
    {
        self::$_m = memory_get_usage();
        return;
    }

    public function e()
    {
        $systemMemoryLimit = preg_replace("/[^0-9]/", "", Pools::get("SYSTEMCONF@SYSTEM_MEMORY_LIMIT", true));
        $t = sprintf("%01.2f", (memory_get_usage() - self::$_m) / 1024 / 1024);
        if ($t > $systemMemoryLimit) {
            printf(Pools::get("ERRORCODE@11129", true), $systemMemoryLimit);
        } else {
            printf(Pools::get("ERRORCODE@11130", true), $t);
        }
        return;
    }
}