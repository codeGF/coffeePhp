<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class System
{

	public static function header($info)
	{
		if (is_array($info) == true)
		{
			foreach ($info as $v)
			{
				header($v);
			}
		}else
		{
			header($info);
		}
		return;
	}

	public static function hash($mix)
	{
	    if (is_object($mix))
	    {
	        $mix = spl_object_hash($mix);
	    }elseif (is_resource($mix))
	    {
	        $mix = get_resource_type($mix) . strval($mix);
	    }else 
	    {
	        $mix = serialize($mix);
	    }
	    return md5($mix);
	}

	public static function quit($str=false)
	{
		exit($str);
	}

	public static function error($code, $message)
	{
		throw new PrivateException(array("message"=>$message, "code"=>$code), 0);
		self::quit();
	}

	public static function printr($obj)
	{
	    print("<pre>");
	    print_r($obj);
	    print("</pre>");
	    return;
	}
	
	public static function dump($var, $echo=true, $label=null, $strict=true)
	{
	    $label = ($label === null) ? "" : rtrim($label)."";
	    if ($strict == false)
	    {
	        if (ini_get("html_errors"))
	        {
	            $output = print_r($var, true);
	            $output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
	        }else
	       {
	            $output = $label . print_r($var, true);
	        }
	    }else
	    {
	        ob_start();
	        var_dump($var);
	        $output = ob_get_clean();
	        if (!extension_loaded("xdebug"))
	        {
	            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
	            $output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
	        }
	    }
	    if ($echo == true)
	    {
	        print($output);
	        return false;
	    }else
	    {
	        return $output;
	    }
	}
}