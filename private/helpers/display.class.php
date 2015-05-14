<?php


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
				ServiceManager::get("SYSTEMCONF@APP_VIEW_PATH"),
				$name,
				ServiceManager::get("SYSTEMCONF@SYSTEM_DISPLAY_TEMPLATES")
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
		$file = sprintf("%s/%s", ServiceManager::get("SYSTEMCONF@SYSTEM_DISPLAY_PATH"), $file);
		return require_cache($file);
	}
}