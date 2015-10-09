<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Thread extends Base
{

    public function start($url, $data=array(), $proxy=null)
    {
        $this->auto_->helpers->curl->timeout = 1;
        $this->auto_->helpers->curl->connecttimeout = 15;
        $this->auto_->helpers->curl->proxy = $proxy;
        $result = $this->auto_->helpers->curl->post($url, $data);
        return $result;
    }
}