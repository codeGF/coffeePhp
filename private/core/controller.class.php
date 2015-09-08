<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

abstract class Controller extends Base
{

    protected $view_ = array();
    protected $layout_ = array();

    public function __construct()
    {
        parent::__construct();
        $this->view_ = (object)array();
        $this->layout_ = (object)array();
    }

    final private function _sendHeader()
    {
        if (defined("SEND_HEADER") && SEND_HEADER == true)
        {
            System::header
            (
                array
                (
                    "Content-Type: text/html;charset=".$this->system_->encoding,
                    "Content-Language: zh-CN",
                    "Date: ".sprintf("%s GMT", gmdate('D, d M Y H:i:s', bcadd($this->system_->date, 900))),
                    "Server: Tomcat/1.0.1",
                    "X-Powered-By: PHP/7.0",
                    "X-XSS-Protection: 1; mode=block",
                    "X-Content-Encoded-By: :)",
                    "Vary:Accept-Encoding"
                )
            );
        }
        return;
    }
    
    final protected function layout_($file)
    {
        $view = sprintf("%s/%s%s", Pools::get("SYSTEMCONF@APP_LAYOUT_PATH", true), $file, Pools::get("SYSTEMCONF@APP_DISPLAY_NAME", true));
        if (file_exists($view) == true)
        {
            extract((array)$this->layout_);
            require $view;
        }else
        {
            System::error(11119, $view);
        }
        return;
    }

	final protected function display_($name="")
	{
		$viewfile = sprintf
        (
            "%s/%s/%s%s",
			Pools::get("SYSTEMCONF@APP_VIEW_PATH", true),
			Pools::get("router@appController", true),
			empty($name) ?  Pools::get("router@appFunction", true) : $name,
			Pools::get("SYSTEMCONF@APP_DISPLAY_NAME", true)
		);
		if (file_exists($viewfile))
		{
			extract((array)$this->view_);
            $this->_sendHeader();
			require $viewfile;
		}else
		{
			System::error(11119, $viewfile);
		}
		return;
	}
}