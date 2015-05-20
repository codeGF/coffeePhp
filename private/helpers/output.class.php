<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

/**
 * 输出参数
 * @author changguofeng
 *
 * @var code 状态码 （not null）
 * @var desc 状态说明（null）
 * @var data 数据参数（null）
 *
 * @method _json 输出json串
 * @method _xml 输出xml文本
 * @method _str 输出普通字符串
 */

class OutPut extends Base
{

    public function set($code = "", $desc = "", $data = "", $case="json")
    {
        $tmpcode = empty($code) || $code === false ? "error" : "success";
        if (is_array($desc))
        {
        	$desc = $tmpcode == "success" ? $desc[0] : $desc[1];
        }
        if (empty($code) || $code === false)
        {
        	$data = false;
        }
        if ($case == "json")
        {
            return $this->_json($tmpcode, $desc, $data);
        }else if ($case == "xml")
        {
            return $this->_xml($tmpcode, $desc, $data);
        }else if ($case == "str")
        {
            return $this->_str($tmpcode, $desc, $data);
        }
    }
    
    public function location($title, $url=null, $data=array())
    {
        $js = "alert('{$title}');";
        if ($url != null)
        {
            $js .= "window.location.href = {$url}&";
            if (!empty($data))
            {
                $js .= http_build_query($data);
            }
        }
        System::quit("<script type='text/javascript'>{$js}</script>");
        return;
    }

    private function _json($code, $desc, $data)
    {
        System::quit(json_encode(array("code" => $code, "desc" => $desc, "data" => $data)));
    }

    private function _xml($code, $desc, $data)
    {
        System::quit($this->auto_->helpers->xml->create(array("code"=>$code, "desc"=>$desc, "data"=>$data)));
    }

    private function _str($code, $desc, $data)
    {
        System::quit(implode("", array("code"=>$code, "desc"=>$desc, "data"=>$data)));
    }
}