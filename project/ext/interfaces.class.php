<?php


class Interfaces extends App
{
    
    public  $timeout = 5; //默认接口超时时间
    public  $connecttimeout = 5; //默认握手超时时间
    public  $logsData = array(); //日记数据
    public  $data = array(); //接口返回数据
    public  $isLog = true;
    
    public function __construct()
    {
    	parent::__construct();
    	$this->_construct(); //初始化数据
    }
    
    private function _construct()
    {
    	$this->auto_->helpers->curl->timeout = $this->timeout;
    	$this->auto_->helpers->curl->connecttimeout = $this->connecttimeout;
    	$this->auto_->helpers->curl->httpHead = array
    	(
    	    "Content-Type: application/x-www-form-urlencoded",
    	    "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/536.35"
    	);
    	$this->auto_->helpers->curl->referer = "http://mp.weixin.qq.com/s?__biz=MjM5Nzg3Mjg2Mg==&mid=205064116&idx=1&sn=6b8928aeddc72ccc3ede2050d84162d3&scene=1&from=singlemessage&isappinstalled=0#rd";
    }
    
    public function post($url, $data)
    {
    	$this->data = $this->auto_->helpers->curl->post($url, $data);
    	return $this->data;
    }
}