<?php


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