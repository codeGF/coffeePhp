<?php


class Str
{
    
    /**
     * @param unknown $table
     * @param unknown $userid
     * @param string $excision
     * @return string
     */
    public function hashTable($table, $userid, $excision="_")
    {
        $str = crc32($userid);
        if ($str < 0)
        {
            $hash = sprintf("%s%s", 0, substr(abs($str), 0, 1));
        }else
       {
            $hash = substr($str, 0, 2);
        }
        return sprintf("%s%s%s", $table, $excision, $hash);
    }
    
    /**
     * 加载配置文件 支持格式转换 仅支持一级配置
     * @param string $file 配置文件名
     * @param string $parse 配置解析方法 有些格式需要用户自己解析
     * @return array
     */
    function loadConf($file)
    {
        $conf = false;
        $ext  = pathinfo($file, PATHINFO_EXTENSION);
        switch($ext)
        {
            case 'php':
                $conf = require_cache($file);
                break;
            case 'ini':
                $conf = parse_ini_file($file);
                break;
            case 'yaml':
                $conf = yaml_parse_file($file);
                break;
            case 'xml':
                $conf = (array)simplexml_load_file($file);
                break;
            case 'json':
                $conf = json_decode(file_get_contents($file), true);
                break;
            default:
                if(function_exists($ext))
                {
                    System::error(11139, $ext);
                }
        }
        return $conf;
    }

	/**
	 * 对查询结果集进行排序
	 * @access public
	 * @param array $list 查询结果
	 * @param string $field 排序的字段名
	 * @param array $sortby 排序类型
	 * asc正向排序 desc逆向排序 nat自然排序
	 * @return array
	 */
	function list_sort_by($list, $field, $sortby='asc')
	{
		if(is_array($list))
		{
			$refer = $resultSet = array();
			foreach ($list as $i => $data)
				$refer[$i] = &$data[$field];
			switch ($sortby)
			{
				case 'asc': // 正向排序
					asort($refer);
					break;
				case 'desc':// 逆向排序
					arsort($refer);
					break;
				case 'nat': // 自然排序
					natcasesort($refer);
					break;
			}
			foreach ( $refer as $key)
				$resultSet[] = &$list[$key];
			return $resultSet;
		}
		return false;
	}

	/**
	 * XSS过滤，输出内容过滤
	 * @param string $string  需要过滤的字符串
	 * @param string $type    encode HTML处理 | decode 反处理
	 * @return string
	 */
	public function xss($string, $type = 'encode')
	{
		$html = array("&", '"', "'", "<", ">", "%3C", "%3E");
		$html_code = array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;", "&lt;", "&gt;");
		if ($type == 'encode')
		{
			if (function_exists('htmlspecialchars')) return htmlspecialchars($string);
			$str = str_replace($html, $html_code, $string);
		}else
		{
			if (function_exists('htmlspecialchars_decode')) return htmlspecialchars_decode($string);
			$str = str_replace($html_code, $html, $string);
		}
		return $str;
	}

	/**
	 * 检测字符串是否ascii
	 * @param unknown $string
	 * @return boolean
	 */
	public function isAscii($string)
	{
		return (preg_match('/(?:[^\x00-\x7F])/', $string) !== 1);
	}

	/**
	 * 将1,2,3,4,5类似字符转换为数组
	 * @param unknown $str
	 * @return Ambigous <boolean, multitype:unknown , multitype:>
	 */
	public function strListArray($str)
	{
		$result = false;
		if ($str != false && strlen($str) > 1)
		{
			$delimiter = substr(preg_replace("/[0-9]/", "", $str), 0, 1);
			$result = explode($delimiter, trim($str, $delimiter));
		}else if (strlen($str) > 0 && $str != false)
		{
			$result = array($str);
		}
		return $result;
	}

    /**
     * @action 检测字符是汉字还是字符
     * @return 汉字TRUE， 其他字符为FALSE
     * */
    public function is_char($str)
    {
        return ord(substr($str, 0, 1)) > 0xa0;
    }

    /**
     * 对数据进行编码转换
     * @param array/string $data 数组
     * @param string $input 需要转换的编码
     * @param string $output 转换后的编码
     * */
    public function array_iconv($data, $input = 'gbk', $output = 'utf-8')
    {
        if (!is_array($data))
        {
            return $this->charset_encode($data, $input, $output);
        }else
        {
            foreach ($data as $key => $val)
            {
                if (is_array($val))
                {
                    $data [$key] = $this->array_iconv($val, $input, $output);
                }else
              {
                    $data [$key] = $this->charset_encode($val, $input, $output);
                }
            }
            return $data;
        }
    }

    /**
     * 转换字符集
     * @var input 待转换字体
     * @var $_input_charset 输入字符集
     * @var $_output_charset 输出字符集
     * @return char
     */
    public function charset_encode($input, $_input_charset="gbk", $_output_charset="utf-8")
    {
        $output = "";
        if (function_exists("mb_convert_encoding"))
        {
            $output = mb_convert_encoding($input,$_output_charset,$_input_charset);
        }else if(function_exists("iconv"))
        {
            $output = iconv($_input_charset,$_output_charset,$input);
        }else
        {
            System::error(11132);
        }
        return $output;
    }

    /**
     * 字符串截取，支持中文和其他编码
     * @param string $str 需要转换的字符串
     * @param string $length 截取长度
     * @param string $replace 截取后代替字符
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     * @param string $start 开始位置
     * @return string
     * */
    public function msubstr($str, $length, $replace = '...', $charset = 'utf-8', $start = 0, $suffix = true)
    {
        if (function_exists("mb_substr"))
        {
            $slice = mb_substr($str, $start, $length, $charset);
        }elseif (function_exists('iconv_substr'))
        {
            $slice = iconv_substr($str, $start, $length, $charset);
            if (false === $slice)
            {
                $slice = '';
            }
        }else
        {
        	$re = array(); $match = array();
            $re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re ['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re ['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re ['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re [$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }
        unset($str);
        return $suffix ? $slice . $replace : $slice;
    }

    //自动将字符里面的单引号自动加入转义'\'
    public function new_addslashes($string)
    {
        if (!is_array($string))
        {
            return addslashes($string);
        }else
        {
            $tmpstring = array();
            foreach ($string as $key => $val)
            {
                $tmpstring[$key] = $this->new_addslashes($val);
            }
            return $tmpstring;
        }
    }

    /**
     * 检查字符串是否是UTF8编码
     * @param string $string 字符串
     * @return Boolean
     */
    public function is_utf8($str)
    {
        $c = 0; $b = 0;
        $bits = 0;
        $len = strlen($str);
        for($i=0; $i<$len; $i++)
        {
            $c=ord($str[$i]);
            if($c > 128)
            {
                if(($c >= 254)) return false;
                else if($c >= 252) $bits = 6;
                else if($c >= 248) $bits = 5;
                else if($c >= 240) $bits = 4;
                else if($c >= 224) $bits = 3;
                else if($c >= 192) $bits = 2;
                else return false;
                if(($i+$bits) > $len) return false;
                while($bits > 1)
                {
                    $i++;
                    $b = ord($str[$i]);
                    if($b < 128 || $b > 191) return false;
                    $bits--;
                }
            }
        }
        return true;
    }
}