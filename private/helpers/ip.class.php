<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */
class IP
{

    /**
     * @param number $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param string $adv 是否进行高级模式获取（有可能被伪装）
     */
    public function get($type = 0, $adv = true)
    {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL) return $ip[$type];
        if ($adv) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $arr = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
                $pos = array_search("unknown", $arr);
                if (false !== $pos) unset($arr[$pos]);
                $ip = trim($arr[0]);
            } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } elseif (isset($_SERVER["REMOTE_ADDR"])) {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
        } elseif (isset($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array("0.0.0.0", 0);
        return $ip[$type];
    }

    public function isScope($uip, $scope) //检测IP是否在一个指定的范围内
    {
        $result = false;
        $uip = str_replace(".", "", $uip);
        $scope = explode(",", str_replace(array(".", "*"), "", implode(",", (array)$scope)));
        foreach ($scope as $lsip) {
            if (strcasecmp($lsip, substr($uip, 0, strlen($lsip))) == 0) {
                $result = true;
                break;
            }
        }
        return $result;
    }
}