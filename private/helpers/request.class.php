<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

class Request extends Base
{

    public function __get($name)
    {
        global $$name;
        $$name = false;
        if (isset($$name) == false)
        {
            $value = $this->auto_->helpers->postget->$name;
            if (strlen($value) > 0)
            {
                $$name = $value;
            }
        }
        return $$name;
    }
}