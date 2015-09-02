<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Ft extends Base
{

	//过滤函数
	public function get($value, $leng=false)
	{
		$value = preg_replace("/'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)/is", "", $value);
		$value = preg_replace("/\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)/is", "", $value);
		$value = preg_replace("/ |;|\\r|\\n|\"|\\\|%0a%0d|%0a|%0d|\t|\r\n|%20|%27|%2527/is", "", $value);
		return $leng != false ? $this->auto_->helpers->str->msubstr($value, (int)$leng, "") : $value;
	}
}