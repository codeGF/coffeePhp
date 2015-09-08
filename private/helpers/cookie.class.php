<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

class Cookie
{

    private $_cookiek = "";

    public function __construct()
    {
    	$this->_cookiek = Pools::get("SYSTEMCONF@SYSTEM_COOKIE_K", true);
    }

    private function _setKey($name)
    {
    	return System::hash(sprintf("%s@%s", $this->_cookiek, $name));
    }

    public function set($name, $value, $date=0, $path="")
    {
    	$name = $this->_setKey($name);
        if (isset($_COOKIE[$name]))
        {
            setcookie($name, "", Pools::get("SYSTEMCONF@SYSTEM_TIME", true)-3600*24);
        }
        return setcookie($name, $value, $date, $path, "", "", true);
    }

    public function get($name)
    {
    	$name = $this->_setKey($name);
    	if (isset($_COOKIE[$name]))
        {
            return $_COOKIE[$name];
        }
        return false;
    }

    public function delete($name)
    {
    	$name = $this->_setKey($name);
        if (isset($_COOKIE[$name]))
        {
            unset($_COOKIE[$name]);
            return setcookie($name, "", Pools::get("SYSTEMCONF@SYSTEM_TIME", true)-3600*24);
        }
        return true;
    }
}