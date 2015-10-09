<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

abstract class Base
{
    
    protected $auto_ = null;
	protected $system_ = array();
	
	public function __construct()
	{
		$this->system_ = (object)array();
		$this->_auto();
	}

	final private function _auto()
	{
		if (Pools::get("base@construct") == false)
		{
			$this->system_->date = Pools::get("SYSTEMCONF@SYSTEM_TIME", true);
			$this->system_->encoding = Pools::get("SYSTEMCONF@SYSTEM_ENCODING", true);
			$this->auto_ = new Auto;
			Pools::set("base@construct@auto_", $this->auto_);
			Pools::set("base@construct@base_", $this->system_);
			Pools::set("base@construct", true);
		}else
		{
			$this->auto_ = Pools::get("base@construct@auto_", true);
			$this->system_ = Pools::get("base@construct@base_", true);
		}
		return;
	}

    public function __set($name, $value)
    {
    	System::error(11120, $name);
    	return;
    }

    public function __get($name)
    {
        System::error(11121, $name);
        return;
    }

    public function __call($name, $arguments)
    {
        System::error(11122, $name);
        return;
    }
}