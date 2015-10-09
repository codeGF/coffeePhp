<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Thread extends Base
{

    public function start($url, $data = array(), $proxy = null)
    {
        $this->auto->helpers->curl->timeout = 1;
        $this->auto->helpers->curl->connecttimeout = 15;
        $this->auto->helpers->curl->proxy = $proxy;
        $result = $this->auto->helpers->curl->post($url, $data);
        return $result;
    }
}