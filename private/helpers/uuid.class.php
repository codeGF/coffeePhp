<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

class Uuid
{

	public function get($len = 6, $char = "")
	{
		$chars = $char ? $char : "ABCDEFGHIJKLMNOPQRSTUVWXY1234567890";
		mt_srand((double)microtime()*1000000*getmypid());
		$uuid = "";
		--$len;
		while (strlen($uuid) <= $len)
		{
			$uuid .= substr($chars, (mt_rand()%strlen($chars)), 1);
		}
		return $uuid;
	}
}