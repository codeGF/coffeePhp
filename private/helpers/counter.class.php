<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

/**
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
        $this->auto_->helpers->lock->k = $this->k;
    }
    
    /**
     * 计数器状态
     * @return bool [false 未锁, true 上锁]
     */
    public function in()
    {
        return $this->auto_->helpers->lock->in();
    }
    
    public function stus($key) //获取指定key计数
    {
        return $this->auto_->helpers->caches->main()->get(System::hash($this->k.$key));
    }
    
    public function add($key) //添加计数
    {
        if ($this->in() == false)
        {
            $this->auto_->helpers->lock->up(); //上锁
            $num = $this->auto_->helpers->caches->main()->get(System::hash($this->k.$key));
            if ($num == false)
            {
                $num = 0;
                $this->_stus = true;
                $this->auto_->helpers->caches->main()->set(System::hash($this->k.$key), ++$num, $this->date);
            }else
            {
                $this->_stus = true;
                if ($num > $this->nums) //大于阀值
                {
                    $this->_stus = false;
                }else
              {
                    $this->auto_->helpers->caches->main()->set(System::hash($this->k.$key), ++$num, $this->date);
                }
            }
            $this->auto_->helpers->lock->un(); //解锁
        }
        return $this->_stus;
    }
}