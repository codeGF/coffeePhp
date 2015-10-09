<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */
class Request extends Base
{

    public function __get($name)
    {
        global $$name;
        $$name = false;
        if (isset($$name) == false) {
            $value = $this->auto->helpers->postget->$name;
            if (strlen($value) > 0) {
                $$name = $value;
            }
        }
        return $$name;
    }
}