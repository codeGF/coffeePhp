<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class ServiceManager
{

	private static $_data = array();
	private static $_marked = "@";

	private static function _key($name)
	{
		return md5($name);
	}

	private static function setStorage($name, $value) //设置存储
	{
		$key = self::_key($name);
		self::$_data[$key] = "";
		self::$_data[$key] = $value;
		return true;
	}

	private static function getStorage($name) //获取储存
	{
		$value = false;
		$key  =self::_key($name);
		if (isset(self::$_data[$key]))
		{
			$value = self::$_data[$key];
		}
		return $value;
	}

	public static function get($name) //获取资源
	{
		$value = false;
		if (strpos($name, self::$_marked) !== false)
		{
			$value = self::getStorage($name);
			if ($value == false)
			{
				$arr = explode(self::$_marked, $name);
				$tmp = self::getStorage($arr[0]);
				unset($arr[0]);
				foreach ($arr as $k)
				{
				    if (isset($tmp[$k]) == true)
				    {
				        $value = $tmp[$k];
				        break;
				    }
				}
				$value ? self::setStorage($name, $value) : $value = false;
			}
		}else
		{
			$value = self::getStorage($name);
		}
		return $value;
	}

	public static function set($name, $value) //注册资源
	{
		if (is_array($name))
		{
			foreach ($name as $key => $v)
			{
				self::setStorage($v, is_array($value) ? $value[$key] : $value);
			}
		}else
		{
			self::setStorage($name, $value);
		}
		return true;
	}

	public static function isExists($name) //查找资源是否存在
	{
		$result = self::get($name);
		return !empty($result);
	}

	public static function clear() //清除全部资源
	{
		self::$_data = "";
		return empty(self::$_data);
	}
}