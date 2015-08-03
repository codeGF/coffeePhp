<?php


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
        $this->_sendHeader();
    }

    final private function _sendHeader() //ͷ��Ϣ����
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
        $view = sprintf("%s/%s%s", ServiceManager::get("SYSTEMCONF@APP_LAYOUT_PATH"), $file, ServiceManager::get("SYSTEMCONF@APP_DISPLAY_NAME"));
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
			ServiceManager::get("SYSTEMCONF@APP_VIEW_PATH"),
			ServiceManager::get("router@appController"),
			empty($name) ?  ServiceManager::get("router@appFunction") : $name,
			ServiceManager::get("SYSTEMCONF@APP_DISPLAY_NAME")
		);
		if (file_exists($viewfile))
		{
			extract((array)$this->view_);
			require $viewfile;
		}else
		{
			System::error(11119, $viewfile);
		}
		return;
	}
}