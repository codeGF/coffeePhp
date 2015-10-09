<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 * 锁
 * @method k 锁唯一标示
 * @method date 锁有效时间
 * @return bool
 */

class Lock extends Base
{

	public $k = "lock";
	public $date = 600;
	private $_stus = false;
	
	public function un() //解除锁
	{
	    $this->_stus = $this->auto_->helpers->caches->main()->delete($this->k);
	    return $this->_stus;
	}

	public function up() //上锁
	{
		$this->auto_->helpers->caches->main()->delete($this->k);
		$this->_stus = $this->auto_->helpers->caches->main()->set
		(
				$this->k, $this->system_->date, $this->date
		);
		return $this->_stus;
	}

	public function in() //锁状态
	{
		$data = $this->auto_->helpers->caches->main()->get($this->k);
		if ($data != false)
		{
			if (($this->system_->date - $data) <= $this->date)
			{
				$this->_stus = true;
			}
		}
		return $this->_stus;
	}
}