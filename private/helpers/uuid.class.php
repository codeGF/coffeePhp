<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */
class Uuid
{

    public function get($len = 6, $char = "")
    {
        $chars = $char ? $char : "ABCDEFGHIJKLMNOPQRSTUVWXYabcdefghijklmnopqrstuvwxyz1234567890~!@#%^&*()_+";
        mt_srand((double)microtime() * 1000000 * getmypid());
        $uuid = "";
        --$len;
        while (strlen($uuid) <= $len) {
            $uuid .= substr($chars, (mt_rand() % strlen($chars)), 1);
        }
        return $uuid;
    }
}