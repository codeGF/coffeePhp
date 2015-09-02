<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Caches extends Base
{

	private function loadConf()
	{
		require_cache(ServiceManager::get("SYSTEMCONF@APP_CACHE_CONF", true));
		return;
	}

	private function loadCacheFile()
	{
		$this->auto_->import->load("cache/cache.class.php");
		return;
	}

	public function main()
	{
		$result = ServiceManager::get(__CLASS__);
		if ($result == false)
		{
			$this->loadConf(); $this->loadCacheFile();
		    $result = new Cache(ServiceManager::get("CACHE_CONF", true));
			ServiceManager::set(__CLASS__, $result);
		}
		return $result;
	}
}