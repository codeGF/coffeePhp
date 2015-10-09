<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Hook extends Base
{

	private $_hookConf = array();
	private $_hookFile = null;
	private $_controller = null;
	private $_function = null;
	public  $excision = array(".", "@");

	public function __construct()
	{
		parent::__construct();
		$this->_hookFile = Pools::get("SYSTEMCONF@APP_HOOK_CONF", true);
		if (file_exists($this->_hookFile) == true)
		{
			$this->_hookConf = require_cache($this->_hookFile);
			if ($this->_hookConf["en"] == true)
			{
				$this->_construct();
				$this->_function();
			}
		}
	}

	private function _eval($str)
	{
		$data = array(); $tmp = null; preg_match("/\[.*\]/", $str, $data);
		$str = sprintf("\$this->%s", str_replace($this->excision, "->", preg_replace("/\[.*\]/", "", $str)));
		if ($data != false)
		{
			$data = explode(",", str_replace(array("[", "]"), "", $data[0]));
			foreach ($data as $v)
			{
				$tmp .= sprintf("'%s',", $v);
			}
			$tmp = trim($tmp, ",");
		}
		return eval(sprintf("%s(%s);", $str, $tmp)); //钩子机制用到的eval，请放心使用，不存在后门漏洞
	}

	private function _construct()
	{
		$this->_controller = strtolower(Pools::get("router@appController", true));
		$this->_function = strtolower(Pools::get("router@appFunction", true));
		if (array_key_exists($this->_controller, $this->_hookConf["conf"]) == true)
		{
			if (!empty($this->_hookConf["conf"][$this->_controller]["__construct"]))
		    {
			    if (is_array($this->_hookConf["conf"][$this->_controller]["__construct"]) == true)
			    {
				    foreach ($this->_hookConf["conf"][$this->_controller]["__construct"] as $fun)
				    {
					    $this->_eval($fun);
				    }
			    }else
			    {
				    $this->_eval($this->_hookConf["conf"][$this->_controller]["__construct"]);
			    }
		    }
		}
		return;
	}

	private function _function()
	{
		if (!empty($this->_hookConf["conf"][$this->_controller][$this->_function]))
		{
			if (is_array($this->_hookConf["conf"][$this->_controller][$this->_function]))
			{
				foreach ($this->_hookConf["conf"][$this->_controller][$this->_function] as $v)
				{
					$this->_eval($v);
				}
			}else
			{
				$this->_eval($this->_hookConf["conf"][$this->_controller][$this->_function]);
			}
		}
		return;
	}
}