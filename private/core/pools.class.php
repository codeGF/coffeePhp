<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Pools
{

	private static $_data = array();
	private static $_marked = "@";
	private static $_stu = false;

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
		$value = false; self::$_stu = false;
		$key  =self::_key($name);
		if (isset(self::$_data[$key]))
		{
			self::$_stu = true;
			$value = self::$_data[$key];
		}
		return $value;
	}

	public static function get($name, $error=false) //获取资源
	{
		$value = false; self::$_stu = false;
        if (strpos($name, self::$_marked) !== false)
        {
            $value = self::getStorage($name);
            if (self::$_stu == false)
            {
                $arr = explode(self::$_marked, $name);
                $tmp = self::getStorage($arr[0]);
                unset($arr[0]);
                foreach ($arr as $v)
                {
					if (isset($tmp[$v]) == false)
					{
						self::$_stu = false;
						$tmp = false;
						break;
					}else
					{
						$tmp = $tmp[$v];
						self::$_stu = true;
					}
                }
                $value = $tmp;
                if (self::$_stu == true)
                {
                    self::setStorage($name, $value);
                }
            }
        }else
        {
            $value = self::getStorage($name);
        }
		if (self::$_stu == false && $error == true)
		{
			System::error(11142, array(__CLASS__, $name));
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
		self::get($name);
		return self::$_stu;
	}

	public static function clear() //清除全部资源
	{
		self::$_data = array();
		return empty(self::$_data);
	}
}