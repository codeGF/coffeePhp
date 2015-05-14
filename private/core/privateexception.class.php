<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class PrivateException extends Exception
{

    protected $description_;
    private $_errorCnt = null;
    private $_errorHtml = null;

    public function __construct($array = "", $description = "")
    {
        parent::__construct($this->_getErrorMessage($array), 0);
        $this->description = $description;
        if ($this->_errorCnt == null)
        {
            $this->_errorCnt = file_get_contents(sprintf("%s/system/error.html", ServiceManager::get("SYSTEMCONF@SYSTEM_DISPLAY_PATH")));
        }
    }

    private function _getErrorMessage($array)
    {
        if (is_array($array) == true)
        {
            $error = ServiceManager::get("ERRORCODE@{$array["code"]}");
            if ($error)
            {
                return str_replace('{e}', $array["message"], $error);
            }else
            {
                return $array["message"];
            }
        }else
       {
            return $array;
        }
    }

    public function getDescription()
    {
        return $this->description_;
    }

    public function __getTraceAsString()
    {
        return $this->getTraceAsString();
    }

    public function show()
    {
        $this->errorSystem_();
        if (ServiceManager::get("SYSTEMCONF@SYSTEM_ERROR_PROMPT") == true)
        {
            $this->errorSystem_();
        }else
       {
            $this->toEmail();
            $this->errorFriendly_();
        }
        die($this->_errorHtml);
    }

    protected function errorSystem_()
    {
        $list1 = null; $list2 = null;
        $list1 .= "<li>".htmlspecialchars($this->getMessage())."</li>";
        $list1 .= "<li>Server PHP Version: ".htmlspecialchars(phpversion())."</li>";
        $list1 .= "<li>Error Date: ".date("Y-m-d H:i:s")."</li>";
        if (isset($_SERVER ['REQUEST_URI']))
        {
            $list1 .= "<li>Request Uri: ".htmlspecialchars($_SERVER ['REQUEST_URI'])."</li>";
        }
        if (isset($_SERVER ['HTTP_REFERER']))
        {
            $list1 .= "<li>Http Referer: ".htmlspecialchars($_SERVER ['HTTP_REFERER'])."</li>";
        }
        $arr = explode("#", trim(htmlspecialchars($this->__getTraceAsString()), "#"));
        foreach ($arr as $v)
        {
            $list2 .= "<li>{$v}</li>";
        }
        $this->_errorHtml = str_replace(array("{{list1}}", "{{list2}}"), array($list1, $list2), $this->_errorCnt);
        return;
    }

    private function toEmail()
    {
        if (ServiceManager::get("SYSTEM_RUN_ERROR_EMAIL") == false)
        {
            include sprintf("%s/emailer/emailer.class.php", ServiceManager::get("SYSTEMCONF@SYSTEM_IMPORT_PATH"));
            ServiceManager::set("SYSTEM_RUN_ERROR_EMAIL", true);
        }
        $emailObj = new Emailer((array)ServiceManager::get("SYSTEMCONF@SYSTEM_ERROR_TO_EMAIL"), array("SYSTEM_RUN_ERROR", $this->_errorHtml), true);
        $emailObj->email();
        return;
    }

    protected function errorFriendly_()
    {
        $this->_errorHtml = str_replace(array("{{list1}}", "{{list2}}"), array("<li>Error Date: ".date("Y-m-d H:i:s")."</li>", ":("), $this->_errorCnt);
        return;
    }
}