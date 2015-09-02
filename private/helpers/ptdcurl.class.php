<?php


class PtdCurl
{

    public $proxy = ""; //代理服务器
    private $_responses = array();
    private $_queue, $_ch, $_url;
    public  $timeout = 30; //设置cURL允许执行的最长秒数
    public  $connecttimeout = 10; //在发起连接前等待的时间(秒)，如果设置为0，则无限等待

    public function __construct()
    {
        $this->_queue = curl_multi_init();
    }

    /**
     * @param $url array("xxxx", "xxxxx" ...)
     */
    public function fileGetContents(array $url)
    {
        foreach ($url as $value)
        {
            $this->_url = $value;
            $this->_init();
        }
        return $this->_results();
    }

    /**
     * @param $data array(
           array("url"=>"xxxxxx", "data"=>array("xxx"=>"xxx"...))
           ...
       )
     */
    public function post(array $data)
    {
        foreach ($data as $value)
        {
            $this->_url = $value["url"];
            $this->_init();
            curl_setopt($this->_ch, CURLOPT_POST, true);
            curl_setopt($this->_ch, CURLOPT_POSTFIELDS, http_build_query($value["data"]));
        }
        return $this->_results();
    }

    /**
     * @param array $data array(
           array("url"=>"xxx", "data"=>array("xxx"=>"xxx"...))
           ...
       )
     */
    public function get(array $data)
    {
        foreach ($data as $value)
        {
            $this->_url = sprintf("%s?%s", trim($value["url"], "?"), http_build_query($value["data"]));
            $this->_init();
            curl_setopt($this->_ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->_ch, CURLOPT_SSL_VERIFYHOST, 2);
        }
        return $this->_results();
    }

    /**
     * 当为get请求的时候，空格会造成目标机解析HTTP协议错误，会返回HTPP505
     * 当前：
     *     清除空格
     */
    private function _formatUrl()
    {
        $this->_url = str_replace(array(" "), "", $this->_url);
        return $this->_url;
    }

    private function _init()
    {
        $this->_ch = curl_init();
        curl_setopt($this->_ch, CURLOPT_URL, $this->formatUrl());
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->_ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($this->_ch, CURLOPT_NOSIGNAL, true);
        curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($this->_ch, CURLOPT_HEADER, false);
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $this->httpHeader());
        curl_setopt($this->_ch, CURLOPT_USERAGENT, $this->userAgent());
        curl_multi_add_handle($this->_queue, $this->_ch);
        if ($this->proxy)
        {
            curl_setopt($this->_ch, CURLOPT_PROXY, $this->proxy);
        }
    }

    private function _results()
    {
        do {
            while (($code = curl_multi_exec($this->_queue, $active)) == CURLM_CALL_MULTI_PERFORM);
            if ($code != CURLM_OK)
            {
                break;
            }
            while ($done = curl_multi_info_read($this->_queue))
            {
                $info = curl_getinfo($done['handle']);
                $this->responses[] = array(
                    "data"=>curl_multi_getcontent($done['handle']),
                    "info"=>$info,
                    "error"=>curl_error($done['handle']),
                    "runtime"=>$this->isTimeOut($info["connect_time"], $info["total_time"]) //所消耗时间
                );
                curl_multi_remove_handle($this->_queue, $done['handle']);
                curl_close($done['handle']);
            }
            if ($active > 0)
            {
                curl_multi_select($this->_queue, 0.5);
            }
        } while ($active);
        curl_multi_close($this->_queue);
        return $this->responses;
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
        return array(
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language: zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3",
            "Cache-Control: no-cache",
            "Connection: Close",
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8"
        );
    }

    private function userAgent()
    {
        return "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)";
    }
}
