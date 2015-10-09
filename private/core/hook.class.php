<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Hook extends Base
{

    private $_hookConf = array();
    private $_function = null;
    public $excision = array(".", "@");

    public function __construct($conf)
    {
        parent::__construct();
        $this->_hookConf = is_array($conf) == false ? (array)$conf : $conf;
        $this->_function = Pools::get("router@appFunction", true);
        $this->_construct();
        $this->_function();
    }

    private function _eval($str)
    {
        $data = array(); $tmp = null;
        preg_match("/\[.*\]/", $str, $data);
        $str = sprintf("\$this->%s", str_replace($this->excision, "->", preg_replace("/\[.*\]/", "", $str)));
        if ($data != false) {
            $data = explode(",", str_replace(array("[", "]"), "", $data[0]));
            foreach ($data as $v) {
                $tmp .= sprintf("'%s',", $v);
            }
            $tmp = trim($tmp, ",");
        }
        eval("$str($tmp);");
    }

    private function _construct()
    {
        if (empty($this->_hookConf[0]) == false) {
            if (is_array($this->_hookConf[0]) == true) {
                foreach ($this->_hookConf[0] as $fun) {
                    $this->_eval($fun);
                }
            } else {
                $this->_eval($this->_hookConf[0]);
            }
        }
    }

    private function _function()
    {
        if (empty($this->_hookConf[$this->_function]) == false) {
            if (is_array($this->_hookConf[$this->_function])) {
                foreach ($this->_hookConf[$this->_function] as $v) {
                    $this->_eval($v);
                }
            } else {
                $this->_eval($this->_hookConf[$this->_function]);
            }
        }
    }
}