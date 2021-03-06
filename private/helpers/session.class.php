<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Session extends Base
{

    public $sessionk = "";

    public function __construct()
    {
        $this->sessionk = Pools::get("SYSTEMCONF@SYSTEM_SESSION_K", true);
        if (Pools::get("SYSTEMCONF@APP_SESSION_LOCAL_DIST", true) == true) {
            $this->_savePath();
        }
        if (!session_id()) session_start();
    }

    public function set($name, $value = "v") //设置值
    {
        if (!empty($value) && $value != "v") {
            return $this->_setValue($name, $value);
        } else if (empty($value) || $value == "v") {
            return $this->_unsetValue($name);
        }
    }

    public function delete($name) //删除一个值
    {
        return $this->_unsetValue($name);
    }

    public function get($name) //获取一个值
    {
        return $this->_getValue($name);
    }

    public function clear() //清除所有值
    {
        $_SESSION[$this->sessionk] = array();
        unset($_SESSION[$this->sessionk]);
        return isset($_SESSION[$this->sessionk]) ? false : true;
    }

    private function _getValue($name)
    {
        $name = System::hash($name);
        if (isset($_SESSION[$this->sessionk][$name])) {
            return $_SESSION[$this->sessionk][$name];
        }
        return false;
    }

    private function _setValue($name, $value)
    {
        $name = System::hash($name);
        $_SESSION[$this->sessionk][$name] = "";
        return $_SESSION[$this->sessionk][$name] = $value;
    }

    private function _unsetValue($name)
    {
        $name = System::hash($name);
        $_SESSION[$this->sessionk][$name] = "";
        unset($_SESSION[$this->sessionk][$name]);
        return isset($_SESSION[$this->sessionk][$name]) ? false : true;
    }

    private function _savePath() //分布式session文件储存
    {
        $sessionPath = Pools::get("SYSTEMCONF@APP_SESSION_PATH", true);
        if (file_exists($sessionPath) == false) {
            $dir = '123456789abcdefghijklmnopqrstuvwxyz';
            $nums = strlen($dir);
            for ($i = 0; $i < $nums; ++$i) {
                for ($j = 0; $j < $nums; ++$j) {
                    $dirname = sprintf("%s/%s/%s", $sessionPath, $dir[$i], $dir[$j]);
                    if (!is_file($dirname)) {
                        mkdir($dirname, 0777, true);
                    }
                }
            }
        }
        return;
    }
}