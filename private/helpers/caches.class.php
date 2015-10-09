<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Caches extends Base
{

	private function loadConf()
	{
		require_cache(Pools::get("SYSTEMCONF@APP_CACHE_CONF", true));
		return;
	}

	private function loadCacheFile()
	{
		$this->auto_->import->load("cache/cache.class.php");
		return;
	}

	public function main()
	{
		$result = Pools::get(__CLASS__);
		if ($result == false)
		{
			$this->loadConf(); $this->loadCacheFile();
		    $result = new Cache(Pools::get("CACHE_CONF", true));
			Pools::set(__CLASS__, $result);
		}
		return $result;
	}
}