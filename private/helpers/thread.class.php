<?php


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