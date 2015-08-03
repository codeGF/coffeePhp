<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>
 * createTime: 2015/7/27 16:23
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