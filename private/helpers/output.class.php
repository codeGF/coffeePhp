<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 * 输出参数
 * @var code 状态码 （not null）
 * @var desc 状态说明（null）
 * @var data 数据参数（null）
 * @method _json 输出json串
 * @method _xml 输出xml文本
 * @method _str 输出普通字符串
 */
class OutPut extends Base
{

    public function set($code = "", $desc = "", $data = "", $case = "json")
    {
        $tmpcode = empty($code) || $code === false ? "error" : "success";
        if (is_array($desc)) {
            $desc = $tmpcode == "success" ? $desc[0] : $desc[1];
        }
        if (empty($code) || $code === false) {
            $data = false;
        }
        if ($case == "json") {
            return $this->_json($tmpcode, $desc, $data);
        } else if ($case == "xml") {
            return $this->_xml($tmpcode, $desc, $data);
        } else if ($case == "str") {
            return $this->_str($tmpcode, $desc, $data);
        } else if ($case == "serialize") {
            return $this->_serialize($tmpcode, $desc, $data);
        }
        return;
    }

    public function location($title, $url = null, $data = array())
    {
        $js = "alert('{$title}');";
        if ($url != null) {
            $js .= "window.location.href = {$url}&";
            if (!empty($data)) {
                $js .= http_build_query($data);
            }
        }
        System::quit("<script type='text/javascript'>{$js}</script>");
        return;
    }

    private function _json($code, $desc, $data)
    {
        System::header("Content-Type: application/json; charset=" . $this->system->encoding);
        System::quit(json_encode(array("code" => $code, "desc" => $desc, "data" => $data)));
    }

    private function _xml($code, $desc, $data)
    {
        System::header("Content-Type: text/xml;charset=" . $this->system->encoding);
        System::quit($this->auto->helpers->xml->create(array("code" => $code, "desc" => $desc, "data" => $data)));
    }

    private function _str($code, $desc, $data)
    {
        System::quit(implode("", array("code" => $code, "desc" => $desc, "data" => $data)));
    }

    private function _serialize($code, $desc, $data)
    {
        System::quit(serialize(array("code" => $code, "desc" => $desc, "data" => $data)));
    }
}