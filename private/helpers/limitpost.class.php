<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

/**
 * 防止重复操作
 * @author changguofeng <281441619@qq.com>
 * @access public
 * @return ok || no , true为可操作，false为不可操作
 * @name expires 默认2分钟内为false，可依据情况作出修改，值为秒
 */

class LimitPost extends Base
{

	public  $key = "limit@post@time";
	public  $expires = 120;
	private $_result = false;

	public function is()
	{
		$this->key = System::hash($this->key);
		$time = $this->auto_->helpers->session->get($this->key);
		if (empty($time))
		{
			$this->auto_->helpers->session->set($this->key, ServiceManager::get("SYSTEMCONF@SYSTEM_TIME", true));
			$this->_results = true;
		}else
		{
			$newtime = ServiceManager::get("SYSTEMCONF@SYSTEM_TIME", true);
			$oldtime = $this->auto_->helpers->session->get($this->key);
			if (($newtime - $oldtime) > $this->expires)
			{
				$this->auto_->helpers->session->delete($this->key);
				$this->_results = true;
			}
		}
		return $this->_result;
	}
}