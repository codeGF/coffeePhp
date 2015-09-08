<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

class Display
{

	public $view = "";

	public function __construct()
	{
		$this->view = (object)array();
	}

	public function __destruct()
	{
		unset($this->view);
	}

	public function main($name)
	{
		$viewfile = sprintf
		(
				"%s/%s%s",
				Pools::get("SYSTEMCONF@APP_VIEW_PATH", true),
				$name,
				Pools::get("SYSTEMCONF@SYSTEM_DISPLAY_TEMPLATES", true)
		);
		if (file_exists($viewfile))
		{
			extract((array)$this->view);
			require $viewfile;
		}else
       {
			System::error(11119, $viewfile);
		}
		return;
	}

	public function tmpView($file)
	{
		$file = sprintf("%s/%s", Pools::get("SYSTEMCONF@SYSTEM_DISPLAY_PATH", true), $file);
		return require_cache($file);
	}
}