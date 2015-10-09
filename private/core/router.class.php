<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Router extends Base
{

	private $_router = array();
    private $_apprunk = "";
    private $_appFunction = "";
    private $_appController = "";
    private $_pgData = array();
    private $_routerStr = "";

    public function __construct()
    {
    	parent::__construct();
    	$this->_apprunk = Pools::get("SYSTEMCONF@SYSTEM_APP_RUN_K", true);
    }

    private function _getRequest()
    {
        $apprunk = $this->_apprunk;
        $this->_routerStr = $this->auto_->helpers->postget->$apprunk;
		if (empty($this->_routerStr))
		{
			defined("MAIN") ? $this->_routerStr = MAIN : $this->_httpStatus();
		}
        $this->_pgData = array_merge
        (
                $this->auto_->helpers->postget->post,
                $this->auto_->helpers->postget->get
        );
        unset($this->_pgData[$this->_apprunk]);
        $this->_pgData = array_values($this->_pgData);
        return;
    }

	private function _getControllerFunction()
	{
		$Delimiter = preg_replace("/[0-9a-zA-Z]/", "", $this->_routerStr);
		if ($Delimiter)
		{
			$this->_router = explode($Delimiter, $this->_routerStr);
		}else
       {
			$this->_router[0] = $this->_routerStr;
		}
		if (empty($this->_router[1]))
		{
			$this->_router[1] = Pools::get("SYSTEMCONF@SYSTEM_APP_FUN_MAIN", true);
		}
		Pools::set("router", array("appController"=>$this->_router[0], "appFunction"=>$this->_router[1]));
        $this->_appController = $this->_router[0];
        $this->_appFunction = $this->_router[1];
        return;
	}

    private function _loadController()
    {
    	$result = false;
    	$appFile = sprintf("%s/%s%s", Pools::get("SYSTEMCONF@APP_CONTROLLER_PATH", true), $this->_appController, Pools::get("SYSTEMCONF@SYSTEM_SUFFIX", true));
        if (file_exists($appFile))
        {
        	require $appFile;
        	$ReflectionClass = new ReflectionClass($this->_appController);
        	if ($ReflectionClass->hasMethod($this->_appFunction))
        	{
        		new Hook;
        		$CTL = $ReflectionClass->newInstance();
        		call_user_func_array(array($CTL, $this->_appFunction), $this->_pgData);
        	    $result = true;
        	}
        }
        if ($result == false)
        {
        	$this->_httpStatus();
        }
        return;
    }

    private function _registry()
    {
    	$file = Pools::get("SYSTEMCONF@APP_REGISTRY_CONF", true);
    	if (file_exists($file))
    	{
    		$data = require_cache($file);
    		if ($data["is"] == true)
    		{
    			if (in_array($this->_appController, $data["list"]) == false)
    			{
    				$this->_httpStatus();
    			}
    		}
    	}
    	return;
    }

	private function _httpStatus()
	{
		$this->auto_->helpers->url->send_http_status(404);
		$this->auto_->helpers->display->tmpView("system/404.html");
        System::quit();
	}

	public static function run()
	{
		$router = new Router;
        $router->_getRequest();
        $router->_getControllerFunction();
        $router->_registry();
        $router->_loadController();
        return;
	}
}