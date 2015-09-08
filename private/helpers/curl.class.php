<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Curl extends Base
{

    private $_ch = null;
    private $_url = null;
    public  $proxy = null; //需要将请求发送那台服务器，为IP
    public  $timeout = 30; //设置cURL允许执行的最长秒数
    public  $connecttimeout = 10; //在发起连接前等待的时间(秒)，如果设置为0，则无限等待
    public  $httpHead = false; //其他头信息，如果有将压入现有的头信息中
    public  $referer = null; //请求来路url地址

    public function __construct()
    {
    	parent::__construct();
    	$this->_ch = curl_init();
    }

    public function __destruct()
    {
    	$this->_clear();
    }

    private function _encoding($data, $in_encoding="", $out_encoding="")
    {
        if ($in_encoding && $out_encoding)
        {
            $data = $this->auto_->helpers->str->array_iconv($data, $in_encoding, $out_encoding);
        }
        return $data;
    }

    private function _clear()
    {
    	$this->proxy = null;
    	$this->referer = null;
    	$this->connecttimeout = 10;
    	$this->timeout = 30;
        $this->httpHead = array();
    	$this->_url = null;
    	curl_close($this->_ch);
    	$this->_ch = null;
    }

    private function _curl_setpot()
    {
        curl_setopt($this->_ch, CURLOPT_URL, str_replace(" ", "", $this->_url));
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->_ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($this->_ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($this->_ch, CURLOPT_HEADER, false);
        curl_setopt($this->_ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->_ch, CURLOPT_SSLVERSION, 3);
        if ($this->proxy) curl_setopt($this->_ch, CURLOPT_PROXY, $this->proxy);
        if ($this->referer) curl_setopt($this->_ch, CURLOPT_REFERER, $this->referer);
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $this->_httpHeader());
    }

    public function fileGetContents($url, $in_encoding="", $out_encoding="")
    {
        $this->_url = $this->_encoding($url, $in_encoding, $out_encoding);
        $this->_curl_setpot();
        return $this->results();
    }

    public function post($url, $data, $in_encoding="", $out_encoding="")
    {
        $data = $this->_encoding($data, $in_encoding, $out_encoding);
        $this->_url = $url;
        curl_setopt($this->_ch, CURLOPT_POST, true);
        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $data);
        $this->_curl_setpot();
        return $this->results();
    }

    public function get($url, $data, $in_encoding="", $out_encoding="")
    {
        $data = $this->_encoding($data, $in_encoding, $out_encoding);
        $this->_url = sprintf("%s?%s", trim($url, "?"), http_build_query($data));
        $this->_curl_setpot();
        return $this->results();
    }

    //输出结果
    private function results()
    {
    	$results = array();
        $results["data"] = curl_exec($this->_ch); //获取内容
        $results["error"] = curl_error($this->_ch); //错误提示
        $results["errno"] = curl_errno($this->_ch); //错误代码
        $results["info"] = curl_getinfo($this->_ch); //获取一个cURL连接资源句柄的信息
        $results["runtime"] = $this->_isTimeOut($results["info"]["connect_time"], $results["info"]["total_time"]);
        $results["errorPrize"] = $this->curlerrno($results["errno"]); //curl错误内部提示
        return $results;
    }

    //检测等待连接超时还是传输超时
    private function _isTimeOut($connectTime, $totalTime)
    {
        $result = "";
        if ($connectTime > $this->connecttimeout)
        {
            $result = "CONNECTTIMEOUT";
        }else if ($totalTime > $this->timeout)
        {
            $result = "TIMEOUT";
        }else
       {
            $result = $totalTime;
        }
        return $result;
    }

    private function _httpHeader()
    {
    	$head = array(
    			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
    			"Accept-Language: zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3",
    			"Cache-Control: no-cache",
    			"Connection: Close",
    			"User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0"
    	);
    	if ($this->httpHead != false)
    	{
    		$head = array_merge($head, (array)$this->httpHead);
    	}
    	return $head;
    }

    public function curlerrno($k)
    {
        $error_codes = array(
            1 => "CURLE_UNSUPPORTED_PROTOCOL",
            2 => "CURLE_FAILED_INIT",
            3 => "CURLE_URL_MALFORMAT",
            4 => "CURLE_URL_MALFORMAT_USER",
            5 => "CURLE_COULDNT_RESOLVE_PROXY",
            6 => "CURLE_COULDNT_RESOLVE_HOST",
            7 => "CURLE_COULDNT_CONNECT",
            8 => "CURLE_FTP_WEIRD_SERVER_REPLY",
            9 => "CURLE_REMOTE_ACCESS_DENIED",
            11 => "CURLE_FTP_WEIRD_PASS_REPLY",
            13 => "CURLE_FTP_WEIRD_PASV_REPLY",
            14 => "CURLE_FTP_WEIRD_227_FORMAT",
            15 => "CURLE_FTP_CANT_GET_HOST",
            17 => "CURLE_FTP_COULDNT_SET_TYPE",
            18 => "CURLE_PARTIAL_FILE",
            19 => "CURLE_FTP_COULDNT_RETR_FILE",
            21 => "CURLE_QUOTE_ERROR",
            22 => "CURLE_HTTP_RETURNED_ERROR",
            23 => "CURLE_WRITE_ERROR",
            25 => "CURLE_UPLOAD_FAILED",
            26 => "CURLE_READ_ERROR",
            27 => "CURLE_OUT_OF_MEMORY",
            28 => "CURLE_OPERATION_TIMEDOUT",
            30 => "CURLE_FTP_PORT_FAILED",
            31 => "CURLE_FTP_COULDNT_USE_REST",
            33 => "CURLE_RANGE_ERROR",
            34 => "CURLE_HTTP_POST_ERROR",
            35 => "CURLE_SSL_CONNECT_ERROR",
            36 => "CURLE_BAD_DOWNLOAD_RESUME",
            37 => "CURLE_FILE_COULDNT_READ_FILE",
            38 => "CURLE_LDAP_CANNOT_BIND",
            39 => "CURLE_LDAP_SEARCH_FAILED",
            41 => "CURLE_FUNCTION_NOT_FOUND",
            42 => "CURLE_ABORTED_BY_CALLBACK",
            43 => "CURLE_BAD_FUNCTION_ARGUMENT",
            45 => "CURLE_INTERFACE_FAILED",
            47 => "CURLE_TOO_MANY_REDIRECTS",
            48 => "CURLE_UNKNOWN_TELNET_OPTION",
            49 => "CURLE_TELNET_OPTION_SYNTAX",
            51 => "CURLE_PEER_FAILED_VERIFICATION",
            52 => "CURLE_GOT_NOTHING",
            53 => "CURLE_SSL_ENGINE_NOTFOUND",
            54 => "CURLE_SSL_ENGINE_SETFAILED",
            55 => "CURLE_SEND_ERROR",
            56 => "CURLE_RECV_ERROR",
            58 => "CURLE_SSL_CERTPROBLEM",
            59 => "CURLE_SSL_CIPHER",
            60 => "CURLE_SSL_CACERT",
            61 => "CURLE_BAD_CONTENT_ENCODING",
            62 => "CURLE_LDAP_INVALID_URL",
            63 => "CURLE_FILESIZE_EXCEEDED",
            64 => "CURLE_USE_SSL_FAILED",
            65 => "CURLE_SEND_FAIL_REWIND",
            66 => "CURLE_SSL_ENGINE_INITFAILED",
            67 => "CURLE_LOGIN_DENIED",
            68 => "CURLE_TFTP_NOTFOUND",
            69 => "CURLE_TFTP_PERM",
            70 => "CURLE_REMOTE_DISK_FULL",
            71 => "CURLE_TFTP_ILLEGAL",
            72 => "CURLE_TFTP_UNKNOWNID",
            73 => "CURLE_REMOTE_FILE_EXISTS",
            74 => "CURLE_TFTP_NOSUCHUSER",
            75 => "CURLE_CONV_FAILED",
            76 => "CURLE_CONV_REQD",
            77 => "CURLE_SSL_CACERT_BADFILE",
            78 => "CURLE_REMOTE_FILE_NOT_FOUND",
            79 => "CURLE_SSH",
            80 => "CURLE_SSL_SHUTDOWN_FAILED",
            81 => "CURLE_AGAIN",
            82 => "CURLE_SSL_CRL_BADFILE",
            83 => "CURLE_SSL_ISSUER_ERROR",
            84 => "CURLE_FTP_PRET_FAILED",
            84 => "CURLE_FTP_PRET_FAILED",
            85 => "CURLE_RTSP_CSEQ_ERROR",
            86 => "CURLE_RTSP_SESSION_ERROR",
            87 => "CURLE_FTP_BAD_FILE_LIST",
            88 => "CURLE_CHUNK_FAILED",
            89 => "CURLE_NO_CONNECTION_AVAILABLE",
        );
        return isset($error_codes[$k]) ? $error_codes[$k] : "CURLE_ERR_NO:{$k}";
    }
}
