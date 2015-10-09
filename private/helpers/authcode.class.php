<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 * @var  $string  明文字符串
 * @var  encide:加密, decode:解密
 * @var  $expiry  密码有效时间
 * @var  $key     密匙
 * @var  $cipher  加密类型(默认MCRYPT_DES)，可自由切换
 * @var  $modes   密码模式(默认MCRYPT_MODE_ECB)，可自由切换
 */
class Authcode
{

    public $key = "authcode";
    public $expiry = 0;
    private $_cipher = MCRYPT_RIJNDAEL_128;
    private $_modes = MCRYPT_MODE_ECB;
    private $_score = MCRYPT_RAND;

    private $_iv = null;
    private $_size = null;

    public function __construct()
    {
        //初始化向量
        $this->_size = mcrypt_get_iv_size($this->_cipher, $this->_modes);
        $this->_iv = mcrypt_create_iv($this->_size, $this->_score);
    }

    //替换base64特殊字符
    private function _charconen($char)
    {
        $char = str_replace(array('+', '/'), array('-', '_'), $char);
        return $char;
    }

    //转换base64特殊字符
    private function _charconde($char)
    {
        $char = str_replace(array('-', '_'), array('+', '/'), $char);
        return $char;
    }

    //加密
    public function en($string)
    {
        $string = trim(sprintf("%s-%d-%d", $string, ceil(bcadd(Pools::get("SYSTEMCONF@SYSTEM_TIME", true), $this->expiry)), $this->expiry));
        $char = mcrypt_encrypt($this->_cipher, $this->key, $string, $this->_modes, $this->_iv);
        $char = base64_encode($char);
        $result = $this->_charconen($char);
        return $result;
    }

    //解密
    public function de($string)
    {
        $result = false;
        $string = $this->_charconde($string);
        $string = base64_decode($string, true);
        $char = mcrypt_decrypt($this->_cipher, $this->key, trim($string), $this->_modes, $this->_iv);
        $tmp = explode("-", $char);
        if (isset($tmp[2]) && isset($tmp[1]) && isset($tmp[0])) {
            $result = $tmp[0];
            if ($tmp["2"] != 0 && Pools::get("SYSTEMCONF@SYSTEM_TIME", true) > $tmp["1"]) {
                $result = false;
            }
        }
        return $result;
    }
}