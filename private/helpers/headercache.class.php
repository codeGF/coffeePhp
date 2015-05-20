<?php


/**
 * 头缓存
 * @author changguofeng
 * @param int date 缓存有效时间
 */

class HeaderCache extends Base
{
    
    public $date = 10;
    
    public function main()
    {
        if ($_SERVER['HTTP_IF_MODIFIED_SINCE'])
        {
            $ctime = bcadd(strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']), $this->date);
            if($ctime > $this->system_->date)
            {
                $this->auto_->helpers->url->send_http_status(304);
                System::quit();
            }
        }
        $this->setHeader();
        return;
    }
    
    public function setHeader()
    {
        $expires = gmdate("D, d M Y H:i:s", bcadd($this->system_->date, $this->date));
        $lastModified = gmdate("D, d M Y H:i:s");
        System::header
        (
            array
           (
                "Pragma: privat",
                "Cache-Control:max-age={$this->date}, pre-check={$this->date}",
                "Expires: {$expires} GMT",
                "Last-Modified: {$lastModified} GMT"
            )
        );
        return;
    }
}