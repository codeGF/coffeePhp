<?php


class Log
{

	public function set($filename, $str, $action='a+')
	{
		if (is_writable(ROOT))
		{
			if (!is_file(ServiceManager::get("SYSTEMCONF@APP_LOGS_PATH", true)))
			{
				mkdir(ServiceManager::get("SYSTEMCONF@APP_LOGS_PATH", true), 0777, TRUE);
			}
			error_log($str."\r\n", 3, sprintf("%s/%s", ServiceManager::get("SYSTEMCONF@APP_LOGS_PATH", true), trim($filename, '/')));
		}else
		{
			System::error(11131, ROOT);
		}
		return;
	}
}