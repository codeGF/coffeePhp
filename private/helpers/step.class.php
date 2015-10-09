<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 * 检测步骤
 */
class Step extends Base
{

    public $stepKey = "step";
    public $stepDate = 10;
    private $_stepData = null;
    private $_stus = true;

    private function _isDate()
    {
        $stus = true;
        if ($this->system->date <= $this->_stepData["date"]) {
            $stus = false;
        }
        return $stus;
    }

    private function _isAction($action)
    {
        $stus = true;
        if (strcasecmp($this->_stepData["action"], $action) == 0) {
            $stus = false;
        }
        return $stus;
    }

    private function _isIp()
    {
        $stus = true;
        if (strcasecmp($this->auto->helpers->ip->get(true), $this->_stepData["ip"]) == 0) {
            $stus = false;
        }
        return $stus;
    }

    public function is($action) //指定动作是否合法
    {
        $this->_stepDate = $this->auto->helpers->session->get($this->stepKey);
        $this->auto->helpers->session->delete($this->stepKey);
        if ($this->_stepDate != false) {
            if ($this->_isDate() == true) {
                $this->_stus = true;
            } else if ($this->_isAction($action) == true) {
                $this->_stus = true;
            } else if ($this->_isIp() == true) {
                $this->_stus = true;
            }
        }
        return $this->_stus;
    }

    public function set($action) //设置指定动作
    {
        $this->auto->helpers->session->delete($this->stepKey);
        $this->auto->helpers->session->set
        (
            $this->stepKey, array(
                "date" => bcadd($this->system->time, $this->stepDate),
                "action" => $action, "ip" => $this->auto->helpers->ip->get(true)
            )
        );
        return;
    }
}