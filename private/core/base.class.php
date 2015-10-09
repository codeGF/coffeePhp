<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

abstract class Base
{

    public $auto = null;
    public $system = array();

    public function __construct()
    {
        $this->system = (object)array();
        $this->_auto();
    }

    final private function _auto()
    {
        if (Pools::get("base@construct") == false) {
            $this->system->date = Pools::get("SYSTEMCONF@SYSTEM_TIME", true);
            $this->system->time = Pools::get("SYSTEMCONF@SYSTEM_TIME", true);
            $this->system->encoding = Pools::get("SYSTEMCONF@SYSTEM_ENCODING", true);
            $this->auto = new Auto;
            Pools::set("base@construct@auto", $this->auto);
            Pools::set("base@construct@base_", $this->system);
            Pools::set("base@construct", true);
        } else {
            $this->auto = Pools::get("base@construct@auto", true);
            $this->system = Pools::get("base@construct@base_", true);
        }
    }

    public function __set($name, $value)
    {
        System::error(11120, $name);
    }

    public function __get($name)
    {
        System::error(11121, $name);
    }

    public function __call($name, $arguments)
    {
        System::error(11122, $name);
    }
}