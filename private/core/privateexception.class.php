<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */

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
        if ($this->_errorCnt == null) {
            $this->_errorCnt = file_get_contents(sprintf("%s/system/error.html", Pools::get("SYSTEMCONF@SYSTEM_DISPLAY_PATH", true)));
        }
    }

    private function _getErrorMessage($array)
    {
        if (is_array($array) == true) {
            if (empty($array["code"]) == false) {
                $array["code"] = trim($array["code"], "\r\n");
                if (is_numeric($array["code"]) == true) {
                    $codeMessage = Pools::get("ERRORCODE@{$array["code"]}", true);
                    if (is_array($array["message"]) == true) {
                        foreach ($array["message"] as $k => $v) {
                            $codeMessage = str_replace("{{$k}}", $v, $codeMessage);
                        }
                        return $codeMessage;
                    }
                    return str_replace('{0}', $array["message"], $codeMessage);
                } else {
                    return $array["code"];
                }
            } else {
                $array["message"] = trim($array["message"]);
                return $array["message"];
            }
        } else {
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
        $this->errorMsg_();
        if (Pools::get("SYSTEMCONF@SYSTEM_ERROR_PROMPT", true) == false) {
            $this->toEmail();
            $this->errorFriendly_();
        }
        System::quit($this->_errorHtml);
    }

    protected function errorMsg_()
    {
        $list1 = null;
        $list2 = null;
        $list1 .= "<li>" . htmlspecialchars($this->getMessage()) . "</li>";
        $list1 .= "<li>Server PHP Version: " . htmlspecialchars(phpversion()) . "</li>";
        $list1 .= "<li>Error Date: " . date("Y-m-d H:i:s") . "</li>";
        if (isset($_SERVER ['REQUEST_URI'])) {
            $list1 .= "<li>Request Uri: " . htmlspecialchars($_SERVER ['REQUEST_URI']) . "</li>";
        }
        if (isset($_SERVER ['HTTP_REFERER'])) {
            $list1 .= "<li>Http Referer: " . htmlspecialchars($_SERVER ['HTTP_REFERER']) . "</li>";
        }
        $arr = explode("#", trim(htmlspecialchars($this->__getTraceAsString()), "#"));
        foreach ($arr as $v) {
            $list2 .= "<li>{$v}</li>";
        }
        $this->_errorHtml = str_replace(array("{{list1}}", "{{list2}}"), array($list1, $list2), $this->_errorCnt);
    }

    private function toEmail()
    {
        require_cache(sprintf("%s/emailer/emailer.class.php", Pools::get("SYSTEMCONF@SYSTEM_IMPORT_PATH", true)));
        $emailObj = new Emailer((array)Pools::get("SYSTEMCONF@SYSTEM_ERROR_TO_EMAIL", true), array("SYSTEM_RUN_ERROR", $this->_errorHtml), true);
        $emailObj->email();
    }

    protected function errorFriendly_()
    {
        $this->_errorHtml = str_replace(array("{{list1}}", "{{list2}}"), array("<li>Error Date: " . date("Y-m-d H:i:s") . "</li>", ":("), $this->_errorCnt);
    }

    public function getErrorType($type) //错误提示
    {
        switch ($type) {
            case E_ERROR: // 1 //
                return 'E_ERROR';
            case E_WARNING: // 2 //
                return 'E_WARNING';
            case E_PARSE: // 4 //
                return 'E_PARSE';
            case E_NOTICE: // 8 //
                return 'E_NOTICE';
            case E_CORE_ERROR: // 16 //
                return 'E_CORE_ERROR';
            case E_CORE_WARNING: // 32 //
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR: // 64 //
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING: // 128 //
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR: // 256 //
                return 'E_USER_ERROR';
            case E_USER_WARNING: // 512 //
                return 'E_USER_WARNING';
            case E_USER_NOTICE: // 1024 //
                return 'E_USER_NOTICE';
            case E_STRICT: // 2048 //
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR: // 4096 //
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED: // 8192 //
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED: // 16384 //
                return 'E_USER_DEPRECATED';
        }
        return "[ERROR]";
    }
}