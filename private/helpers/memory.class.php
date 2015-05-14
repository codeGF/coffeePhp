<?php


class Memory
{

	private static $_m = "";

	public function start()
	{
		self::$_m = memory_get_usage();
		return;
	}

	public function end()
	{
		$systemMemoryLimit = preg_replace("/[^0-9]/", "", ServiceManager::get("SYSTEMCONF@SYSTEM_MEMORY_LIMIT"));
		$t = sprintf("%01.2f", (memory_get_usage()-self::$_m)/1024/1024);
		if ($t > $systemMemoryLimit)
		{
			printf(ServiceManager::get("ERRORCODE@11129"), $systemMemoryLimit);
		}else
       {
			printf(ServiceManager::get("ERRORCODE@11130"), $t);
		}
		return;
	}
}