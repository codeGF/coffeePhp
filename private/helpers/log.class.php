<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

class Log
{

	public function set($filename, $str, $action='a+')
	{
		if (is_writable(ROOT))
		{
			if (!is_file(Pools::get("SYSTEMCONF@APP_LOGS_PATH", true)))
			{
				mkdir(Pools::get("SYSTEMCONF@APP_LOGS_PATH", true), 0777, TRUE);
			}
			error_log($str."\r\n", 3, sprintf("%s/%s", Pools::get("SYSTEMCONF@APP_LOGS_PATH", true), trim($filename, '/')));
		}else
		{
			System::error(11131, ROOT);
		}
		return;
	}
}