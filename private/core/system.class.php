<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class System
{

    public static function sapi($method, $prompt = null)
    {
        if (strcasecmp(php_sapi_name(), $method) != 0) {
            self::quit($prompt != null ? $prompt : "You must be run in the specified mode");
        }
    }

    public static function header($info)
    {
        if (is_array($info) == true) {
            foreach ($info as $v) {
                header($v);
            }
        } else {
            header($info);
        }
    }

    public static function hash($mix, $algo="haval128,5")
    {
        if (is_object($mix) == true) {
            $mix = spl_object_hash($mix);
        } else if (is_resource($mix) == true) {
            $mix = get_resource_type($mix) . strval($mix);
        } else {
            $mix = serialize($mix);
        }
        return hash($algo, $mix);
    }

    public static function quit($str = 250)
    {
        exit($str);
    }

    public static function error($code = null, $message = null)
    {
        throw new PrivateException(array("message" => $message, "code" => $code), 0);
        self::quit();
    }
}