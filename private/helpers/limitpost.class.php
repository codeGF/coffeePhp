<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 * 防止重复操作
 * @return blue , true为可操作，false为不可操作
 * @name expires 默认2分钟内为false，可依据情况作出修改，值为秒
 */
class LimitPost extends Base
{

    public $key = "limit@post@time";
    public $expires = 120;
    private $_result = false;

    public function is()
    {
        $this->key = System::hash($this->key);
        $time = $this->auto->helpers->session->get($this->key);
        if (empty($time)) {
            $this->auto->helpers->session->set($this->key, Pools::get("SYSTEMCONF@SYSTEM_TIME", true));
            $this->_results = true;
        } else {
            $newtime = Pools::get("SYSTEMCONF@SYSTEM_TIME", true);
            $oldtime = $this->auto->helpers->session->get($this->key);
            if (($newtime - $oldtime) > $this->expires) {
                $this->auto->helpers->session->delete($this->key);
                $this->_results = true;
            }
        }
        return $this->_result;
    }
}