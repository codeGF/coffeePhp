<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

class Check
{

    private function _preg($preg, $str)
    {
        return preg_match($preg, $str);
    }

    //验证ip地址是否正确
    public function is_ip($ip)
    {
    	return $this->_preg("/([1-9]|[1-9]\\d|1\\d{2}|2[0-4]\\d|25[0-5])(\\.(\\d|[1-9]\\d|1\\d{2}|2[0-4]\\d|25[0-5])){3}/", $ip);
    }

    //是否正确邮箱
    public function is_email($str)
    {
        return $this->_preg("/[a-z0-9&\-_.]+@[\w\-_]+([\w\-.]+)?\.[\w\-]+/is", $str);
    }

    //是否正确url连接
    public function is_url($str)
    {
        return $this->_preg("/(http|https|ftp|ftps)://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?/i", $str);
    }

    //是否汉字
    public function is_char($str)
    {
        return $this->_preg("/[\x{4e00}-\x{9fa5}]+/u", $str);
    }

    //邮编号是否正确
    public function is_post($str)
    {
        return $this->_preg("/^[1-9][0-9]{5}$/", $str);
    }

    //身份证号是否正确
    public function is_pcard($str)
    {
        return $this->_preg("/^[\d]{15}$|^[\d]{18}$/", $str);
    }

    //出版物ISBN是否正确
    public function is_isbn($str)
    {
        return $this->_preg("/^978[\d]{10}$|^978-[\d]{10}$/", $str);
    }

    //手机号码是否正确（大陆）
    public function is_mobile($str)
    {
        return $this->_preg("/^13[\d]{9}$|14^[0-9]\d{8}|^15[0-9]\d{8}$|^18[0-9]\d{8}$/", $str);
    }
}