<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class PostGet extends Base
{

    private $_post = array();
    private $_get = array();
    private $_results = false;

    public function __construct()
    {
        parent::__construct();
        $this->_post = $_POST;
        $this->_get = $_GET;
    }

    public function getData($name)
    {
        $this->_results = "";
        if ($name != "post" && $name != "get") {
            if (isset($this->_post["$name"])) {
                $this->_results = $this->_post["$name"];
            } else if (isset($this->_get["$name"])) {
                $this->_results = $this->_get["$name"];
            }
        } else if ($name == "post") {
            $this->_results = $this->_post;
        } else if ($name == "get") {
            $this->_results = $this->_get;
        }
        return $this->ft();
    }

    public function __get($name)
    {
        static $ky = array();
        if (!isset($ky[$name])) {
            return $ky[$name] = $this->getData($name);
        }
        return $ky[$name];
    }

    public function ft()
    {
        if (is_array($this->_results)) {
            return $this->_results;
        }
        return $this->auto->helpers->ft->get($this->_results);
    }
}