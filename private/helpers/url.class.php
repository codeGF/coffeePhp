<?php


class Url
{
    
    public function is_ssl()
    {
        if(isset($_SERVER["HTTPS"]) && ("1" == $_SERVER["HTTPS"] || "on" == strtolower($_SERVER["HTTPS"])))
        {
            return true;
        }elseif(isset($_SERVER["SERVER_PORT"]) && ("443" == $_SERVER["SERVER_PORT"] ))
        {
            return true;
        }
        return false;
    }

	public function location($url, $time=0) {
	    $url = str_replace(array("\n", "\r"), "", $url); //多行URL地址支持
	    if (!headers_sent())
	    {
	        if (0 === $time)
	        {
	            System::header("Location: ".$url);
	        }else
	        {
	            System::header("refresh:{$time};url={$url}");
	        }
	        System::quit();
	    } else {
	        $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
	        System::quit($str);
	    }
	    return;
	}

    public function getUrl() //获取当前页面完整URL地址
    {
        $sys_protocal = isset($_SERVER ["SERVER_PORT"]) && $_SERVER ["SERVER_PORT"] == "443" ? "https://" : "http://";
        $php_self = $_SERVER ["PHP_SELF"] ? $this->safe_replace($_SERVER ["PHP_SELF"]) : $this->safe_replace($_SERVER ["SCRIPT_NAME"]);
        $path_info = isset($_SERVER ["PATH_INFO"]) ? $this->safe_replace($_SERVER ["PATH_INFO"]) : "";
        $relate_url = isset($_SERVER ["REQUEST_URI"]) ? $this->safe_replace($_SERVER ["REQUEST_URI"]) : $php_self . (isset($_SERVER ["QUERY_STRING"]) ? "?" . $this->safe_replace($_SERVER ["QUERY_STRING"]) : $path_info);
        return $sys_protocal . (isset($_SERVER ["HTTP_HOST"]) ? $_SERVER ["HTTP_HOST"] : "") . $relate_url;
    }

    /**
     * 去除url指定值，类似：aa&=xxxx
     * @param string $param 可以使字符或者字符数组
     * excUrlChar("http://www.a.com?a=xxx&b=xxx", "a"); http://www.a.com?b=xxx
     * excUrlChar("http://www.a.com?a=xxx&b=xxx", array("a", "b")); http://www.a.com
     */
    public function excUrlChar($url, $param)
    {
    	if (is_array($param))
    	{
    		foreach ($param as $v)
    		{
    			$url = preg_replace(array("/{$v}=[^&]*/i", "/[&]+/", "/\?[&]+/", "/[?&]+$/",),array("", "&", "?" , ""), $url);
    		}
    	}else
    	{
    		$url = preg_replace(array("/{$param}=[^&]*/i", "/[&]+/", "/\?[&]+/", "/[?&]+$/",),array("", "&", "?" , ""), $url);
    	}
    	return $url;
    }

    public function safe_replace($string)
    {
    	$string = str_ireplace(array("%20", "%27", "%2527", "*", "\"", "\"", ";", "<", ">", "{", "}", "\\", "or"), null, $string);
    	return $string;
    }

    /**
     * 验证是否死链接
     * return TRUE 有效连接
     * return FALSE 死链接
     * */
    public function isLinkDead($url)
    {
        $info = get_headers($url, 1);
        if (!$info)
        {
            return false;
        }
        return !(stristr($info["0"], "200") && stristr($info["0"], "ok")) ? false : true;
    }

    //发送http状态
    public function send_http_status($code)
    {
        static $_status = array
        (
            100 => "Continue",
            101 => "Switching Protocols",
            200 => "OK",
            201 => "Created",
            202 => "Accepted",
            203 => "Non-Authoritative Information",
            204 => "No Content",
            205 => "Reset Content",
            206 => "Partial Content",
            300 => "Multiple Choices",
            301 => "Moved Permanently",
            302 => "Moved Temporarily ", // 1.1
            303 => "See Other",
            304 => "Not Modified",
            305 => "Use Proxy",
            307 => "Temporary Redirect",
            400 => "Bad Request",
            401 => "Unauthorized",
            402 => "Payment Required",
            403 => "Forbidden",
            404 => "Not Found",
            405 => "Method Not Allowed",
            406 => "Not Acceptable",
            407 => "Proxy Authentication Required",
            408 => "Request Timeout",
            409 => "Conflict",
            410 => "Gone",
            411 => "Length Required",
            412 => "Precondition Failed",
            413 => "Request Entity Too Large",
            414 => "Request-URI Too Long",
            415 => "Unsupported Media Type",
            416 => "Requested Range Not Satisfiable",
            417 => "Expectation Failed",
            500 => "Internal Server Error",
            501 => "Not Implemented",
            502 => "Bad Gateway",
            503 => "Service Unavailable",
            504 => "Gateway Timeout",
            505 => "HTTP Version Not Supported",
            509 => "Bandwidth Limit Exceeded"
        );
        if (isset($_status[$code]))
        {
            System::header("HTTP/1.1 " . $code . " " . $_status[$code]);
            System::header("Status:" . $code . " " . $_status[$code]);
        }
        return;
    }
    
    public function headerType($type) //发送http头信息
    {
        $mime_types = array(
            "ez"=> "application/andrew-inset",
            "hqx"=> "application/mac-binhex40",
            "cpt"=> "application/mac-compactpro",
            "doc"=> "application/msword",
            "bin"=> "application/octet-stream",
            "dms"=> "application/octet-stream",
            "lha"=> "application/octet-stream",
            "lzh"=> "application/octet-stream",
            "exe"=> "application/octet-stream",
            "class"=> "application/octet-stream",
            "so"=> "application/octet-stream",
            "dll"=> "application/octet-stream",
            "oda"=> "application/oda",
            "pdf"=> "application/pdf",
            "ai"=> "application/postscript",
            "eps"=> "application/postscript",
            "ps"=> "application/postscript",
            "smi"=> "application/smil",
            "smil"=> "application/smil",
            "mif"=> "application/vnd.mif",
            "xls"=> "application/vnd.ms-excel",
            "ppt"=> "application/vnd.ms-powerpoint",
            "wbxml"=> "application/vnd.wap.wbxml",
            "wmlc"=> "application/vnd.wap.wmlc",
            "wmlsc"=> "application/vnd.wap.wmlscriptc",
            "bcpio"=> "application/x-bcpio",
            "vcd"=> "application/x-cdlink",
            "pgn"=> "application/x-chess-pgn",
            "cpio"=> "application/x-cpio",
            "csh"=> "application/x-csh",
            "dcr"=> "application/x-director",
            "dir"=> "application/x-director",
            "dxr"=> "application/x-director",
            "dvi"=> "application/x-dvi",
            "spl"=> "application/x-futuresplash",
            "gtar"=> "application/x-gtar",
            "hdf"=> "application/x-hdf",
            "js"=> "application/x-javascript",
            "json"=> "application/json",
            "skp"=> "application/x-koan",
            "skd"=> "application/x-koan",
            "skt"=> "application/x-koan",
            "skm"=> "application/x-koan",
            "latex"=> "application/x-latex",
            "nc"=> "application/x-netcdf",
            "cdf"=> "application/x-netcdf",
            "sh"=> "application/x-sh",
            "shar"=> "application/x-shar",
            "swf"=> "application/x-shockwave-flash",
            "sit"=> "application/x-stuffit",
            "sv4cpio"=> "application/x-sv4cpio",
            "sv4crc"=> "application/x-sv4crc",
            "tar"=> "application/x-tar",
            "tcl"=> "application/x-tcl",
            "tex"=> "application/x-tex",
            "texinfo"=> "application/x-texinfo",
            "texi"=> "application/x-texinfo",
            "t"=> "application/x-troff",
            "tr"=> "application/x-troff",
            "roff"=> "application/x-troff",
            "man"=> "application/x-troff-man",
            "me"=> "application/x-troff-me",
            "ms"=> "application/x-troff-ms",
            "ustar"=> "application/x-ustar",
            "src"=> "application/x-wais-source",
            "xhtml"=> "application/xhtml+xml",
            "xht"=> "application/xhtml+xml",
            "zip"=> "application/zip",
            "au"=> "audio/basic",
            "snd"=> "audio/basic",
            "mid"=> "audio/midi",
            "midi"=> "audio/midi",
            "kar"=> "audio/midi",
            "mpga"=> "audio/mpeg",
            "mp2"=> "audio/mpeg",
            "mp3"=> "audio/mpeg",
            "aif"=> "audio/x-aiff",
            "aiff"=> "audio/x-aiff",
            "aifc"=> "audio/x-aiff",
            "m3u"=> "audio/x-mpegurl",
            "ram"=> "audio/x-pn-realaudio",
            "rm"=> "audio/x-pn-realaudio",
            "rpm"=> "audio/x-pn-realaudio-plugin",
            "ra"=> "audio/x-realaudio",
            "wav"=> "audio/x-wav",
            "pdb"=> "chemical/x-pdb",
            "xyz"=> "chemical/x-xyz",
            "bmp"=> "image/bmp",
            "gif"=> "image/gif",
            "ief"=> "image/ief",
            "jpeg"=> "image/jpeg",
            "jpg"=> "image/jpeg",
            "jpe"=> "image/jpeg",
            "png"=> "image/png",
            "tiff"=> "image/tiff",
            "tif"=> "image/tiff",
            "djvu"=> "image/vnd.djvu",
            "djv"=> "image/vnd.djvu",
            "wbmp"=> "image/vnd.wap.wbmp",
            "ras"=> "image/x-cmu-raster",
            "pnm"=> "image/x-portable-anymap",
            "pbm"=> "image/x-portable-bitmap",
            "pgm"=> "image/x-portable-graymap",
            "ppm"=> "image/x-portable-pixmap",
            "rgb"=> "image/x-rgb",
            "xbm"=> "image/x-xbitmap",
            "xpm"=> "image/x-xpixmap",
            "xwd"=> "image/x-xwindowdump",
            "igs"=> "model/iges",
            "iges"=> "model/iges",
            "msh"=> "model/mesh",
            "mesh"=> "model/mesh",
            "silo"=> "model/mesh",
            "wrl"=> "model/vrml",
            "vrml"=> "model/vrml",
            "css"=> "text/css",
            "html"=> "text/html",
            "htm"=> "text/html",
            "asc"=> "text/plain",
            "txt"=> "text/plain",
            "rtx"=> "text/richtext",
            "rtf"=> "text/rtf",
            "sgml"=> "text/sgml",
            "sgm"=> "text/sgml",
            "tsv"=> "text/tab-separated-values",
            "wml"=> "text/vnd.wap.wml",
            "wmls"=> "text/vnd.wap.wmlscript",
            "etx"=> "text/x-setext",
            "xsl"=> "text/xml",
            "xml"=> "text/xml",
            "mpeg"=> "video/mpeg",
            "mpg"=> "video/mpeg",
            "mpe"=> "video/mpeg",
            "qt"=> "video/quicktime",
            "mov"=> "video/quicktime",
            "mxu"=> "video/vnd.mpegurl",
            "avi"=> "video/x-msvideo",
            "movie"=> "video/x-sgi-movie",
            "ice"=> "x-conference/x-cooltalk",
        );
        System::header("Content-Type: {$mime_types[$type]};charset=".ServiceManager::get("SYSTEMCONF@SYSTEM_ENCODING"));
        return;
    }
}