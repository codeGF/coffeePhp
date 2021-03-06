<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 * 原子计数器
 * @param k 唯一标示
 * @param nums 计数总数阀值
 * @param date 数据有效时间
 * @param _stus 操作结果
 * @return bool
 */
class Counter extends Base
{

    public $k = __CLASS__;
    public $nums = 100;
    public $date = 86400;
    private $_stus = false;

    public function __construct()
    {
        parent::__construct();
        $this->_stus = false;
        $this->auto->helpers->lock->k = $this->k;
    }

    /**
     * 计数器状态
     * @return bool [false 未锁, true 上锁]
     */
    public function in()
    {
        return $this->auto->helpers->lock->in();
    }

    public function stus($key) //获取指定key计数
    {
        return $this->auto->helpers->caches->main()->get(System::hash("{$this->k}{$key}"));
    }

    public function add($key) //添加计数
    {
        if ($this->in() == false) {
            $this->auto->helpers->lock->up(); //上锁
            $num = $this->auto->helpers->caches->main()->get(System::hash("{$this->k}{$key}"));
            if ($num == false) {
                $num = 0;
                $this->_stus = true;
                $this->auto->helpers->caches->main()->set(System::hash("{$this->k}{$key}"), ++$num, $this->date);
            } else {
                $this->_stus = true;
                if ($num > $this->nums) //大于阀值
                {
                    $this->_stus = false;
                } else {
                    $this->auto->helpers->caches->main()->set(System::hash("{$this->k}{$key}"), ++$num, $this->date);
                }
            }
            $this->auto->helpers->lock->un(); //解锁
        }
        return $this->_stus;
    }
}