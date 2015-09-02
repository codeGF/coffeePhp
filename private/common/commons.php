<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

function import($name) //加载import类 import("xxx/xxx.php")
{
    return require_cache(sprintf("%s/%s", ServiceManager::get("SYSTEMCONF@SYSTEM_IMPORT_PATH"), $name));
}

function helpers($name) //加载助手类 helpers("xxx.php")
{
    return require_cache(sprintf("%s/%s", ServiceManager::get("SYSTEMCONF@SYSTEM_HELPERS_PATH"), $name));
}

function require_cache($filename) //防止重复加载文件
{
	$result = ServiceManager::get($filename);
	if ($result == false)
	{
		if (file_exists($filename))
		{
			$result = require($filename);
			ServiceManager::set($filename, $result);
		}else
		{
		    throw new PrivateException(array("message"=> $filename, "code"=> 11128));
		    die;
        }
	}
    return $result;
}

function Exception(Exception $e)
{
	if ($e instanceof PrivateException)
    {
		$e->show ();
		die;
	}
}

function handleError($errorNo, $message, $filename, $lineNo) {
	$type = "[ERROR]";
	switch ($errorNo)
	{
		case 2 :
			$type = "[E_WARNING]";
			break;
		case 8 :
			$type = "[E_NOTICE]";
			break;
	}
	throw new PrivateException(array("message"=> sprintf("%s in file %s (line: %s) %s", $type, $filename, $lineNo, $message), "code"=> ""));
	die;
}

set_exception_handler('exception'); //错误调用函数
set_error_handler('handleError', E_ALL); //错误时，调用框架指定错误函数