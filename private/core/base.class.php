<?php


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
		if (ServiceManager::get("base@construct") == false)
		{
			$this->system_->date = ServiceManager::get("SYSTEMCONF@SYSTEM_TIME");
			$this->system_->encoding = ServiceManager::get("SYSTEMCONF@SYSTEM_ENCODING");
			$this->auto_ = new Auto;
			ServiceManager::set("base@construct@auto_", $this->auto_);
			ServiceManager::set("base@construct@base_", $this->system_);
			ServiceManager::set("base@construct", true);
		}else
		{
			$this->auto_ = ServiceManager::get("base@construct@auto_");
			$this->system_ = ServiceManager::get("base@construct@base_");
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